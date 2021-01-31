<?php

namespace Drupal\search_api_fusion\Plugin\search_api_autocomplete\suggester;

use Drupal\search_api\IndexInterface;
use Drupal\search_api\LoggerTrait;
use Drupal\search_api_fusion\SolrAutocompleteInterface;

/**
 * Provides a helper method for loading the search backend.
 */
trait BackendTrait {

  use LoggerTrait;

  /**
   * Retrieves the backend for the given index, if it supports autocomplete.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   The search index.
   *
   * @return \Drupal\search_api_fusion\SolrAutocompleteInterface|null
   *   The backend plugin of the index's server, if it exists and supports
   *   autocomplete; NULL otherwise.
   */
  protected static function getBackend(IndexInterface $index) {
    try {
      if (!$index->hasValidServer()) {
        return NULL;
      }
      $server = $index->getServerInstance();
      $backend = $server->getBackend();
      if ($backend instanceof SolrAutocompleteInterface && $server->supportsFeature('search_api_autocomplete')) {
        return $backend;
      }
    }
    catch (\Exception $e) {
      $this->logException($e);
    }
    return NULL;
  }

}
