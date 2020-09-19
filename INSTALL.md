Installing Fusion Search Drupal
===============================

The fusion-search-drupal module manages its dependencies and class loader via
composer. So if you simply downloaded this module from drupal.org you have to
delete it and install it again via composer!

Simply change into Drupal directory and use composer to install fusion-search-drupal:

```
cd $DRUPAL
composer require lucidworks/fusion-search-drupal
```

**Warning!** Unless https://www.drupal.org/project/drupal/issues/2876675 is
committed to Drupal Core and released you need to modify the composer command:

```
cd $DRUPAL
composer require symfony/event-dispatcher:"4.3.4 as 3.4.99" drupal/search_api_solr
```
