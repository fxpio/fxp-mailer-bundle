Getting Started With Sonatra MailerBundle
=========================================

## Prerequisites

This version of the bundle requires Symfony 2.4+ and Sonatra BootstrapBundle.

## Installation

Installation is a quick, 2 step process:

1. Download the bundle using composer
2. Enable the bundle
3. Configure the bundle (optional)

### Step 1: Download the bundle using composer

Add Sonatra MailerBundle in your composer.json:

```js
{
    "require": {
        "sonatra/mailer-bundle": "~1.0"
    }
}
```

Or tell composer to download the bundle by running the command:

```bash
$ php composer.phar update sonatra/web-interface-bundle
```

Composer will install the bundle to your project's `vendor/sonatra` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sonatra\Bundle\MailerBundle\SonatraMailerBundle(),
    );
}
```

### Step 3: Configure the bundle (optional)

You can override the default configuration adding `sonatra_web_interface` tree in `app/config/config.yml`.
For see the reference of Sonatra Mailer Configuration, execute command:

```bash
$ php app/console config:dump-reference SonatraMailerBundle
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Sonatra MailerBundle, you are ready to learn about usages of the bundle.
