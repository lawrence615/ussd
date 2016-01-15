<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailerController extends Controller
{
    public function index(Request $request)
    {
        $to_email = $request->get('email');
        $subject_email = $request->get('subject');
        $message_email = $request->get('message');


        $result = self::messageSend($to_email, $subject_email, $message_email);
        print_r($result);
        exit;
    }

    protected function messageSend($to, $subject, $message)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . env('MAILGUN_API'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        $plain = strip_tags(self::br2nl($message));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/' . env('MAILGUN_DOMAIN') . '/messages');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'info@' . env('MAILGUN_DOMAIN'),
            'to' => $to,
            'subject' => $subject,
            'html' => $message,
            'text' => $plain));


        $j = json_decode(curl_exec($ch));

        $info = curl_getinfo($ch);



        if ($info['http_code'] != 200)
            error("Fel 313: Vänligen meddela detta via E-post till info@" . env('MAILGUN_DOMAIN'));

        curl_close($ch);

        return $j;
    }

    protected function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
}
