<?php

namespace Drupal\search_api_fusion;

/**
 * Provides an interface defining a SolrCache entity.
 */
interface SolrCacheInterface extends SolrConfigInterface {

  /**
   * Gets the environments targeted by this Solr Cache.
   *
   * @return string[]
   *   Environments.
   */
  public function getEnvironments();

  /**
   * Gets the Solr Cache definition as nested associative array.
   *
   * @return array
   *   The Solr Cache definition as nested associative array.
   */
  public function getCache();

}
