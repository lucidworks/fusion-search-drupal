## How to build a Drupal search UI with Lucidworks Managed Search

[search_api_solr](https://www.drupal.org/project/search_api_solr) is a Drupal module that allows building search UIs with Solr as a backend. In this post, we will cover how to use [Lucidworks managed-search-drupal](https://packagist.com/orgs/lucidworks/packages/2498311) - a fork of `search_api_solr` module to build search UIs using Lucidworks Managed Search.

Pre-requisite: You will first need to install a Drupal site. You can follow the instructions mentioned over [here](https://www.drupal.org/docs/8/install) to achieve that.

Assuming you have PHP installed with a web server, we recommend setting up the drupal site using composer.

```composer create-project drupal/recommended-project drupal-site-search```

This will create a project in 'drupal-site-search' and automatically executes composer install to download the latest stable version of Drupal 8 and all its dependencies.

You can read more about the installation steps using composer over [here](https://www.drupal.org/docs/develop/using-composer/using-composer-to-install-drupal-and-manage-dependencies#download-core).

After following the standard install process, you should see the site looking something like below:

![](https://i.imgur.com/uHsLlsK.png)

### Step 1: Install Lucidworks managed-search-drupal module

Prior to installing the package, do the following:

```
export COMPOSER_MEMORY_LIMIT=-1
composer require symfony/event-dispatcher:"4.3.4 as 3.4.35"
```

A brief explanation of the above steps:

1. The default memory limit imposed by composer may be too low as we install additional dependencies. The first command unsets memory limit constraint.
2. The latest version of search_api_solr recommends this particular step. More info on it over [here](https://git.drupalcode.org/project/search_api_solr#installation).
3. This reads the composer.json file from the
  current directory, processes it, and updates, removes or installs all the dependencies.

We will now install the `managed-search-drupal` project.

```composer require lucidworks/managed-search-drupal```

Once you're done with the above, you can also run:

```composer update drupal/core --with-dependencies```

This will process your site's composer.json file and update/remove/install the relevant dependencies.

Next, we're going to install the following three modules by going to the `Extend` menu:

- Search API
- Search API Solr
- Search API Solr Search Defaults
  
![](https://i.imgur.com/wrNqZWm.png)

The Search API Solr Search Defaults module provides a default setup of Search API which would save us time to create one from scratch.

In addition to this, we should uninstall the default `Search` module of Drupal since we're going to configure one that's using Solr.

Here's a GIF showing the above process.

![](http://g.recordit.co/OKJf5JuPBj.gif)


### Step 2: Configure the Search API Module

Go to the `Configuration` tab and select `Search API` menu within it.

![](https://i.imgur.com/EflNbaM.png)

Once here, select the Server option. To begin with, the STATUS might read as `Unavailable`. 

![](https://imgur.com/rHZ4Jyc.png)

Click on the Edit Operation for the server.

![](https://imgur.com/u0J8Xcz.png)

Select a server name and choose `Solr Cloud` as the option.

![](https://imgur.com/GOn8g6L.png)

Next, enter the config options for:

1. HTTP protocol - choose HTTPS
2. Solr node - use your host name, e.g. `pg01.us-west1.cloud.lucidworks.com`
3. Pick port as 443
4. Choose Solr Path. For LMS, this is `/:customer_id/:cluster_id`
5. Set your OAuth2 Client ID and Client Secret values
6. Set your default Solr collection to a collection that exists in your cluster. Here, I've chosen `drupal-site-search`.

![](https://imgur.com/27CM5NS.png)

Optionally, you can set the HTTP Method for search as `GET` for better caching.


Here's a video showing the Search API configuration process:

[![](https://imgur.com/u0J8Xcz.png)](https://youtu.be/F3BJPhEYWAs)


### Step 3: Setting the Schema via configset

Although, we have configured our Solr backend, you're likely to see the following error screen:

![](https://imgur.com/nccBILR.png)

You can download the configset and upload it to LucidWorks Managed Search. However, Lucidworks Managed Search doesn't allow uploading of untrusted `<lib>` paths. Prior to uploading this configset, you will need to edit `solrconfig.xml` and remove all the lib references.

Alternatively, you can use this [configset directly](drupal-site-search-configset.zip) and upload it instead.


```
curl --location --request POST 'https://pg01.us-west1.cloud.lucidworks.com/php-test/php-test/solr/admin/configs?action=UPLOAD&name=drupal-new-search' \
--header 'Content-Type: application/octet-stream' \
--header "Authorization: Bearer $TOKEN" \
--data-binary '@drupal-new-search-configset.zip'
```

The above API call uses `$TOKEN` which requires a valid Bearer token. You can get one via the following REST API call:


```sh
curl -X POST \
  https://cloud.lucidworks.com/oauth2/default/$customer_id/v1/token \
  -H 'Accept-Encoding: gzip, deflate' \
  -H 'accept: application/json' -H 'Cache-control: no-cache' \
  -H 'authorization: Basic base64($oauth2_client_id:$oauth2_client_secret)' \
  -H 'content-type: application/x-www-form-urlencoded' \
  -d 'grant_type=client_credentials&scope=com.lucidworks.cloud.search.solr.customer'

Response Format:

{
    "token_type":"Bearer",
    "access_token":"$TOKEN",
    "scope":"com.lucidworks.cloud.search.solr.customer",
    "expires_in":3599
}
```

Once you've uploaded the config set, you can visit the Solr Admin UI to create a collection with the config set applied.

![](https://imgur.com/nj2zePZ.png)

`Note`: If you had already set a collection in the drupal site, delete the collection prior to re-creating it with the config set.

Once you have done this step, your drupal site should show that everything is successfully configured.

![](https://imgur.com/SboceHO.png)

### Step 4: Generating Site Data And Indexing It

First, we will add some data. Install the [`devel`](https://www.drupal.org/project/devel) module which provides some tools to generate site data.

```
composer require drupal/devel
```

Next, go to the `Extend` tab and add the Devel Generate module.

![](https://imgur.com/iYBcvf5.png)

Next, go to the `Configuration` tab and select `Generate Content` menu.

![](https://imgur.com/B0AcZuu.png)

Select some options and once you save, your site will have dummy content generated.

![](https://imgur.com/BEzDDPD.png)

![](https://imgur.com/nvMmNJH.png)

Since our Search API module is already configured, this data would've been indexed in our Solr backend already.

We can confirm this by going to the Configuration > Search API > Solr Index menu.

![](https://imgur.com/LJdk9jU.png)

We can also confirm this by making a search query from Solr Admin UI.

![](https://imgur.com/gKa3El3.png)

### Step 5: Search Content

Our search is already configured, thanks to the `Search API Solr Search Defaults` module.

We can confirm this by going to Structure > Views tab.

![](https://imgur.com/fJJJ4Xg.png)

Go to the Solr Search Content view.

![](https://imgur.com/vSnSG31.png)

Here, you can edit the page path from the default `/solr-search/content/` to something more obvious like `/search`.

![](https://imgur.com/3HpXt2V.png)

Great! Now, let's visit the search page. Our search is configured to work with Lucidworks Managed Search.

![](https://imgur.com/he1d6Hi.png)