<?php
namespace Drupal\weather\Controller;

class WeatherController{
  public function content()
  {
    $client = \Drupal::httpClient();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    try{
      // place your appid in place of {appid}
      // TODO: set appid in settings
      $response=$client->request('GET','api.openweathermap.org/data/2.5/weather?q=Warsaw,pl&units=metric&lang='.$language.'&appid={appid}');
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
