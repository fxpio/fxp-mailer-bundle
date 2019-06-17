Send email with Symfony Mailer
==============================

## Installation

To use the Email transporter, you must install the [Symfony Mailer](https://symfony.com/doc/current/mailer.html):

```
$ composer require symfony/mailer
```

Read the documentation to install and configure the [Symfony Mailer](https://symfony.com/doc/current/mailer.html).

## Basic usage

You can use directly the Symfony Mailer service to send your email, but but you can also use
the Fxp Mailer service. The advantage of this service is to automatically select the good
transporter according to the message type (email, sms, etc...).

```php
// src/Controller/MailerController.php
namespace App\Controller;

use Fxp\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('francois@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Fxp Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Localizable Twig loader for better HTML integration!</p>');

        $mailer->send($email);

        // ...
    }
}
```
