<?php

namespace Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider;

use Drupal\video_embed_google_drive\GoogleDrivePluginBase;

/**
 * A google drive presentation provider.
 *
 * @VideoEmbedProvider(
 *   id = "google_drive_presentation",
 *   title = @Translation("Google presentation"),
 *   document_type = "presentation"
 * )
 */
class GoogleDrivePresentation extends GoogleDrivePluginBase {

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    preg_match('#^(?:(?:https:)?//)?docs\.google\.com/presentation/d/(e/)?(?<id>[^/]+).*?#i', $input, $matches);
    return isset($matches['id']) ? $matches['id'] : FALSE;
  }

}
