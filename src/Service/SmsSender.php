<?php

namespace Drupal\sms_validator\Service;

use Drupal\sms\Provider\SmsProviderInterface;
use Drupal\sms\Entity\SmsGateway;
use Drupal\sms\Entity\SmsMessage;
use Drupal\sms\Direction;

/**
 * {@inheritdoc}
 */
class SmsSender {

  protected $smsHandler = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct(SmsProviderInterface $smsHandler) {
    $this->smsHandler = $smsHandler;
  }
  
  public function codeGenerate() {
      $code = [
        'code' => mt_Rand(100000,999999),
        'time' => \Drupal::time()->getCurrentTime(),
      ]; 
      return $code;
  }
  
  public function checkIfCodeActive($time) {
    $expired = FALSE;
    if(empty($time)) {
      return $active;
    }
    $config = \Drupal::config('sms_validator.admin.settings.key');
    $expiration = base64_decode($time) + $config->get('time');
    if($expiration > \Drupal::time()->getCurrentTime()) {
      $expired = TRUE;
    }
    return $expired;
  }

  public function messagePrepare($phone, $code) {
     try {
            $config = \Drupal::config('sms_validator.admin.settings.key');
            $text = $config->get('text') . ' ' . $code;
            $message = SmsMessage::create()
                    ->addRecipient($phone)
                    ->setGateway(SmsGateway::load("smsvalidator"))
                    ->setMessage($text)
                    ->setDirection(Direction::OUTGOING);
            $this->sendMessage($message);
        } catch (\Exception $exception) {
            return [$exception->getMessage()];
        }
  }
  
  /**
   * {@inheritdoc}
   */
  public function sendMessage($message) {
    return $this->smsHandler->send($message);
  }

}