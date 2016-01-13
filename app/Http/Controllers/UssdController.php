<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class UssdController extends Controller
{
    public function index(Request $request)
    {
        $serviceCode = $request->get('serviceCode');
        $sessionId = $request->get('sessionId');
        $phoneNumber = $request->get('phoneNumber');
        $text = $request->get('text');

        $remove_hash = explode("#", $serviceCode, -1);
        $filter = array_filter($remove_hash);
        $extension = explode("*", $filter[0]);


        if (array_key_exists(4, $extension)) {
            switch ($extension[4]) {
                case 1:
                    $this->redirectToApp($phoneNumber, $sessionId, $this->getServerUrl() . "kplc", $text, $serviceCode);
                    break;
                default:
                    $this->sendResponse("App does not exist", 2);
                    break;
            }

        } else {
            if ($text == '') {
                $main_menu = "Welcome to Masharyan Ussd apps. Proceed with any:" . PHP_EOL;

                $response = $main_menu;
                $main_menu_items = $this->getAvailableApps();

                $no = 1;
                foreach ($main_menu_items as $key => $value):
                    $response .= $no . ":" . $value;
                    $no++;
                endforeach;

                $this->sendResponse($response, 1);
            } else {
                $selected_app = $text;

                switch ($selected_app) {
                    case 1:
                        $this->redirectToApp($phoneNumber, $sessionId, $this->getServerUrl() . "kplc", "", $serviceCode);
                        break;
                    default:
                        $this->sendResponse("Invalid selection", 2);
                        break;
                }

            }
        }


    }


    public function redirectToApp($phone, $session_id, $url, $text, $serviceCode)
    {
        $qry_str = "?phoneNumber=" . trim($phone) . "&text=" . urlencode($text) . "&sessionId=" . $session_id . "&serviceCode=" . $serviceCode;

//        dd($url . $qry_str);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $qry_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');
        $content = trim(curl_exec($ch));
        curl_close($ch);

        return $content;
    }

    public function getServerUrl()
    {
//        return \Illuminate\Support\Facades\Request::server('SERVER_NAME');
        return 'http://localhost:8000/ussd/serve/';
    }


    public function getAvailableApps()
    {
        $apps = [
            '1.' => 'kplc (*384*2014*615*1#)'
        ];

        return $apps;
    }


    public function sendResponse($response, $type)
    {

        switch ($type) {
            case 1:
                $output = "CON ";
                break;
            case 2:
                $output = "END ";
                break;

        }


        $output .= $response;
        header('Content-type: text/plain');
        echo $output;
        exit;

    }
}
