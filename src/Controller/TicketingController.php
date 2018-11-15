<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Form\OrderType;
use App\Service\OrderServices;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TicketingController
 * @package App\Controller
 */
class TicketingController extends Controller
{
    /**
     * @Route("/", name="ticketing")
     *
     * @param Request                   $request
     * @param EntityManagerInterface    $manager
     */
    public function index(Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(OrderType::class)->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $manager->persist($form->getData());
            $manager->flush();
            return $this->redirect('/payment/' . $form->getData()->getId());
        }
        return $this->render('ticketing/index.html.twig', [
            'controller_name' => 'TicketingController',
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/thousand/{day}", name="thousand")
     *
     * @param $day
     */
    public function thousandTickets($day){
        $choiceDate= new \DateTime($day);
        $nbr= $this->getDoctrine()
            ->getRepository(Ticket::class)
            ->countTicketsByVisitDate($choiceDate);
        return $this->json($nbr);
    }

    /**
     * @Route("/payment/{id}", name="payment")
     * @param $id
     * @return Response
     */
    public function payment($stripePublicKey,Order $order){
        $this->denyAccessUnlessGranted('payment', $order);
        return $this->render('ticketing/payment.html.twig', [
            'order' => $order,
            'public' => $stripePublicKey
        ]);
    }

    /**
     * @Route("/valid/{id}", name="valid")
     * @param $id
     */
    public function valid(Request $request, $stripeSecretKey, Order $order, OrderServices $orderServices){
        $entityManager = $this->getDoctrine()->getManager();
        $token=$request->request->get("stripeToken");
        $order->setStripeToken($token);
        $order->setPayed(true);
        $entityManager->flush();
        $orderServices->orderCharge($stripeSecretKey, $order, $token);
        return $this->redirect('/send/'.$order->getId());
    }

    /**
     * @Route("/send/{id}", name="send")
     */
    public function send(Order $order, \Swift_Mailer $mailer, OrderServices $orderServices){
        $this->denyAccessUnlessGranted('send', $order);
        $tickets= $order->getTickets();
        $code=[];
        $code= $orderServices->barcodeGenerator($code, $tickets);
        $orderServices->pdfGenerator($order, $code);
        $orderServices->sendMail($order);
        unlink('./pdf/commande'.$order->getId().'.pdf');

        return $this->render('ticketing/mail.html.twig', [
            'order' => $order,
            'code' => $code
        ]);
    }

}
