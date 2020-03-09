<?php
namespace Drupal\weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'weather' block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather block"),
 * )
 */
class WeatherBlock extends BlockBase {
  /**
  * {@inheritdoc}
  */
  public function build() {
    $config = \Drupal::config('weather.config');
    $appid = $config->get('appid');
    // TODO: Check if appid isset. If not show message with info when to set appid 
    $client = \Drupal::httpClient();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    try{
      $response=$client->request('GET','api.openweathermap.org/data/2.5/weather?q=Warsaw,pl&units=metric&lang='.$language.'&appid='.$appid);
      $body = $response->getBody()->getContents();
      $data = json_decode($body);
    }
    catch(auto $e)
    {
      // TODO: write what to do when exeption happens
    }
    return array(
      '#theme' => 'weather',
      '#weather'=>$data
    );
  }
}
