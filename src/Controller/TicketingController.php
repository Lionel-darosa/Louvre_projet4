<?php

namespace App\Controller;

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
            return $this->redirectToRoute('ticketing');
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
}
