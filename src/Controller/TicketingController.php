<?php

namespace App\Controller;

use App\Entity\Order;
use App\Handler\OrderHandler;
use App\Repository\TicketRepository;
use App\Service\OrderServices;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TicketingController
 * @package App\Controller
 */
class TicketingController extends Controller
{
    /**
     * @Route("/", name="ticketing")
     * @param Request $request
     * @param OrderHandler $orderHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function index(Request $request, OrderHandler $orderHandler)
    {
        if ($orderHandler->handle($request)){
            return $this->redirectToRoute("payment", ["id" => $orderHandler->getOrder()->getId()]);
        }
        return $this->render('ticketing/index.html.twig', [
            "form" => $orderHandler->createView()
        ]);
    }

    /**
     * @Route("/thousand/{day}", name="thousand")
     * @param string $day
     * @param TicketRepository $ticketRepository
     * @return JsonResponse
     */
    public function thousandTickets(string $day, TicketRepository $ticketRepository)
    {
        return $this->json($ticketRepository->countTicketsByVisitDate(new \DateTime($day)));
    }

    /**
     * @Route("/payment/{id}", name="payment")
     * @IsGranted("payment", subject="order")
     * @param $id
     * @return Response
     */
    public function payment(string $stripePublicKey,Order $order)
    {
        return $this->render('ticketing/payment.html.twig', [
            'order' => $order,
            'public' => $stripePublicKey
        ]);
    }

    /**
     * @Route("/valid/{id}", name="valid")
     * @param Request $request
     * @param OrderServices $orderServices
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function valid(Request $request, OrderServices $orderServices, Order $order)
    {
        $orderServices->valid($order, $request);
        return $this->redirectToRoute('send', ["id" => $order->getId()]);
    }

    /**
     * @Route("/send/{id}", name="send")
     * @IsGranted("send", subject="order")
     * @param Order $order
     * @param OrderServices $orderServices
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(Order $order, OrderServices $orderServices)
    {
        $orderServices->send($order);
        return $this->redirectToRoute('ticketing');
    }

}
