<?php
namespace App\Mail;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Http;

class ResendTransport extends AbstractTransport
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return 'resend';
    }

    protected function doSend(RawMessage $message, Envelope $envelope): SentMessage
    {
        // تأكد أن الرسالة من نوع Email
        if (!$message instanceof Email) {
            throw new \LogicException('ResendTransport يدعم فقط رسائل من نوع Email.');
        }

        $from = $message->getFrom()[0]->getAddress();
        $to = array_map(fn($t) => $t->getAddress(), $message->getTo());
        $subject = $message->getSubject();
        $html = $message->getHtmlBody();

        Http::withToken(env('RESEND_API_KEY'))
            ->post('https://api.resend.com/emails', [
                'from' => $from,
                'to' => $to,
                'subject' => $subject,
                'html' => $html,
            ]);

        return new SentMessage($message, $envelope);
    }
}
