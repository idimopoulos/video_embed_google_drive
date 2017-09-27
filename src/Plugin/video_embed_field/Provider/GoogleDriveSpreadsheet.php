<?php

namespace Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider;

use Drupal\video_embed_google_drive\GoogleDrivePluginBase;

/**
 * A google drive spreadsheet provider.
 *
 * @VideoEmbedProvider(
 *   id = "google_drive_spreadsheet",
 *   title = @Translation("Google spreadsheet"),
 *   document_type = "spreadsheets"
 * )
 */
class GoogleDriveSpreadsheet extends GoogleDrivePluginBase {

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    preg_match('#^(?:(?:https:)?//)?docs\.google\.com/spreadsheets/d/(e/)?(?<id>[^/]+).*?#i', $input, $matches);
    return isset($matches['id']) ? $matches['id'] : FALSE;
  }

}
