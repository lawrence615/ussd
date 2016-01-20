<?php

namespace App\Http\Controllers;

use App\BundlesMenu;
use App\BundlesMenuItems;
use App\BundlesSubscription;
use App\UssdLogs;
use App\UssdResponse;
use App\UssdUser;
use Illuminate\Http\Request;

use App\Http\Requests;

class BundlesController extends Controller
{
    public function index(Request $request)
    {
        //get inputs
        $sessionId = $request->get('sessionId');
        $serviceCode = $request->get('serviceCode');
        $phoneNumber = $request->get('phoneNumber');
        $text = $request->get('text');

        /**
         * log all ussd requests
         */

        UssdLogs::create(['phone' => $phoneNumber, 'text' => $text, 'session_id' => $sessionId, 'service_code' => $serviceCode]);

        $no = substr($phoneNumber, -9);

        //verify that the user exists
        $user = UssdUser::where('phone', "0" . $no)->orWhere('phone', "+254" . $no)->first();
        if (!$user) {
            $data = ['phone' => $phoneNumber, 'session' => 0, 'progress' => 0, 'pin' => 0, 'menu_id' => 0, 'menu_item_id' => 0];
            $user = Ussduser::create($data);

        }


        if (($this->user_is_starting($text))) {
            $user->menu_id = 1;
            $user->session = 1;
            $user->progress = 0;
            $user->save();

            $menu = BundlesMenu::find(1);

            $response = $this->nextStepSwitch($user, $menu);


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

                case 0 :
                    break;
                case 1:
                    $response = $this->continueUssdProgress($user, $message);
                    break;
                case 2:
                    $response = $this->subscribeToDailyInternetBundles($user, $menu = null, $message);
                    break;
                case 3:
                    break;

                default:
                    break;

            }


            $this->sendResponse($response, 1);
        }


    }


    public function continueUssdProgress($user, $message)
    {
        $menu = BundlesMenu::find($user->menu_id);

        switch ($menu->menu_type) {
            case 0:
                break;
            case 1:
                $response = $this->continueUssdMenu($user, $message, $menu);
                break;
        }

        return $response;
    }


    public function continueUssdMenu($user, $message, $menu)
    {
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
            $i = 1;
            $response = "We could not understand your response" . PHP_EOL . $menu->title . PHP_EOL;
            foreach ($menu_items as $key => $value) {
                $response = $response . $i . ": " . $value->description . PHP_EOL;
                $i++;
            }

            return $response;
        } else {

            $menu = BundlesMenu::find($next_menu_id);
            $response = $this->nextStepSwitch($user, $menu);

            return $response;
        }

    }

    protected function nextStepSwitch($user, $menu)
    {

        switch ($menu->menu_type) {
            case 1:
                $menu_items = self::getMenuItems($menu->id);
                $response = $menu->title . PHP_EOL;

                $i = 1;
                foreach ($menu_items as $key => $value) {
                    $response = $response . $i . ": " . $value->description . PHP_EOL;
                    $i++;
                }

                $user->session = 1;
                $user->menu_id = $menu->id;
                $user->menu_item_id = 0;
                $user->progress = 0;
                $user->save();
                break;


            case 2:
                $response = $this->subscribeToDailyInternetBundles($user, $menu);

            default:
                break;
        }


        return $response;

    }

    function subscribeToDailyInternetBundles($user, $menu, $message = null)
    {
        switch ($user->progress) {
            case 0:
                $bundleOptions = $this->getBundleOptions(2);

                $i = 1;
                $response = "Daily Internet Bundles: " . PHP_EOL;
                foreach ($bundleOptions as $option) {
                    $response = $response . PHP_EOL . $i . ": " . $option->description;
                    $i++;
                }
                break;

            case 1:

                $this->storeUssdResponseFromUser($user, $message);
                $user->session = 2;
                $user->progress = 2;
                $user->menu_id = $menu->id;
                $user->menu_item_id = 1;
                $user->save();
                break;
        }

        return $response;
    }


    function getBundleOptions($menu_id)
    {
        $results = BundlesMenuItems::whereMenuId($menu_id)->get(['id', 'menu_id', 'description', 'next_menu_id']);
        return $results;
    }


    function confirmBundlesToBuy($user, $message = null)
    {
        switch ($user->progress) {
            case 0:

                $user->session = 4;
                $user->progress = 1;
                $user->save();
                break;
        }


        return $response;
    }

    function getBundlesToBeConfirmed($user)
    {
        $no = substr($user->phone, -9);
        $no = "0" . $no;
    }

    function getUnConfirmedBets($phone)
    {

    }

    function getErrorMessage($user)
    {
        $menu = BundlesMenu::find($user->menu_id);
        $menu_items = $this->getMenuItems($user->menu_id);


        $i = 1;
        $response = "We could not understand your response" . PHP_EOL . $menu->title . PHP_EOL;

        foreach ($menu_items as $key => $value):
            $response = $response . $i . ": " . $value->description . PHP_EOL;
        endforeach;

        return $response;
    }

    public
    function sendResponse($response, $type)
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


    public
    function user_is_starting($text)
    {
        if (strlen($text) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public
    function getLatestMessage($text)
    {
        $result = explode("*", $text);
        if (empty($result)) {
            $message = $text;
        } else {
            end($result);
            $message = current($result);
        }
        return $message;
    }

//Menu Items Function
    public static function getMenuItems($menu_id)
    {
        $menu_items = BundlesMenuItems::whereMenuId($menu_id)->get();
        return $menu_items;
    }


    public function resetUser($user)
    {
        $user->session = 0;
        $user->progress = 0;
        $user->menu_id = 0;
        $user->confirm_from = 0;
        $user->menu_item_id = 0;

        return $user->save();

    }


    public function storeUssdResponseFromUser($user, $message)
    {
        $data = ['phone' => $user->phone, 'menu_id' => $user->menu_id, 'menu_item_id' => $user->menu_item_id, 'response' => $message];
        return UssdResponse::create($data);
    }
}
