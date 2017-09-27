<?php

namespace Drupal\video_embed_google_drive;

use Drupal\video_embed_field\ProviderPluginBase;
use GuzzleHttp\ClientInterface;

/**
 * A base for the google drive provider plugins.
 */
abstract class GoogleDrivePluginBase extends ProviderPluginBase {

  const BASE_URL = 'https://docs.google.com/%s/d/';

  /**
   * GoogleDrivePluginBase constructor.
   */
  public function __construct($configuration, $plugin_id, array $plugin_definition, ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $http_client);
    if (empty($plugin_definition['document_type'])) {
      throw new \Exception('Document type (document_type) annotation parameter is missing.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    $embed_code = [
      '#type' => 'video_embed_iframe',
      '#provider' => $this->getPluginId(),
      '#url' => sprintf($this->getUrlPattern(), $this->getDocumentType(), $this->getVideoId()),
      '#query' => [],
      '#attributes' => [
        'width' => $width,
        'height' => $height,
        'frameborder' => '0',
        'allowfullscreen' => 'allowfullscreen',
      ],
    ];

    return $embed_code;
  }

  /**
   * Checks whether the input is an embedded or a direct url.
   *
   * @return bool
   *   True if the url is embedded, false otherwise.
   */
  public function isEmbeddedUrl() {
    return (bool) preg_match("#^(?:(?:https?:)?//)?docs\.google\.com/{$this->getDocumentType()}/d/e/.*?#i", $this->getInput());
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    return '';
  }

  /**
   * Returns the base url to use for the rendering.
   *
   * @return string
   *   The appropriate url, depending on whether the url is embedded or
   *   direct.
   */
  public function getUrlPattern() {
    return self::BASE_URL . ($this->isEmbeddedUrl() ? 'e/%s/viewform?embedded=true' : '%s');
  }

  /**
   * Returns the type of the document.
   *
   * @return string
   *   The type of the document, e.g. 'document', 'spreadsheet' etc.
   */
  protected function getDocumentType() {
    return $this->getPluginDefinition()['document_type'];
  }

}
