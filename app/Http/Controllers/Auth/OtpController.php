<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate(['phone' => 'required|string']);

        $phoneNumber = $validatedData['phone'];

        // if (app()->environment('local')) {
        //     $otp = 1234;
        //     Session::put('otp', (string) $otp);
        //     Session::put('phone_number', $phoneNumber);
        // } else {
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        $this->sendOtp($phoneNumber);
        // }
        return response()->json(['status' => 'OTP sent for registration']);
    }

    public function test()
    {
        dd(session()->all());
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validate(['phone' => 'required|string']);
        $phoneNumber = $validatedData['phone'];

        // if (app()->environment('local')) {
        //     $otp = 1234;
        //     Session::put('otp', (string) $otp);
        //     Session::put('phone_number', $phoneNumber);
        // }else{
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        $this->sendOtp($phoneNumber);
        // }
        return response()->json(['status' => 'OTP sent for login']);
    }

    // public function formatPhoneNumber($phoneNumber)
    // {
    //     // if(strlen($phoneNumber) == 13) {
    //     //     return $phoneNumber;
    //     // }elseif (strlen($phoneNumber) == 11) {
    //     //     $phoneNumber = '88' . $phoneNumber;
    //     // } elseif (strlen($phoneNumber) == 10) {
    //     //     $phoneNumber = '880' . $phoneNumber;
    //     // }
    //     return '88' . $phoneNumber;
    // }

    public function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters (e.g., spaces, dashes)
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the phone number already starts with '88'
        if (strpos($phoneNumber, '88') === 0) {
            return $phoneNumber; // Return as is
        }

        // Prepend '88' if it doesn't already start with '88'
        return '88' . $phoneNumber;
    }

    public function sendOtp($phoneNumber)
    {
        $otp = rand(1000, 9999);
        Log::info('OTP for login: ' . $otp);
        Session::put('otp', (string) $otp);
        Session::put('phone_number', $phoneNumber);
        $smsResult = $this->sendSMS($phoneNumber, $otp);

        // Session Store the SMS result (optional)
        Session::put('sms_result', $smsResult);
    }

    public function sendSMS($phone, $otp)
    {
        $apiUserName = env('SMS_API_USERNAME');
        $apiKey = env('SMS_API_KEY');
        $apiUrl = env('SMS_API_URL');
        $senderName = env('SMS_SENDER_NAME');

        if (empty($apiUserName) || empty($apiKey) || empty($apiUrl) || empty($senderName)) {
            Log::error("SMS API configuration is incomplete in .env");
            return ["success" => false, "error" => "SMS API configuration is incomplete"];
        }

        $message = "Welcome to Khoroch Pati. Your OTP is: $otp";

        $queryParams = http_build_query([
            'UserName' => $apiUserName,
            'Apikey' => $apiKey,
            'MobileNumber' => $phone,
            'SenderName' => $senderName,
            'TransactionType' => 'T',
            'Message' => $message,
        ]);

        $fullUrl = $apiUrl . '?' . $queryParams;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Enable SSL verification with CA certificate bundle
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certs/cacert.pem'));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error("cURL Error: " . $error);
            return ["success" => false, "error" => "cURL Error: $error"];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        Log::info("Raw API Response: " . $response);
        $responseData = json_decode($response, true);
        return $responseData;
    }

    public function verifyOtp(Request $request)
    {
        $userOtp = $request->input('otp');
        $sessionOtp = Session::get('otp');

        if ($userOtp == $sessionOtp) {
            return response()->json(['status' => 'OTP verified']);
        } else {
            return response()->json(['status' => 'Invalid OTP'], 400);
        }
    }

    public function sendOrderStatusSMS($phone, $orderCode, $status)
    {
        $apiUserName = env('SMS_API_USERNAME');
        $apiKey = env('SMS_API_KEY');
        $apiUrl = env('SMS_API_URL');
        $senderName = env('SMS_SENDER_NAME');

        // Validate SMS API configuration
        if (empty($apiUserName) || empty($apiKey) || empty($apiUrl) || empty($senderName)) {
            Log::error("SMS API configuration is incomplete in .env");
            return ["success" => false, "error" => "SMS API configuration is incomplete"];
        }

        // Customize the message based on the order status
        $message = match ($status) {
            'pending' => "Your order #{$orderCode} has been placed. Thank you for shopping from Khoroch Pati.",
            'shipped' => "Your order #{$orderCode} has been shipped. It's on its way to you! Thank you for shopping from Khoroch Pati.",
            'delivered' => "Your order #{$orderCode} has been delivered. Enjoy your purchase! Thank you for shopping from Khoroch Pati.",
            'successful' => "Your order #{$orderCode} has been confirmed. Thank you for your payment! Stay with Khoroch Pati.",
            'refunded' => "Your order #{$orderCode} has been refunded. Please check your account.",
            'canceled' => "Your order #{$orderCode} has been canceled. Contact us for more details.",
            default => "Your order #{$orderCode} status has been updated to: {$status}.",
        };

        // Build the query parameters
        $queryParams = http_build_query([
            'UserName' => $apiUserName,
            'Apikey' => $apiKey,
            'MobileNumber' => $phone,
            'SenderName' => $senderName,
            'TransactionType' => 'T', // Transaction type
            'Message' => $message,
        ]);

        $fullUrl = $apiUrl . '?' . $queryParams;

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Enable SSL verification with CA certificate bundle
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certs/cacert.pem'));

        // Execute the request
        $response = curl_exec($ch);

        // Handle cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error("cURL Error: " . $error);
            return ["success" => false, "error" => "cURL Error: $error"];
        }

        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL
        curl_close($ch);

        // Log and return the response
        Log::info("Raw API Response: " . $response);
        $responseData = json_decode($response, true);
        return $responseData;
    }
}