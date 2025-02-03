<?php

declare(strict_types=1);

namespace App\Notifier;

use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;

class CustomLoginLinkNotification extends LoginLinkNotification
{
    public function asEmailMessage(EmailRecipientInterface $recipient, ?string $transport = null): ?EmailMessage
    {
        $emailMessage = parent::asEmailMessage($recipient, $transport);

        $email = $emailMessage->getMessage();
        $email->from($_ENV['ADMIN_EMAIL']);
        //$email->htmlTemplate('emails/custom_login_link_email.html.twig');

        return $emailMessage;
    }
}
