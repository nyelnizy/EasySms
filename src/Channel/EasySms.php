<?php

namespace Sguy\EasySms\Channel;

use Illuminate\Notifications\Notification;
use Sguy\EasySms\Sms\EasySms as Sms;

class EasySms
{

  public function send($notifiable, Notification $notification)
  {
    $data = $notification->toEasySms($notifiable);

    $message = $data['message'];
    $sms = new Sms();

    $phone = $data['field']? $notifiable->{$data['field']}: $notifiable->phone;
    if(isset($data['sender_id']) && !is_null($data['sender_id'])){
        $sms->setSenderId($data['sender_id']);
    }
    if(isset($data['datetime']) && !is_null($data['datetime'])){
        $sms->schedule($data['datetime']);
    }

    $sms->setDestinations([$phone]);
    $sms->setMessage($message);
    $status = $sms->sendMessage();
    return $status;
  }
}
