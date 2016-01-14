<?php

namespace App\Http\Controllers;

use App\KplcStaff;
use App\UssdUser;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class KplcStaffVerificationController extends Controller
{


    /**
     * This is my starting point
     * @param Request $request
     */
    public function index(Request $request)
    {

        $sessionId = $request->get('sessionId');
        $serviceCode = $request->get('serviceCode');
        $phoneNumber = $request->get('phoneNumber');
        $text = $request->get('text');


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


            UssdUser::create($data);
            if ($text == '') {
                $this->sendResponse("Please enter staff ID e.g ekp1111", 1);
            }

        } else {
            if ($text == '') {
                $this->sendResponse("Please enter staff ID e.g ekp1111", 1);
            }

            $id = $text;
//            print_r($id);exit;

            $staff = $this->getStaffId($id);
            if ($staff) {
                $message = "ID is valid and it belongs to " . $staff->first_name . " " . $staff->last_name;
            } else {
                $message = "No Staff with that id";

            }


            $this->sendResponse($message, 2);
        }


    }


    public function getStaffId($id)
    {
        $staff = KplcStaff::where('staff_id', $id)->first(['first_name', 'last_name', 'staff_id']);
        return $staff;

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
