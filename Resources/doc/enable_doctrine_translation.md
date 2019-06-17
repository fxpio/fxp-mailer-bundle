Enable the Doctrine translation
===============================

The repository trait of Fxp Mailer is natively compatible with the [Doctrine Extensions Translatable](https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/translatable.md),
and you so can follow the [documentation](https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/translatable.md#translatable-entity-example)
directly to enable the localization for the `TemplateMessage` entity.

However, it is recommended to create a dedicated entity to store translations of template messages,
you can so follow the [documentation section](https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/translatable.md#translation-entity)
to create the entity `TemplateMessageTranslation`.

Also, make sure to make and run a migration for the new entities:

```
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```
