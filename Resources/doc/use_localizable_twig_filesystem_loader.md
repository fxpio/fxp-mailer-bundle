Use the localizable Twig filesystem loader
==========================================

## Save localized templates

To use the localizable Twig filesystem loader, you simply have to use the `@template_message`
namespace to the Twig filename. Each template file must be saved with the locale in her
filename like: `<NAME>.<LOCALE>.html.twig`.

## Select a localized template

If the locale is not defined at the end of Twig filename, the default locale is used:

```php
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

$email = (new TemplatedEmail())
    // email config
    ->htmlTemplate('@templates/emails/welcome/welcome.html.twig')
;
```

To select manually the locale, you must add the valid locale in the filename
(example `@templates/<PATH_IN_TEMPLATES_DIR>/<NAME>.<LOCALE>.html.twig`) like:

```php
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

$email = (new TemplatedEmail())
    // email config
    ->htmlTemplate('@templates/emails/welcome/welcome.fr_FR.html.twig')
    // or
    ->htmlTemplate('@templates/emails/welcome/welcome.fr.html.twig')
;
```

The Loader will look for the file with the complete locale, and if it does not find it,
the Loader will look for the file with only the country name.

If the template is not found with the locale, the Loader will look for the file with the
locale defined in fallback.
