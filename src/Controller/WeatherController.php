<?php
namespace Drupal\weather\Controller;

use Drupal\Core\Controller\ControllerBase;

class WeatherController extends ControllerBase{
  public function content()
  {
    $config = \Drupal::config('weather.config');
    $appid = $config->get('appid');
    if($appid=='')
    {
      return array(
        '#theme' => 'markup',
        '#markup' => $this->t("API key isn't setted. Set it in admin/config/system/weather")
      );
    }
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
