## Pre-requisites

Have a Drupal site and a Lucidworks Fusion subscription.

This is a step-by-step guide showing the installation and setup steps to get a Fusion powered search running on your Drupal site.

## Installation Steps

1. Go to the Drupal site directory and apply the following steps:

```sh
composer config --global --auth http-basic.repo.packagist.com siddharthlatest a1ce37b3079d2959cd78b2f4440d374d776b1c7f48887a33d0b58c9c488f
composer config repositories.private-packagist composer https://repo.packagist.com/lucidworks/
composer config repositories.packagist.org false
```

The auth token is specific to a user.

// TODO - Once lucidworks/fusion-search-drupal package is public, the above commands won't be required.

1. Install the package.

```sh
composer require lucidworks/fusion-search-drupal
```

![](http://recordit.co/ir3r7F3KqH.gif)

2. Add the following modules from the Extend panel:

- Fusion Connector
- Search API
- Search API Solr
- Search API Solr Admin

And remove the default `Search` module from the Extend panel.

![](http://recordit.co/TC9ye3wCn9.gif)
   
3. Configure the Search API module in the admin page to connect to the Fusion server.

![](http://recordit.co/nY0tD0OL7I.gif)

4. Add an index within the server and select the fields to use for the search view.

![](http://recordit.co/A5qkqDgFm5.gif)
   
5. Create the Search view from the Structure panel and test the search UI view.

![](http://recordit.co/O6yl4wKcRZ.gif)

![](http://recordit.co/qhr5cSBW7l.gif)

6. Install the facets module and test it. 

```sh
composer require drupal/facets
```

- Enable the Facets module from the Extend panel

- Set the Facets from the Facets section of Configuration panel
  
![](http://recordit.co/qtRNecWNV3.gif)


- Create a Facet block and place it in the sidebar

![](http://recordit.co/hlFHI2K5yF.gif)