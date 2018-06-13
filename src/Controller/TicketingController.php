<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketingController extends Controller
{
    /**
     * @Route("/", name="ticketing")
     */
    public function index()
    {
        return $this->render('ticketing/index.html.twig', [
            'controller_name' => 'TicketingController',
        ]);
    }
}
