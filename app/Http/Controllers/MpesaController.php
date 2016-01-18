<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MpesaController extends Controller
{


    protected $user_current_leveel;

    public function index(Request $request)
    {
        $text = $request->get('text');

        $input = $this->getInput($text);

        switch ($input['level']) {
            case 0:
                $response = $this->getMainMenu();
                break;
            case 1:
                $response = $this->levelOneProcess($input);
                break;
            case 2:
                //enter phone no.-->amount->pin->confirm
                $this->user_current_leveel = 2;
                $response = $this->levelTwoProcess($input);
                break;

            case 3:
                $response = $this->processUserInput($input);
                break;

            default:
                $response = $this->getMainMenu();
                break;

        }


        $this->sendResponse($response, 1);
    }


    public function getMainMenu()
    {
        return "Safaricom:" . PHP_EOL . "1.Safaricom+" . PHP_EOL . "2.M-PESA";
    }

    function getErrorMessage()
    {
        return "We do not understand your response";
    }

    function getMpesaMenu()
    {
        return "MPESA:" . PHP_EOL . "1.Send Money" . PHP_EOL . "2.Withdraw Cash" . PHP_EOL . "3.Buy Airtime";
    }

    function getSafaricomMenu()
    {
        return "Still in progress";
    }


    function getPhoneNumberInput()
    {
        return "Enter Phone Number" . PHP_EOL;
    }


    function getAmountInput()
    {
        return "Enter amount to send" . PHP_EOL;
    }

    function getPinInput()
    {
        return "Enter Pin" . PHP_EOL;
    }

    function getConfirmationDialog()
    {

    }


    protected function levelOneProcess($input)
    {
        switch ($input['message']) {
            case 1:
                $response = $this->getSafaricomMenu();
                break;
            case 2:
                $response = $this->getMpesaMenu();
                break;
            default:
                $response = $this->getErrorMessage();
                break;
        }

        return $response;
    }

    protected function levelTwoProcess($input)
    {
        switch ($input['message']) {
            case 1:
                $response = $this->getPhoneNumberInput();
                break;
            case 2:
                $response = $this->getAmountInput();
                break;
            case 3:
                break;
            default:
                $response = $this->getErrorMessage();
                break;
        }

        return $response;
    }

    protected function processUserInput($input)
    {
        $exploded_text = $input['exploded_text'];
        $mpesa_process = $exploded_text[1];

//        dd($mpesa_process);

        switch ($mpesa_process) {
            case 1:
                $response = $this->getAmountInput();
                break;
            case 2:
                break;
            case 3:
                break;
            default:
                $response = $this->getErrorMessage();
                break;
        }

        return $response;
    }

    protected function sendMoneyProcess()
    {



    }


    protected function getInput($text)
    {
        $input = [];

        if (empty($text)) {
            $input['level'] = 0;
            $input['message'] = "";
        } else {
            $exploded_text = explode('*', $text);

            $input['exploded_text'] = $exploded_text;
            $input['level'] = count($exploded_text);
            $input['message'] = end($exploded_text);
        }

        return $input;
    }


    protected function sendResponse($response, $type)
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
