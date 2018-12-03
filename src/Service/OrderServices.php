<?php
namespace App\Service;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Knp\Snappy\Pdf;


class OrderServices
{
    private $pdf;
    private $template;
    private $mailer;

    public function __construct(Pdf $pdf,\Twig_Environment $template, \Swift_Mailer $mailer)
    {
        $this->pdf = $pdf;
        $this->template = $template;
        $this->mailer = $mailer;
    }

    public function orderCharge($stripeSecretKey, $order, $token)
    {
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        $charge = \Stripe\Charge::create([
            'amount' => ($order->getPrice())*100,
            'currency' => 'eur',
            'description' => 'Example charge',
            'source' => $token,
        ]);
    }

    public function barcodeGenerator($code, $tickets)
    {
        for ($i= 0; $i<count($tickets); $i++ ){
            $barcode = new BarcodeGenerator();
            $barcode->setText($tickets[$i]->getId());
            $barcode->setType(BarcodeGenerator::Code128);
            $barcode->setScale(1);
            $barcode->setThickness(25);
            $barcode->setFontSize(0);
            $code[$tickets[$i]->getId()]= $barcode->generate();
        }
        return $code;
    }

    public function pdfGenerator($order, $code)
    {
        $this->pdf->generateFromHtml(
            $this->template->render(
                'ticketing/attachment.html.twig',
                array(
                    'order' => $order,
                    'code' => $code
                )
            ),
            './pdf/commande'.$order->getId().'.pdf'
        );
    }

    public function sendMail($order)
    {
        var_dump($order->getEmail());
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('lioneldarosa@gmail.com')
            ->setTo($order->getEmail())
            ->setBody(
                $this->template->render(
                    'ticketing/mail.html.twig',
                    array(
                        'order' => $order,
                    )
                ),
                'text/html'
            )
            ->attach(\Swift_Attachment::fromPath('./pdf/commande'.$order->getId().'.pdf'))
        ;
        $this->mailer->send($message);
    }
}