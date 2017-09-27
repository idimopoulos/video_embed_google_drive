<?php

namespace Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider;

use Drupal\video_embed_google_drive\GoogleDrivePluginBase;

/**
 * A google drive form provider.
 *
 * @VideoEmbedProvider(
 *   id = "google_drive_form",
 *   title = @Translation("Google form"),
 *   document_type = "forms"
 * )
 */
class GoogleDriveForm extends GoogleDrivePluginBase {

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    preg_match('#^(?:(?:https:)?//)?docs\.google\.com/forms/d/(e/)?(?<id>[^/]+).*?#i', $input, $matches);
    return isset($matches['id']) ? $matches['id'] : FALSE;
  }

}
