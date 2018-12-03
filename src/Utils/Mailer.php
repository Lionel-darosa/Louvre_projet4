<?php

namespace App\Utils;

/**
 * Class Mailer
 * @package App\Utils
 */
class Mailer
{

    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Mailer constructor.
     * @param \Twig_Environment $template
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Twig_Environment $template, \Swift_Mailer $mailer)
    {
        $this->template = $template;
        $this->mailer = $mailer;
    }

    /**
     * @param string $subject
     * @param string $email
     * @param string $view
     * @param array $data
     * @param array $attachments
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(string $subject, string $email, string $view, array $data = [], array $attachments = []): void
    {
        $message = (new \Swift_Message($subject))
            ->setFrom('billeterie@louvre.com', 'Billeterie du Louvre')
            ->setTo($email)
            ->setBody($this->template->render($view, $data), 'text/html')
        ;

        foreach($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        $this->mailer->send($message);
    }

}