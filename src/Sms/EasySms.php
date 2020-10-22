<?php

namespace Sguy\EasySms\Sms;

use DateTime;
use Sguy\EasySms\SmsOnlineGh\MessageTypes;
use Sguy\EasySms\SmsOnlineGh\ResponseCodes;
use Sguy\EasySms\SmsOnlineGh\ZenophSMSGH;
use Sguy\EasySms\SmsOnlineGh\ZenophSMSGHException;

class EasySms
{
    private $message;
    private $destinations;
    private $sender_id;
    private $status;
    private $datetime;

    public function __construct()
    {
     $this->sender_id=null;
     $this->datetime=null;
    }
    public function setMessage(string $message)
    {
       $this->message = $message;
    }

    public function setDestinations(array $destinations)
    {
       $this->destinations = $destinations;
    }
    public function setSenderId(string $id)
    {
        if(strlen($id)>11){
            $id = substr($id,0,10);
        }
        $this->sender_id = $id;
    }
    public function schedule(DateTime $date)
    {
        $this->datetime = $date;
    }

    public function sendMessage()
    {
        try{
            // Initialise the object for sending the message and set parameters.
            $zs = new ZenophSMSGH();
            $login = config('easysms.account_login');
            $password = config('easysms.account_password');
            $zs->setUser($login);
            $zs->setPassword($password);

            // set message parameters
            $id = $this->sender_id??config('easysms.sender_id');
            $zs->setSenderId($id);
            $zs->setMessage($this->message);
            $zs->setMessageType(MessageTypes::TEXT); // default is TEXT if you do not set it yourself.

            foreach($this->destinations as $destination){
                $zs->addDestination($destination);
            }

            /*
             * to send personalised message, you will need to set the message once. However,
             * the message should contain variables in parts of the message where values will
             * be different for each destination.
             *
             * To prevent the PHP interpreter from parsing the variables defined in the message,
             * the message can be enclosed in single quotes.
             */
            if(!is_null($this->datetime)){
            $zs->schedule($this->datetime);
            }
            $response = $zs->sendMessage();
            $this->status = "success";
        }

        // when sending requests to the server, ZenophSMSGH_Exception may be
        // thrown if error occurs or the server rejects the request.
        catch (ZenophSMSGHException $ex){
            $errmessage = $ex->getMessage();
            $responsecode = $ex->getResponseCode();

            $this->status=$errmessage;

            // the response code indicates the specific cause of the error
            // you will need to compare with the elements in ZenophSMSGH_RESPONSECODE class.
            // for example,
            switch ($responsecode){
                case ResponseCodes::ERR_AUTH:
                    $this->status = "invalid credentials";
                    break;

                case ResponseCodes::ERR_INSUFF_CREDIT:
                    $this->status = "insufficient balance";
                    break;

                // you can check for the other causes.
            }
        }

        // Exceptions caught here are mostly not the cause of
        // sending request to the SMS server.
        catch (\Exception $ex) {
            $errmessage = $ex->getMessage();

            // if the error needs to be echoed.
            $this->status = "failed";
        }
        finally{
            return $this->status;
        }


    }
    public function sendMessageTo(string $phone)
    {
        try{
            // Initialise the object for sending the message and set parameters.
            $zs = new ZenophSMSGH();
            $login = config('easysms.account_login');
            $password = config('easysms.account_password');
            $zs->setUser($login);
            $zs->setPassword($password);

            // set message parameters
            $id = $this->sender_id??config('easysms.sender_id');
            $zs->setSenderId($id);
            $zs->setMessage($this->message);
            $zs->setMessageType(MessageTypes::TEXT); // default is TEXT if you do not set it yourself.
            $zs->addDestination($phone);

            /*
             * to send personalised message, you will need to set the message once. However,
             * the message should contain variables in parts of the message where values will
             * be different for each destination.
             *
             * To prevent the PHP interpreter from parsing the variables defined in the message,
             * the message can be enclosed in single quotes.
             */
            if(!is_null($this->datetime)){
            $zs->schedule($this->datetime);
            }
            $response = $zs->sendMessage();
            $this->status = "success";
        }

        // when sending requests to the server, ZenophSMSGH_Exception may be
        // thrown if error occurs or the server rejects the request.
        catch (ZenophSMSGHException $ex){
            $errmessage = $ex->getMessage();
            $responsecode = $ex->getResponseCode();

            $this->status=$errmessage;

            // the response code indicates the specific cause of the error
            // you will need to compare with the elements in ZenophSMSGH_RESPONSECODE class.
            // for example,
            switch ($responsecode){
                case ResponseCodes::ERR_AUTH:
                    $this->status = "invalid credentials";
                    break;

                case ResponseCodes::ERR_INSUFF_CREDIT:
                    $this->status = "insufficient balance";
                    break;

                // you can check for the other causes.
            }
        }

        // Exceptions caught here are mostly not the cause of
        // sending request to the SMS server.
        catch (\Exception $ex) {
            $errmessage = $ex->getMessage();

            // if the error needs to be echoed.
            $this->status = "failed";
        }
        finally{
            return $this->status;
        }


    }
    public function sendMessageToAll(array $phones)
    {
        try{
            // Initialise the object for sending the message and set parameters.
            $zs = new ZenophSMSGH();
            $login = config('easysms.account_login');
            $password = config('easysms.account_password');
            $zs->setUser($login);
            $zs->setPassword($password);

            // set message parameters
            $id = $this->sender_id??config('easysms.sender_id');
            $zs->setSenderId($id);
            $zs->setMessage($this->message);
            $zs->setMessageType(MessageTypes::TEXT); // default is TEXT if you do not set it yourself.
            foreach($phones as $phone){
            $zs->addDestination($phone);
            }

            /*
             * to send personalised message, you will need to set the message once. However,
             * the message should contain variables in parts of the message where values will
             * be different for each destination.
             *
             * To prevent the PHP interpreter from parsing the variables defined in the message,
             * the message can be enclosed in single quotes.
             */
            if(!is_null($this->datetime)){
            $zs->schedule($this->datetime);
            }
            $response = $zs->sendMessage();
            $this->status = "success";
        }

        // when sending requests to the server, ZenophSMSGH_Exception may be
        // thrown if error occurs or the server rejects the request.
        catch (ZenophSMSGHException $ex){
            $errmessage = $ex->getMessage();
            $responsecode = $ex->getResponseCode();

            $this->status=$errmessage;

            // the response code indicates the specific cause of the error
            // you will need to compare with the elements in ZenophSMSGH_RESPONSECODE class.
            // for example,
            switch ($responsecode){
                case ResponseCodes::ERR_AUTH:
                    $this->status = "invalid credentials";
                    break;

                case ResponseCodes::ERR_INSUFF_CREDIT:
                    $this->status = "insufficient balance";
                    break;

                // you can check for the other causes.
            }
        }

        // Exceptions caught here are mostly not the cause of
        // sending request to the SMS server.
        catch (\Exception $ex) {
            $errmessage = $ex->getMessage();

            // if the error needs to be echoed.
            $this->status = "failed";
        }
        finally{
            return $this->status;
        }


    }

    public function getSmsAccountBalance():int
    {
        $zs = new ZenophSMSGH();
        $login = config('easysms.account_login');
        $password = config('easysms.account_password');
        $zs->setUser($login);
        $zs->setPassword($password);
        $balance = $zs->getBalance();
        return $balance;
    }

    public function getChargePerSms():int
    {
        $zs = new ZenophSMSGH();
        $login = config('easysms.account_login');
        $password = config('easysms.account_password');
        $zs->setUser($login);
        $zs->setPassword($password);
        $zs->setMessage($this->message);
        $count = $zs->getSMSCount();
        return $count;
    }
}
