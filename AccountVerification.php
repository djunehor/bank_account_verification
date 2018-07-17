<?php
    
    /*
This PHP script helps to verify a Nigerian bank account name
using interswitch API
it returns the account name if successful
*/
        date_default_timezone_set("UTC");

        //the following are gotten from interswitch developer dashboard
        $clientID = "E06640B8005C2EEC13577966D9FD7EB2";
        $secret = "89597435e7c0606906f10e95252aee33=";
        $terminalID = "5PVI0002";

        //$accessKey = "eyJhbGciOiJSUzI1NiJ9.eyJzY29wZSI6WyJwcm9maWxlIl0sImV4cCI6MTUyOTU4MDIzNywianRpIjoiZGYyNGJmMWItMmNlNi00NmZkLWFkMmMtMDY0MmQzNDJkZjYxIiwiY2xpZW50X2lkIjoiSUtJQTVGN0NCNEU2M0U5NTkzNDcxNTcwOUM3OEUwN0U4NzVFQTUxNDgzQkQifQ.RYWYQ0g6sqw4L_4zclhTwinEpQZqbiV3XsUZ-kYXlIOSlEu9uYrSQX8uXxbDBUHQsMUEKwplQjTiLQbyd4FOm3HNi1mMiIMhIPiJhVT637389u8VQU0KmYEVryMXU-miwjPHIuWC5odqTun2gV4_CexqCLvbw0759tvi1hCARDWTKky5ywEvcW7jpZc9YFWCNH6HcXCTPRI_new-JMveAaY0x9-vI238E2UyR0ECl8f83-tHScvu2gd-EW79pxqGavaoghoIlUbFgAtkQGTJZmDBWNMg5PR45A2Oqu739NI8qkajSjf4Kl-1UVYUcPbzBJYITkufv2heXY8ZsS28cQ";
        /*
           Account Number: 9999999999 , Bank Code: 011 - Valid account Scenario
	       Account Number: 0000000000 Bank Code: 086 - Dormat account Scenario
         */
        $bankCode = "044";
        $AccountID = "9999999999"; // You can play around with the dormat scenario 
        $remoteUrl = "https://sandbox.interswitchng.com/";
        $Url = "api/v1/nameenquiry/banks/accounts/names";
        $httpVerb = "GET";
        $contentType = "application/json"; //charset=utf-8
        $timeStamp = time();

        $fullUrl = $remoteUrl.$Url;
        $percentEncodedUrl = urlencode($fullUrl);
        $nonce = rand(1111, 99999).rand(00000, 99999);
        
        $signatureConValue = $httpVerb . "&" . $percentEncodedUrl . "&" . $timeStamp . "&" . $nonce . "&" . $clientID . "&" . $secret;
        $auth = base64_encode($clientID);
        $authorization = "InterswitchAuth" . " " . $auth;
        $signatureMethod = "SHA1";
        $signature = base64_encode(sha1($signatureConValue, true));
        
        
        $headers = array (
            "Content-Type: $contentType",
            "Authorization: $authorization",
            "Signature: $signature",
            "Nonce: $nonce",
            "Timestamp: $timeStamp",
            "SignatureMethod: $signatureMethod",
            "terminalID: $terminalID",
            "accountId: $AccountID",
            "bankCode: $bankCode"
        );
		
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $fullUrl);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $content = json_decode(curl_exec( $ch ),true);
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            
            curl_close($ch);
            $response['status'] = (array_key_exists('accountName', $content) && strlen($content['accountName']) > 6) ? true : false;
            $response['message'] = (array_key_exists('accountName', $content) && strlen($content['accountName']) > 6) ? $content['accountName'] : "Something went wrong";

            echo json_encode($response); //we want to return response as json
           
            
        