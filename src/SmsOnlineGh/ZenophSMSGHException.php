<?php

namespace Sguy\EasySms\SmsOnlineGh;

use Sguy\EasySms\SmsOnlineGh\ResponseCodes;

    class ZenophSMSGHException extends \Exception {
        private $responsecode = ResponseCodes::ERR_UNKNOWN;
        public function __construct($message, $code){
            $this->responsecode = $code;
            parent::__construct($message);
        }

        public function getResponseCode(){
            return $this->responsecode;
        }
    }
?>
