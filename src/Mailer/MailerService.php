<?php

namespace App\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function sendMail(string $from, string $to, string $subject, string $body): void
    {
        $email =  (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}