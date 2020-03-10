<?php
namespace Drupal\weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'weather' block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather block"),
 * )
 */
class WeatherBlock extends BlockBase implements BlockPluginInterface {
  /**
  * {@inheritdoc}
  */
  public function build() {
    $config = \Drupal::config('weather.config');
    $appid = $config->get('appid');
    $blockconfig = $this->getConfiguration();
    $city = $blockconfig['city'];
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
      $response=$client->request('GET','api.openweathermap.org/data/2.5/weather?q='.$city.'&units=metric&lang='.$language.'&appid='.$appid);
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
  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge()
  {
    return 15;
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['city']=[
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => isset($config['city'])?$config['city']:'',
      '#required' => true,
    ];

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->setConfigurationValue('city', $values['city']);
  }
}
