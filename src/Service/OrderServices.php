<?php

namespace App\Service;

use App\Entity\Order;
use App\Stripe\ChargeManager;
use App\Utils\Mailer;
use App\Utils\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderServices
 * @package App\Service
 */
class OrderServices
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ChargeManager
     */
    private $stripeManager;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var PdfGenerator
     */
    private $pdfGenerator;

    /**
     * OrderServices constructor.
     * @param EntityManagerInterface $entityManager
     * @param ChargeManager $stripeManager
     * @param Mailer $mailer
     * @param PdfGenerator $pdfGenerator
     */
    public function __construct(EntityManagerInterface $entityManager, ChargeManager $stripeManager, Mailer $mailer, PdfGenerator $pdfGenerator)
    {
        $this->entityManager = $entityManager;
        $this->stripeManager = $stripeManager;
        $this->mailer = $mailer;
        $this->pdfGenerator = $pdfGenerator;
    }

    /**
     * @param Order $order
     * @param Request $request
     */
    public function valid(Order $order, Request $request)
    {
        $order->setStripeToken($request->request->get("stripeToken"));
        $order->setPayed(true);
        $this->entityManager->flush();
        $this->stripeManager->create($order->getPrice()*100, $order->getStripeToken(), "Commande billets Louvre");
    }

    /**
     * @param Order $order
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(Order $order)
    {
        $filename = './pdf/commande'.$order->getId().'.pdf';

        $this->pdfGenerator->generate(
            'ticketing/attachment.html.twig',
            ['order' => $order],
            $filename
        );

        $this->mailer->send(
            "Louvre - Votre commande de billets",
            $order->getEmail(),
            'ticketing/mail.html.twig',
            ['order' => $order],
            [$filename]
        );

        unlink($filename);
    }
}