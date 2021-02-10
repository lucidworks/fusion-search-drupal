Fusion Search Drupal
--------------------

Fusion Search Drupal module provides a Lucidworks Fusion powered search experience for a Drupal site.

This module uses the following modules as dependencies to achieve this.

1. The source code for the indexing module is present at [lucidworks/drupal-connector-module](https://github.com/lucidworks/drupal-connector-module).
2. The source code for the search module is forked from the search api solr module to add support for Fusion as a backend. The changes reside in:  
  a. [lucidworks/drupal_search_api](https://github.com/lucidworks/drupal_search_api/) - Drupal Search API is a fork of the [Search API](https://www.drupal.org/project/search_api) project. The Search API module provides a framework for easily creating searches on any entity known to Drupal, using any kind of search engine.  
  b. [lucidworks/fusion-search-drupal](https://github.com/lucidworks/fusion-search-drupal) aka this repository - Fusion Search Drupal provides an implementation of the Search API module which uses Lucidworks Fusion search server for searching.  
  c. [lucidworks/fusion-search-solarium](https://github.com/lucidworks/fusion-search-solarium) - This module adds support for a Fusion backend within Solarium, a PHP client library for Solr.


Installation
------------

The `fusion-search-drupal` module manages its dependencies and class loader via
composer. So if you simply downloaded this module from drupal.org you have to
delete it and install it again via composer!

Simply change into the Drupal directory and use composer to install `fusion-search-drupal`:

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




Supported optional features
---------------------------

All Search API datatypes are supported by using appropriate Solr datatypes for
indexing them.

The "direct" parse mode for queries will result in the keys being directly used
as the query to Solr using the
[Standard Parse Mode](https://lucene.apache.org/solr/guide/7_2/the-standard-query-parser.html).

Regarding third-party features, the following are supported:

- autocomplete
  - Introduced by module: search_api_autocomplete
  - Lets you add autocompletion capabilities to search forms on the site.
- facets
  - Introduced by module: facet
  - Allows you to create facetted searches for dynamically filtering search
    results.
- more like this
  - Introduced by module: search_api
  - Lets you display items that are similar to a given one. Use, e.g., to create
    a "More like this" block for node pages build with Views.
- multisite
  - Introduced by module: search_api_solr
- spellcheck
  - Introduced by module: search_api_solr
  - Views integration provided by search_api_spellcheck
- attachments
  - Introduced by module: search_api_attachments
- location
  - Introduced by module: search_api_location

If you feel some service option is missing, or have other ideas for improving
this implementation, please file a feature request in the project's issue queue,
at https://drupal.org/project/issues/search_api_solr.

Processors
----------

Please consider that, since Solr handles tokenizing, stemming and other
preprocessing tasks, activating any preprocessors in a search index' settings is
usually not needed or even cumbersome. If you are adding an index to a Solr
server you should therefore then disable all processors which handle such
classic preprocessing tasks.

If you create a new index, such processors won't be offered anymore since
8.x-2.0.

But the remaining processors are useful and should be activated. For example the
HTML filter or the Highlighting processor.

By default the Highlighting processor provided by Search API uses PHP to create
highlighted snippets or an excerpt based on the entities loaded from the
database. Solr itself can do that much better, especially for different
languages. If you check `Retrieve result data from Solr` and `Highlight
retrieved data` on the index edit page, the Highlighting processor will use
this data directly and bypass it's own logic. To do the highlighting, Solr will
use the configuration of the Highlighting processor.


Support
-------

This is a community supported extension. 

Developers
----------

Whenever you need to enhance the functionality you should do it using the API
instead of extending the SearchApiSolrBackend class!

To customize connection-specific things you should provide your own
implementation of the \Drupal\search_api_solr\SolrConnectorInterface.

A lot of customization can be achieved using YAML files and drupal's
configuration management.

We leverage the [solarium library](http://www.solarium-project.org/). You can
also interact with solarium's API using our hooks and callbacks or via event
listeners.
This way you can for example add any solr specific parameter to a query you need.

But if you create Search API Queries by yourself in code there's an easier way.
You can simply set the required parameter as option prefixed by 'solr_param_'.

So these two lines are "similar":
```
$search_api_query->setOption('solr_param_mm', '75%');

$solarium_query->setParam('mm', '75%');
```
