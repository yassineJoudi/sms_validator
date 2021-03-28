SMS Validator

CONTENTS OF THIS FILE

Introduction
Requirements
Installation
Configuration
Maintainers


INTRODUCTION:

SMS validator is a drupal module gives the possibility to send sms code confirmation to user phone.

REQUIREMENTS:

This module require sms_twilio and smsframework.

INSTALLATION:

install the sms_validator, sms_twilio and smsframework as you would normally install a contributed Drupal module.
Visit https://www.drupal.org/node/1897420 for further information.

CONFIGURATION:

- Go to admin/config/smsframework/gateways to setup the twilio gateway with system name "smsvalidator".
- GO to admin/config/smsframework/sms-validator to setup sms_validator (time expiration, text message).
- in your custom submit add:

    $data = \Drupal::service('sms_validator.sender')->codeGenerate()
    
 to generate new code, and also add these lines to send the code at the phone number and redirect to a confirmation page:

    \Drupal::service('sms_validator.sender')->messagePrepare(PHONE_NUMBER, $data['code']);
    $form_state->setRedirectUrl(Url::fromRoute("sms_validator.confirm", ["query" => ["code" => base64_encode($data['code']), "time" => base64_encode($data['time']), "userId" => \Drupal::currentUser()->id()]]));
        
MAINTAINERS:

YASSINE JOUDI - Creator and Maintainer -
https://www.drupal.org/u/yassine-joudi
