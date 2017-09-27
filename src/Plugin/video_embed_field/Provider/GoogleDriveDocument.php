<?php

namespace Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider;

use Drupal\video_embed_google_drive\GoogleDrivePluginBase;

/**
 * A google drive document provider.
 *
 * @VideoEmbedProvider(
 *   id = "google_drive_document",
 *   title = @Translation("Google document"),
 *   document_type = "document"
 * )
 */
class GoogleDriveDocument extends GoogleDrivePluginBase {

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    preg_match("#^(?:(?:https:)?//)?docs\.google\.com/document/d/(e/)?(?<id>[^/]+).*?#i", $input, $matches);
    return isset($matches['id']) ? $matches['id'] : FALSE;
  }

}
