<?php

namespace Drupal\Tests\search_api_fusion\Kernel\Processor;

use Drupal\Tests\search_api\Kernel\Processor\AddHierarchyTest as SearchApiAddHierarchyTest;

/**
 * Tests the "Hierarchy" processor.
 *
 * @see \Drupal\search_api\Plugin\search_api\processor\AddHierarchy
 *
 * @group search_api_fusion
 *
 * @coversDefaultClass \Drupal\search_api\Plugin\search_api\processor\AddHierarchy
 */
class AddHierarchyTest extends SearchApiAddHierarchyTest {

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

  /**
   *
   */
  public function testRegression3059312() {
    $this->markTestSkipped('This test makes no sense on Solr.');
  }

}
