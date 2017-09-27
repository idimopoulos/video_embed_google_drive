<?php

namespace Drupal\Tests\video_embed_google_drive\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider\GoogleDriveForm;
use GuzzleHttp\ClientInterface;

/**
 * Tests the GoogleDocs provider.
 *
 * @coversDefaultClass \Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider\GoogleDriveForm
 *
 * @group video_embed_field
 */
class GoogleDriveFormTest extends UnitTestCase {

  /**
   * An http client mock.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->httpClient = $this->prophesize(ClientInterface::class)->reveal();
  }

  /**
   * Tests the extraction of ID from input.
   *
   * @param string $url
   *   The input URL.
   * @param string|false $expected_id
   *   The expected ID or FALSE if it cannot be extracted from $url.
   * @param bool $expected_embedded
   *   The expected TRUE or FALSE on whether the url is an embedded url.
   *
   * @covers ::getIdFromInput
   * @covers ::isEmbeddedUrl
   * @dataProvider providerTestGetIdFromInput
   */
  public function testGetIdFromInput($url, $expected_id, $expected_embedded) {
    $plugin_definition = [
      'id' => 'google_drive_form',
      'title' => 'Google drive form',
      'document_type' => 'forms',
    ];
    $document_provider = new GoogleDriveForm(['input' => $url], $plugin_definition['id'], $plugin_definition, $this->httpClient);
    $actual_id = GoogleDriveForm::getIdFromInput($url);
    $this->assertEquals($expected_id, $actual_id);
    $actual_embedded = $document_provider->isEmbeddedUrl();
    $this->assertEquals($expected_embedded, $actual_embedded);
    $url = $document_provider->renderEmbedCode(0, 0, FALSE);
    $this->assertNotEmpty($url['#url']);
  }

  /**
   * Tests that invalid urls throw an exception.
   *
   * @param string $url
   *   The url input.
   *
   * @dataProvider providerTestInvalidUrls
   */
  public function testInvalidUrls($url) {
    $plugin_definition = [
      'id' => 'google_drive_form',
      'title' => 'Google drive form',
      'document_type' => 'forms',
    ];
    $this->setExpectedException('Exception', 'Tried to create a video provider plugin with invalid input.');
    new GoogleDriveForm(['input' => $url], $plugin_definition['id'], $plugin_definition, $this->httpClient);
  }

  /**
   * Provides test cases for ::testGetIdFromInput.
   */
  public function providerTestGetIdFromInput() {
    return [
      'standard direct url' => [
        'https://docs.google.com/forms/d/S4mpl3_id-with-stuff',
        'S4mpl3_id-with-stuff',
        FALSE,
      ],
      'standard embed url' => [
        'https://docs.google.com/forms/d/e/C4rc_4-nz0lA/pub',
        'C4rc_4-nz0lA',
        TRUE,
      ],
      'Follow protocol direct url' => [
        '//docs.google.com/forms/d/aoifsjac484-_ej9f093/pubhtml?widget=true&headers=false',
        'aoifsjac484-_ej9f093',
        FALSE,
      ],
      'No protocol direct url' => [
        'docs.google.com/forms/d/jasdofijf0348c4-___djafoisjf94',
        'jasdofijf0348c4-___djafoisjf94',
        FALSE,
      ],
      'Follow protocol embedded url' => [
        '//docs.google.com/forms/d/e/oifjwe89e4j928fj83--_9df984/pubhtml?widget=true&headers=false',
        'oifjwe89e4j928fj83--_9df984',
        TRUE,
      ],
      'No protocol embedded url' => [
        'docs.google.com/forms/d/e/j93j844_-F_C_F_E_k',
        'j93j844_-F_C_F_E_k',
        TRUE,
      ],
    ];
  }

  /**
   * Provides test cases for ::testInvalidUrls.
   */
  public function providerTestInvalidUrls() {
    return [
      // Non secure direct url.
      ['http://docs.google.com/forms/d/coidjwcwkc-C_C-cwjcw-'],
      // Non secure embedded url.
      ['http://docs.google.com/forms/d/e/fm34im39-3c-34fm3c4'],
      // Missing the /d/.
      ['http://docs.google.com/forms/jf0f34-f_Ffj-wfwf'],
    ];
  }

}
