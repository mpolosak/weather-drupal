<?php

namespace Drupal\weather\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Exception\ClientException;

class WeatherForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('weather.config');
    $appid = $config->get('appid');
    $form['appid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your API key:'),
      '#default_value' => $appid,
      '#required' => true
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $appid = $form_state->getValue('appid');
    if (!strlen($appid)) {
      $form_state->setErrorByName('appid', $this->t('Please enter API key'));
    }
    $client = \Drupal::httpClient();
    try{
      $response=$client->request('GET','api.openweathermap.org/data/2.5/weather?q=Warsaw&units=metric&appid='.$appid);
    }
    catch(ClientException $e)
    {
      $form_state->setErrorByName('appid', $this->t('Incorrect API key'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configfactory()->getEditable('weather.config');
    $config
      ->set('appid', $form_state->getValue('appid'))
      ->save();
  }

}
