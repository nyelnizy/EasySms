<h3>Description</h3>
<p>EasySms is a laravel package ( a wrapper for https://smsonlinegh.com sms gateway) which makes it very easy to send sms in your laravel applications with little setup.
<br>
This package also comes with a notification channel that integrates very well with laravel's powerful notification system.
<b>You may however note that smsonlinegh works only in Ghana!.</b>
</p>

# This package is available via composer, follow the steps below to complete installation.

<ol style="font-weight: lighter; ">
    <li>Run <b>composer require sguy/easysms</b></li>
    <li >Add <b>Sguy\EasySms\EasySmsServiceProvider::class,</b> to the providers array in app.php config file</li>
    <li>Publish Config File by Running the following command <b>php artisan vendor:publish --tag=easysms</b></li>
    <li>Run <b>composer dump-autoload</b></li>
</ol>

<h3>Usage<h3>
 This package allows you to send sms,schedule sms, check sms balance, check the charge per sms before sending and finally using it as a channel for your notifications
  
<h4>Step One</h4>
To begin using this package, you need to create an account on https://smsonlinegh.com for free.
You may want to buy some credits on your account to send sms (credits are very affordable).
After creating your account and buying some sms credits, navigate to the <b>easysms.php</b> config file and update accordingly.

# easysms.php Config File
The config file has just three keys ..
<ol style="font-weight: lighter;">
  <li><b>account_login :</b> add the username or email of the account you created on smsonlinegh as value for this key.</li>
   <li><b>account_password :</b> add the password for the account you created on smsonlinegh as value for this key.</li>
  <li><b>sender_id :</b> this is basically whom you want to send the message as, it should not be more than 11 chars else value will be trimed and firt 11 used
    eg. sender id can be an organization name,<b> Yara Ghana</b> or a phone number, 0543920099 .</li>
  
</ol>
  
  <h3>Step 2<h3>
  
  # Sending simple message
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sguy\EasySms\Sms\EasySms;

class SmsController extends Controller
{
    public function sendSms(Request $request, EasySms $sms)
    {
        //get information from request or any source...
        $mess = $request->message;
        $phone = $request->phone;

        //sender id is optional, value from easysms.php config file will be used if not set.
        $sms->setSenderId("Yara Ghana");

        //set message content
        $sms->setMessage($mess);
        
        //send message
        $status = $sms->sendMessageTo($phone);

        //status will contain either "success", "invalid credentials", "insufficient balance" or "failed",
        if($status==="success"){
            return redirect()->back()->with('success','Message has been sent successfully');
        }
        //you can do more checks and act accordingly
    }
}

```

  # Scheduling sms
  ```php
  <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sguy\EasySms\Sms\EasySms;

class SmsController extends Controller
{
    public function scheduleSms(Request $request, EasySms $sms)
    {
        //get information from request or any source...
        $mess = $request->message;
        $phone = $request->phone;

        //sender id is optional, value from easysms.php config file will be used if not set.
        $sms->setSenderId("Yara Ghana");

        //Date can be obtained from request or any datasource
        $date = now()->addMinutes(10);
        $sms->schedule($date);

        //set message content
        $sms->setMessage($mess);

        //send message
        //message will be sent in 10 mins time
        $status = $sms->sendMessageTo($phone);

        //status will contain either "success", "invalid credentials", "insufficient balance" or "failed",
        if($status==="success"){
            return redirect()->back()->with('success','Message has been sent successfully');
        }
        //you can do more checks and act accordingly
    }
}

  ```

  # Sending Message to multiple recipients
  ```php
  <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sguy\EasySms\Sms\EasySms;

class SmsController extends Controller
{
    public function sendToMultiple(Request $request, EasySms $sms)
    {
        //get information from request or any source...
        $mess = $request->message;
        $phones = $request->phones;

        //sender id is optional, value from easysms.php config file will be used if not set.
        $sms->setSenderId("Yara Ghana");

        //set destinations/receipients which accepts an array of phone numbers to send message to
        $sms->setDestinations($phones);

        //you can schedule too
        // $date = now()->addMinutes(10);
        // $sms->schedule($date);

        //set message content
        $sms->setMessage($mess);

        //send message
        //Now use sendMessage instead of sendMessage
        $status = $sms->sendMessage();

        //status will contain either "success", "invalid credentials", "insufficient balance" or "failed",
        if($status==="success"){
            return redirect()->back()->with('success','Message has been sent successfully');
        }
        //you can do more checks and act accordingly
    }
}

  ```
  # Checking balance and charge per sms
  ```php
  <?php

namespace App\Http\Controllers;
use Sguy\EasySms\Sms\EasySms;

class SmsController extends Controller
{
   //You can just add a constructor and inject EasySms as opposed to injecting in each function

   public function getBalance(EasySms $sms)
   {
    $balance = $sms->getSmsAccountBalance();
    return $balance;
   }
   public function getChargePerSms(EasySms $sms)
   {
    //Message must be set before the charge can be determined
    $sms->setMessage("Bunch of Text Here");
    $charge = $sms->getChargePerSms();
    return $charge;
   }

}

  ```

  
   # Notification Channel
   
   <h4>Create a TestNotification</h4>
  
  ```php
  <?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \Sguy\EasySms\Channel\EasySms; //Import EasySms from Channel, not Sms

class TestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [EasySms::class]; //Replace the default ['mail'] with [EasySms::class].
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     
    //Replace toArray or toDatabase function with this
    public function toEasySms($notifiable)
    {
        return [

          'message'=>"Hello, Notification from EasySms",

          //specify a field on your notifiable entity where you save the phone, Easysms uses phone field if set to null
          'field'=>null,

          //whom you want to send the sms as, Easysms uses sender id in config if not set, maximum of 11 chars,it can also be
          //a phone number
          'sender_id'=>'Easy Sms',

          //'if set, sms will be sent on the specified date but not immediately'
          'datetime'=>null,
        ];
    }
}
```

 <h4>Use it like so</h4>
 
 <p>Please make sure your nofitiable entity uses the notifiable trait like so</p>
 
 ```php
<?php

namespace App;
use Illuminate\Notifications\Notifiable;

class YourModel
{
    use Notifiable;

    
}

 ```
  
```php
  
<?php
namespace App\Http\Controllers;

use App\Notifications\TestNotification;

class SmsController extends Controller
{
    public function notify()
    {
    $user = auth()->user();
    //Note you can schedule notifications too, just accept a data, eg. date in your TestNotification Constructor and supply it here
    //$date = now()->addMinutes(20);
    //$user->notify(new TestNotification($date));
    
    //notify user
    $user->notify(new TestNotification);;
    return redirect()->back();
    }
}
```

  # Testing
  <p>If you write unit tests for your functions, kindly <b>Inject EasySms in your functions as opposed to instantiating it </b> (You would realize EasySms was injected in all the example funtions). <b>The reason is simply to allow you mock EasySms in your test cases</b>.</p>
  

