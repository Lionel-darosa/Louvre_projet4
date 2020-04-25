<?php

namespace App\Utils;
use Knp\Snappy\Pdf;

/**
 * Class PdfGenerator
 * @package App\Utils
 */
class PdfGenerator
{
    /**
     * @var Pdf
     */
    private $pdf;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * PdfGenerator constructor.
     * @param Pdf $pdf
     * @param \Twig_Environment $twig
     */
    public function __construct(Pdf $pdf,\Twig_Environment $twig)
    {
        $this->pdf = $pdf;
        $this->twig = $twig;
    }

    /**
     * @param string $view
     * @param array $data
     * @param string $filename
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function generate(string $view, array $data = [], string $filename): void
    {
        $this->pdf->generateFromHtml($this->twig->render($view,$data), $filename);
    }
}