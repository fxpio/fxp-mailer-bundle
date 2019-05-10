Getting Started With Fxp MailerBundle
=====================================

## Installation

Installation is a quick, 6 step process:

1. Download the bundle using composer
2. Create your entity classes (optional)
3. Configure your application's config.yml
4. Update your database schema
5. Configure the bundle

### Step 1: Download the bundle using composer

In applications using [Symfony Flex](https://symfony.com/doc/current/setup/flex.html), run this command to install
the security feature before using it:

```
$ composer require fxp/security-bundle
```

### Step 2: Create your entity classes (optional)

#### Create the Layout class

You can use `Fxp\Component\Mailer\Entity\TemplateLayout` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/Layout.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fxp\Component\Mailer\Model\TemplateLayout as BaseTemplateLayout;

class TemplateLayout extends BaseTemplateLayout
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * Get the id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->mails = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }
}
```

#### Create the Layout translation class

You can use `Fxp\Component\Mailer\Entity\TemplateLayoutTranslation` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/LayoutTranslation.php

namespace App\Entity;

use Fxp\Component\Mailer\Model\TemplateLayoutTranslation as BaseTemplateLayoutTranslation;

class TemplateLayoutTranslation extends BaseTemplateLayoutTranslation
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * Get the id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}
```

#### Create the Mail class

You can use `Fxp\Component\Mailer\Entity\TemplateMail` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/Mail.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fxp\Component\Mailer\Model\TemplateMail as BaseTemplateMail;

class TemplateMail extends BaseTemplateMail
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * Get the id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
}
```

#### Create the mail translation class

You can use `Fxp\Component\Mailer\Entity\TemplateMailTranslation` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/MailTranslation.php

namespace App\Entity;

use Fxp\Component\Mailer\Model\TemplateMailTranslation as BaseTemplateMailTranslation;

class TemplateMailTranslation extends BaseTemplateMailTranslation
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * Get the id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}
```

#### Create the Layout mapping

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                  http://raw.github.com/doctrine/doctrine2/master/doctrine-mapping.xsd">

    <entity name="App\Entity\TemplateLayout" table="template_layout">

        <indexes>
            <index name="layout_name_idx" columns="name" />
        </indexes>

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>

        <one-to-many field="mails" target-entity="Mail" mapped-by="layout" orphan-removal="false" fetch="EXTRA_LAZY">
            <order-by>
                <order-by-field name="label" direction="ASC" />
            </order-by>
        </one-to-many>

        <one-to-many field="translations" target-entity="LayoutTranslation" mapped-by="layout" orphan-removal="true" fetch="EAGER">
            <cascade>
                <cascade-all/>
            </cascade>
            <order-by>
                <order-by-field name="locale" direction="ASC" />
            </order-by>
        </one-to-many>

    </entity>
</doctrine-mapping>
```

#### Create the Layout translation mapping

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                  http://raw.github.com/doctrine/doctrine2/master/doctrine-mapping.xsd">

    <entity name="App\Entity\TemplateLayoutTranslation" table="template_layout_translation">

        <unique-constraints>
            <unique-constraint columns="layout_id,locale" name="layout_translation_unique_locale_idx" />
        </unique-constraints>

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>

        <many-to-one field="layout" target-entity="Layout" inversed-by="translations">
            <join-column name="layout_id" referenced-column-name="id" on-delete="CASCADE" />
        </many-to-one>

    </entity>
</doctrine-mapping>
```

#### Create the Mail mapping

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                  http://raw.github.com/doctrine/doctrine2/master/doctrine-mapping.xsd">

    <entity name="App\Entity\TemplateMail" table="template_mail">

        <indexes>
            <index name="mail_name_idx" columns="name" />
        </indexes>

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>

        <one-to-many field="translations" target-entity="MailTranslation" mapped-by="mail" orphan-removal="true" fetch="EAGER">
            <cascade>
                <cascade-all/>
            </cascade>
            <order-by>
                <order-by-field name="locale" direction="ASC" />
            </order-by>
        </one-to-many>

        <many-to-one field="layout" target-entity="Layout" inversed-by="mails">
            <join-column name="layout_id" referenced-column-name="id" on-delete="SET NULL" />
        </many-to-one>

    </entity>
</doctrine-mapping>
```

#### Create the Mail translation mapping

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                  http://raw.github.com/doctrine/doctrine2/master/doctrine-mapping.xsd">

    <entity name="App\Entity\TemplateMailTranslation" table="template_mail_translation">

        <unique-constraints>
            <unique-constraint columns="mail_id,locale" name="mail_translation_unique_locale_idx" />
        </unique-constraints>

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>

        <many-to-one field="mail" target-entity="Mail" inversed-by="translations">
            <join-column name="mail_id" referenced-column-name="id" on-delete="CASCADE" />
        </many-to-one>

    </entity>
</doctrine-mapping>
```

### Step 4: Configure your application's config.yml

Add the interface in Doctrine's target entities resolver:

```yaml
# config/packages/doctrine.yaml``
doctrine:
    # ...
    orm:
        resolve_target_entities:
            Fxp\Component\Mailer\Model\TemplateLayoutInterface: App\Entity\TemplateLayout # the FQCN of your template layout entity
            Fxp\Component\Mailer\Model\TemplateLayoutTranslationInterface: App\Entity\TemplateLayoutTranslation # the FQCN of your template layout translation entity
            Fxp\Component\Mailer\Model\TemplateMailInterface: App\Entity\TemplateMail # the FQCN of your template mail entity
            Fxp\Component\Mailer\Model\TemplateMailTranslationInterface: App\Entity\TemplateMailTranslation # the FQCN of your template mail translation entity
```

Also, make sure to make and run a migration for the new entities:

```
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Fxp MailerBundle, you are ready to learn about usages of the bundle.
