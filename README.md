Sonatra Mailer Bundle
=====================

[![Latest Version](https://img.shields.io/packagist/v/sonatra/mailer-bundle.svg)](https://packagist.org/packages/sonatra/mailer-bundle)
[![Build Status](https://img.shields.io/travis/sonatra/SonatraMailerBundle/master.svg)](https://travis-ci.org/sonatra/SonatraMailerBundle)
[![Coverage Status](https://img.shields.io/coveralls/sonatra/SonatraMailerBundle/master.svg)](https://coveralls.io/r/sonatra/SonatraMailerBundle?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/sonatra/SonatraMailerBundle/master.svg)](https://scrutinizer-ci.com/g/sonatra/SonatraMailerBundle?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8e1937d0-1e2d-464f-88d8-076c3a6b8ec5.svg)](https://insight.sensiolabs.com/projects/8e1937d0-1e2d-464f-88d8-076c3a6b8ec5)

The Sonatra MailerBundle is a manager for render and send an mail template with different
transport (email, mail, fax, ...).

Features include:

- Stored the templates in database with doctrine
- Stored the templates in filesystem with:
  - twig format
  - yaml format
- Stored the templates in app config
- Compatible with the localization
- Allow to use the Symfony translator with the translation domain
- Use twig for rendered the mail and layout templates
- Send your email with Switmailer
- Sign your email with DKIM
- Add your event listeners for:
  - template pre render
  - template post render
  - transport pre send
  - transport post send
- Register your filters for:
  - template mail
  - transport
- Build your custom loaders for:
  - template mails
  - template layouts
- Build your custom transports
- Build your custom transport signers
- Twig function for use this templater with existing templates defined in twig files of already existing systems

Documentation
-------------

The bulk of the documentation is stored in the `Resources/doc/index.md`
file in this bundle:

[Read the Documentation](Resources/doc/index.md)

Installation
------------

All the installation instructions are located in [documentation](Resources/doc/index.md).

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

[Resources/meta/LICENSE](Resources/meta/LICENSE)

About
-----

Sonatra MailerBundle is a [sonatra](https://github.com/sonatra) initiative.
See also the list of [contributors](https://github.com/sonatra/SonatraMailerBundle/contributors).

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/sonatra/SonatraMailerBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
