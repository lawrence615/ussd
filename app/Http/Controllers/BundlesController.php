<?php

namespace App\Http\Controllers;

use App\BundlesMenu;
use App\BundlesMenuItems;
use App\UssdLogs;
use App\UssdUser;
use Illuminate\Http\Request;

use App\Http\Requests;

class BundlesController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $request->get('sessionId');
        $serviceCode = $request->get('serviceCode');
        $phoneNumber = $request->get('phoneNumber');
        $text = $request->get('text');


        /**
         * log all ussd requests
         */
        UssdLogs::create(['phone' => $phoneNumber, 'text' => $text, 'session_id' => $sessionId, 'service_code' => $serviceCode]);

        /**
         * check if user exists
         *
         * count 9 numbers from right
         */

        $no = substr($phoneNumber, -9);

        $user = UssdUser::where('phone', "0" . $no)->orWhere('phone', "254" . $no)->first();

        if (!$user) {
            $data = [];
            $data['phone'] = "0" . $no;
            $data['session'] = 0;
            $data['progress'] = 0;
            $data['menu_item_id'] = 0;

            $ussd_user = UssdUser::create($data);


            $ussd_user->menu_id = 1;
            $ussd_user->session = 1;
            $ussd_user->progress = 0;
            $ussd_user->save();

            /**
             * get home menu
             */

            $bundles_menu = BundlesMenu::find(1);
            $bundles_menu_items = $this->getMenuItems($bundles_menu->id);

            $i = 1;
            $response = $bundles_menu->title . PHP_EOL;
            foreach ($bundles_menu_items as $key => $value) {
                $response = $response . $i . ": " . $value->description . PHP_EOL;
                $i++;
            }

            $this->sendResponse($response, 1);

        }

        $result = explode("*", $text);
        if (empty($result)) {
            $message = $text;
        } else {

            end($result);
            // move the internal pointer to the end of the array
            $message = current($result);
        }


        if (($this->user_is_starting($text)) || ($message == '0')) {
            $user->menu_id = 1;
            $user->session = 1;
            $user->progress = 0;
            $user->save();


            /**
             * get home menu
             */

            $bundles_menu = BundlesMenu::find(1);
            $bundles_menu_items = $this->getMenuItems($bundles_menu->id);

            $i = 1;
            $response = $bundles_menu->title . PHP_EOL;
            foreach ($bundles_menu_items as $key => $value) {
                $response = $response . $i . ": " . $value->description . PHP_EOL;
                $i++;
            }

            $this->sendResponse($response, 1);
        } else {

            $result = explode("*", $text);
            if (empty($result)) {
                $message = $text;
            } else {

                end($result);
                // move the internal pointer to the end of the array
                $message = current($result);
            }

            switch ($user->session) {
                case 1:
                    $response = $this->nextStep($user, $message);
                    break;

            }


            $this->sendResponse($response, 1);
        }


    }

    protected function nextStep($user, $message)
    {
        $menu = BundlesMenu::find($user->menu_id);
        $menu_items = $this->getMenuItems($user->menu_id);


        $i = 1;
        $choice = "";
        $next_menu_id = 0;

        foreach ($menu_items as $key => $value):
            $choice = $value->id;
            $next_menu_id = $value->next_menu_id;
            $i++;
        endforeach;


        /**
         *We expect a user input at this point, if none we respond with the following message and also show the choices again
         */
        if (empty($choice)) {
//            $response = "We could not understand your response" . PHP_EOL;

//            $i = 1;
//            $response = "We could not understand your response" . PHP_EOL . $menu->title . PHP_EOL;
//
//            foreach ($menu_items as $key => $value):
//                $response = $response . $i . ": " . $value->description . PHP_EOL;
//            endforeach;

            return $this->getErrorMessage($user);
        } else {

            $menu = BundlesMenu::find($next_menu_id);



        }


    }

    protected function previousStep($user, $message)
    {

    }


    protected function nextStepSwitch($user, $message, $menu)
    {
        $menu_items = self::getMenuItems($menu->id);
    }

    function getErrorMessage($user){
        $menu = BundlesMenu::find($user->menu_id);
        $menu_items = $this->getMenuItems($user->menu_id);



        $i = 1;
        $response = "We could not understand your response" . PHP_EOL . $menu->title . PHP_EOL;

        foreach ($menu_items as $key => $value):
            $response = $response . $i . ": " . $value->description . PHP_EOL;
        endforeach;

        return $response;
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


    public function user_is_starting($text)
    {
        if (strlen($text) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //Menu Items Function
    public static function getMenuItems($menu_id)
    {
        $menu_items = BundlesMenuItems::whereMenuId($menu_id)->get();
        return $menu_items;
    }
}
