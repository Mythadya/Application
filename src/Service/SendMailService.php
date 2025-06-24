<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; // Import the Email class
use Psr\Log\LoggerInterface; // Import the LoggerInterface

class SendMailService
{
    private $mailer;
    private $logger; // Add the logger

    public function __construct(MailerInterface $mailer, LoggerInterface $logger) // Inject the logger
    {
        $this->mailer = $mailer;
        $this->logger = $logger; // Initialize the logger
    }

   public function send(
    string $from,
    string $to,
    string $subject,
    string $template,
    array $context
): void {
    try {
        // For testing ONLY (uncomment when needed)
        /*
        $testEmail = (new Email())
            ->from($from)
            ->to($to)
            ->subject('[TEST] ' . $subject)
            ->text('This is a test email.');
        $this->mailer->send($testEmail);
        */
        
        // Actual templated email
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
        
    } catch (\Exception $e) {
        $this->logger->error('Email sending error: ' . $e->getMessage(), ['exception' => $e]);
        throw $e;
    }
}
}





// namespace App\Service;

// use Symfony\Bridge\Twig\Mime\TemplatedEmail;
// use Symfony\Component\Mailer\MailerInterface;

// class SendMailService
// {
//     private $mailer;

//     // Correct the constructor name
//     public function __construct(MailerInterface $mailer)
//     {
//         $this->mailer = $mailer; // Initialize the mailer
//     }

//     public function send(
//         string $from,
//         string $to,
//         string $subject,
//         string $template,
//         array $context // Change type from string to array
//     ): void
//     {
//        // In SendMailService.php
// $email = (new TemplatedEmail())
//     ->from($from)
//     ->to($to)
//     ->subject($subject)
//     ->htmlTemplate("emails/$template.html.twig") // No duplicate "emails/"
//     ->context($context);

//         // on envoie le mail
//         $this->mailer->send($email);
//     }
// }