Enable the Doctrine template message
====================================

## Installation

### Step 1: Create your template message model

Run this command to create the Template Message entity:

```
$ php bin/console make:entity

 Class name of the entity to create or update (e.g. BraveGnome):
 > TemplateMessage

 created: src/Entity/TemplateMessage.php
 created: src/Repository/TemplateMessageRepository.php

 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 >
```

**Make the Template Message entity compatible with this bundle**

To make your Template Message entity compatible with this bundle, you must update the entity by implementing the interface
`Fxp\Component\Mailer\Model\TemplateMessgeInterface` and the trait `Fxp\Component\Mailer\Model\Traits\TemplateMessgeTrait` like:

```php
use Fxp\Component\Mailer\Model\TemplateMessageInterface;
use Fxp\Component\Mailer\Model\Traits\TemplateMessageTrait;

class TemplateMessage implements TemplateMessageInterface
{
    use TemplateMessageTrait;

    // ...
}
```

To make your Template Message repository compatible with this bundle, you must update the repository by implementing the interface
`Fxp\Component\Mailer\Doctrine\Repository\TemplateMessageRepositoryInterface` and the trait
`Fxp\Component\Mailer\Doctrine\Repository\Traits\TemplateMessageRepositoryTrait` like:

```php
use Fxp\Component\Mailer\Doctrine\Repository\TemplateMessageRepositoryInterface;
use Fxp\Component\Mailer\Doctrine\Repository\Traits\TemplateMessageRepositoryTrait;

class TemplateMessageRepository extends ServiceEntityRepository implements TemplateMessageRepositoryInterface
{
    use TemplateMessageRepositoryTrait;

    // ...
}
```

### Step 2: Configure your application

Add the interface in Doctrine's target entities resolver:

```yaml
# config/packages/doctrine.yaml
doctrine:
    # ...
    orm:
        resolve_target_entities:
            Fxp\Component\Mailer\Model\TemplateMessgeInterface: App\Entity\TemplateMessge # the FQCN of your template message entity
```

And the Doctrine Twig loader must be enabled:

```yaml
# config/packages/fxp_mailer.yaml
fxp_mailer:
    twig:
        loaders:
            doctrine: true
```

Also, make sure to make and run a migration for the new entities:

```
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

## Next Steps

[Enable the Doctrine translation](enable_doctrine_translation.md)
