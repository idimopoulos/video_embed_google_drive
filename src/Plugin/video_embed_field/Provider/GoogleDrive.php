<?php

namespace Drupal\video_embed_google_drive\Plugin\video_embed_field\Provider;

use Drupal\video_embed_field\ProviderPluginBase;

/**
 * A Google Drive provider plugin for video embed field.
 *
 * @VideoEmbedProvider(
 *   id = "google_drive",
 *   title = @Translation("Google Drive")
 * )
 */
class GoogleDrive extends ProviderPluginBase {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    $iframe = [
      '#type' => 'video_embed_iframe',
      '#provider' => 'google_drive',
      '#url' => sprintf('https://drive.google.com/file/d/%s/preview', $this->getVideoId()),
      '#query' => [],
      '#attributes' => [
        'width' => $width,
        'height' => $height,
        'frameborder' => '0',
        'allowfullscreen' => 'allowfullscreen',
      ],
    ];
    // TODO: is this supported by google drive?
//    if ($time_index = $this->getTimeIndex()) {
//      $iframe['#fragment'] = sprintf('t=%s', $time_index);
//    }
    return $iframe;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    $url = 'https://drive.google.com/thumbnail?authuser=0&sz=w320&id=%s';
    return sprintf($url, $this->getVideoId());
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    if (strpos($input, 'drive.google.com') !== false) {
      // e.g. https://drive.google.com/open?id=0B8e8g71GefzHQmdmMnBCZDZsUkE, not working for other url structures atm.
      $parts = parse_url($input);
      parse_str($parts['query'], $query);
      return isset($query['id']) ? $query['id'] : false;
    }
    return false;
  }

}
