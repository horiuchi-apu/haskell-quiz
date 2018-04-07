<?php
/**
 * Created by PhpStorm.
 * User: Horiuchi
 * Date: 2018/04/07
 * Time: 23:50
 */
namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(\Swift_Mailer $swiftMailer, Environment $environment, ContainerInterface $container)
    {
        $this->swiftMailer = $swiftMailer;
        $this->environment = $environment;
        $this->container   = $container;
    }


    public function send(string $path, $params = [])
    {
        $template = $this->environment->load($path);

        $subject = $template->renderBlock('subject', $params);
        $from    = $this->container->getParameter('admin_email');
        $to      = $template->renderBlock('to', $params);
        $body    = $template->renderBlock('body', $params);

        $message = new \Swift_Message($subject);
        $message->setFrom($from)
            ->setTo($to)
            ->setBody($body,
                'text/plain'
            )
        ;

        if (!empty($cc = $template->renderBlock('cc'))) {
            $message->setCc($cc);
        }

        if (!empty($bcc = $template->renderBlock('bcc'))) {
            $message->setBcc($bcc);
        }

        $this->swiftMailer->send($message);
    }
}