<?php

namespace Drupal\sms_validator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class Settings extends ConfigFormBase {

    protected function getEditableConfigNames(): array {
        return [
            'sms_validator.admin.settings.key',
        ];
    }

    public function getFormId(): string {
        return 'sms_validator_settings';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('sms_validator.admin.settings.key');
        $form['validator_settings'] = [
            '#type' => 'details',
            '#title' => t('Paramétrages SMS Validator'),
            '#open' => TRUE,
        ];
        $form['validator_settings']['text'] = [
            '#type' => 'textarea',
            '#title' => t('Text'),
            '#format' => 'full_html',
            '#default_value' => !empty($config->get('text')) ? $config->get('text') : t('Your Code Verification Is :'),
        ];
        $form['validator_settings']['time'] = [
            '#type' => 'number',
            '#title' => t('Time Expiration'),
            '#format' => 'full_html',
            '#description' => t('time will be in seconds EX: 3600'),
            '#default_value' => $config->get('time', ''),
        ];

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $data = $form_state->getValues();
        $this->config('sms_validator.admin.settings.key')
                ->set('text', $data['text'])
                ->set('time', $data['time'])
                ->save();
        parent::submitForm($form, $form_state);
    }

}