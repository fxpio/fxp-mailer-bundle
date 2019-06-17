Use the Twig Sandbox
====================

The [Sandbox extension](https://twig.symfony.com/doc/2.x/api.html#sandbox-extension) can be
used to evaluate untrusted code, and access to unsafe attributes and methods is prohibited. The
sandbox security is managed by the default policy instance of `Twig\Sandbox\SecurityPolicy`.

## Configuration

By default, the security policy is configured with the configuration defined in the class
`Fxp\Component\Mailer\TwigSecurityPolicies`. However, you can override or add new configurations in the bundle configuration:

```yaml
fxp_mailer:
    twig:
        sandbox:
            security_policy:
                allowed_tags:
                    - custom_tag
                allowed_filters:
                    - custom_filter
                allowed_methods:
                    App\Foo\Bar:
                        - bazMethod
                allowed_properties:
                    App\Foo\Bar:
                        - bazProperty
                allowed_functions:
                    - custom_function
```

## Usage with emails

To define the contents of your email with Twig and enable the Sandbox, use the
`Fxp\Component\Mailer\Twig\Mime\SandboxTemplatedEmail` class.
This class extends the normal Email class but adds some new methods for Twig templates:

```php
use Fxp\Component\Mailer\Twig\Mime\SandboxTemplatedEmail;

$email = (new SandboxTemplatedEmail())
    ->from('francois@example.com')
    ->to(new NamedAddress('philippe@example.com', 'Philippe'))
    ->subject('Thanks for signing up!')

    // path of the Twig template to render
    ->htmlTemplate('emails/signup.html.twig')

    // pass variables (name => value) to the template
    ->context([
        'expiration_date' => new \DateTime('+7 days'),
        'username' => 'foo',
    ])
;
```
