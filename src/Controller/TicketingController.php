<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Form\OrderType;
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
            $lastId= $this->getDoctrine()
                ->getRepository(Order::class)
                ->findBy(array(), array('id'=>'desc'),1,0);
            return $this->redirect('/payment/' . $lastId[0]->getId());
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
    public function payment($stripePublicKey, $id){
        $order= $this->getDoctrine()->getRepository(Order::class)->find($id);
        return $this->render('ticketing/payment.html.twig', [
            'order' => $order,
            'public' => $stripePublicKey
        ]);
    }

    /**
     * @Route("/valid/{id}", name="valid")
     * @param $id
     */
    public function valid(Request $request, $stripeSecretKey, $id){
        $entityManager = $this->getDoctrine()->getManager();
        $order= $entityManager->getRepository(Order::class)->find($id);
        $token=$request->request->get("stripeToken");
        $order->setStripeToken($token);
        $entityManager->flush();
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        $charge = \Stripe\Charge::create([
            'amount' => ($order->getPrice())*100,
            'currency' => 'eur',
            'description' => 'Example charge',
            'source' => $token,
        ]);
        return $this->redirectToRoute('ticketing');
    }

    /**
     * @Route("/test", name="test")
     *
     */
    public function test(){

        $lastId= $this->getDoctrine()->getRepository(Order::class)->findBy(array(), array('id'=>'desc'),1,0);
        var_dump($lastId[0]->getId());

    }
}
