<?php

namespace App\Controller;

use App\Form\OrderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketingController extends Controller
{
    /**
     * @Route("/", name="ticketing")
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
}
