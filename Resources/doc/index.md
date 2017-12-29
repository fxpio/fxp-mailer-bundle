Getting Started With Fxp MailerBundle
=========================================

## Prerequisites

This version of the bundle requires Symfony 3.

## Installation

Installation is a quick, 6 step process:

1. Download the bundle using composer
2. Enable the bundle
3. Create your entity classes
4. Configure your application's config.yml
5. Update your database schema
6. Configure the bundle

### Step 1: Download the bundle using composer

Tell composer to download the bundle by running the command:

```bash
$ composer require fxp/mailer-bundle
```

Composer will install the bundle to your project's `vendor/fxp` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Fxp\Bundle\MailerBundle\FxpMailerBundle(),
    );
}
```

### Step 3: Create your entity classes

#### Create the Layout class

You can use `Fxp\Component\Mailer\Entity\Layout` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/Layout.php

namespace Acme\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fxp\Component\Mailer\Model\Layout as BaseLayout;

class Layout extends BaseLayout
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

You can use `Fxp\Component\Mailer\Entity\LayoutTranslation` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/LayoutTranslation.php

namespace Acme\CoreBundle\Entity;

use Fxp\Component\Mailer\Model\LayoutTranslation as BaseLayoutTranslation;

class LayoutTranslation extends BaseLayoutTranslation
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

You can use `Fxp\Component\Mailer\Entity\Mail` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/Mail.php

namespace Acme\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fxp\Component\Mailer\Model\Mail as BaseMail;

class Mail extends BaseMail
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

You can use `Fxp\Component\Mailer\Entity\MailTranslation` class or create the entity class:

``` php
// src/Acme/CoreBundle/Entity/MailTranslation.php

namespace Acme\CoreBundle\Entity;

use Fxp\Component\Mailer\Model\MailTranslation as BaseMailTranslation;

class MailTranslation extends BaseMailTranslation
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

    <entity name="Acme\CoreBundle\Entity\Layout" table="layout">

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

    <entity name="Acme\CoreBundle\Entity\LayoutTranslation" table="layout_translation">

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

    <entity name="Acme\CoreBundle\Entity\Mail" table="mail">

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

    <entity name="Acme\CoreBundle\Entity\MailTranslation" table="mail_translation">

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

Add the following configuration to your `config.yml`.

```yaml
# app/config/config.yml
fxp_mailer:
    layout_class: Acme\CoreBundle\Entity\Layout
    mail_class:   Acme\CoreBundle\Entity\Mail
```

### Step 5: Update your database schema

```bash
$ php app/console doctrine:schema:update --force
```

### Step 6: Configure the bundle

You can override the default configuration adding `fxp_mailer` tree in `app/config/config.yml`.
For see the reference of Fxp Mailer Configuration, execute command:

```bash
$ php app/console config:dump-reference FxpMailerBundle
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Fxp MailerBundle, you are ready to learn about usages of the bundle.
