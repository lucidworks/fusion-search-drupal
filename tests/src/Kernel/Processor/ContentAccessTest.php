<?php

namespace Drupal\Tests\search_api_fusion\Kernel\Processor;

use Drupal\Tests\search_api\Kernel\Processor\ContentAccessTest as SearchApiContentAccessTest;

/**
 * Tests the "Content access" processor.
 *
 * @group search_api_fusion
 *
 * @see \Drupal\search_api\Plugin\search_api\processor\ContentAccess
 */
class ContentAccessTest extends SearchApiContentAccessTest {

  use SolrBackendTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'devel',
    'search_api_fusion',
    'search_api_fusion_devel',
    'search_api_fusion_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp($processor = NULL) {
    parent::setUp();
    $this->enableSolrServer();
  }

}
