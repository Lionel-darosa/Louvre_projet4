<?php

namespace App\Twig;

use App\Entity\Ticket;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('barcode', [$this, 'generateBarcode']),
        ];
    }

    /**
     * @param Ticket $ticket
     * @return string
     */
    public function generateBarcode(Ticket $ticket): string
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText($ticket->getId());
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(1);
        $barcode->setThickness(25);
        $barcode->setFontSize(0);
        return $barcode->generate();
    }
}