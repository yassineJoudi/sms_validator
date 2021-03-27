<?php

namespace Drupal\sms_validator\Form;

use Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

class ConfirmForm extends FormBase {

    public function getFormId(): string {
        return "confirm_form__sms_validator";
    }

    public function buildForm(array $form, FormStateInterface $form_state): array {
        $form['code_number'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Code Received'),
            '#required' => TRUE,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $request = \Drupal::request()->get('query');
        $code = base64_decode($request["code"]);
        $uid = \Drupal::request()->get('query')["userId"];
        $expired = \Drupal::service('sms_validator.sender')->checkIfCodeActive($request['time']);
        if (!empty($uid) && $expired && $code == $form_state->getValue("code_number")) {
            $user = User::load($uid);
            $user->set('status', 1)->save();
            user_login_finalize($user);
            $form_state->setRedirect('<front>');
        } else {
            $message = t('Process not completed, code vérification incorrect.');
            \Drupal::messenger()->addMessage($message, 'warning');
            $form_state->setRedirectUrl(Url::fromRoute("sms_validator.confirm", ["query" => ["code" => base64_encode($code), "userId" => $uid]]));
        }

    }

}