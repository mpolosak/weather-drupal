<?php

namespace Drupal\weather\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
    // TODO: Connect to OpenWeatherMap to check if API key is correct
    if (!strlen($form_state->getValue('appid'))) {
      $form_state->setErrorByName('appid', $this->t('Please enter API key'));
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
