<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UssdController extends Controller
{
    public function index(Request $request)
    {
        $serviceCode = $request->get('serviceCode');

        $remove_hash = explode("#", $serviceCode, -1);
        $filter = array_filter($remove_hash);
        $extension = explode("*", $filter[0]);


        if (array_key_exists(4, $extension)) {
            switch ($extension[4]) {
                case 1:
                    break;
            }

        } else {
            $main_menu = "Welcome to Masharyan Ussd apps. Proceed with any:" . PHP_EOL;

            $response = $main_menu;
            $main_menu_items = $this->getAvailableApps();

            $no = 1;
            foreach ($main_menu_items as $key => $value):
                $response .= $no . ":" . $value;
                $no++;
            endforeach;

            $this->sendResponse($response, 1);
        }


        exit;

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
