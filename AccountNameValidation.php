<?php
 /*
This PHP script helps to verify a Nigerian bank account name
using paystack API
it returns the account name if successful
*/
        $bankCode = "058"; //bank CBN code https://bank.codes/api-nigeria-nuban/
        $AccountID = "0215113983"; //NUBAN account number
        $baseUrl = "https://api.paystack.co";
        $endpoint = "/bank/resolve?account_number=".$AccountID."&bank_code=".$bankCode;
        $httpVerb = "GET";
        $contentType = "application/json"; //e.g charset=utf-8
        $authorization = "sk_test_89597435e7c0606906f10e95252aee33"; //gotten from paystack dashboard
        
    
        $headers = array (
            "Content-Type: $contentType",
            "Authorization: Bearer $authorization"
        );
		
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $baseUrl.$endpoint);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $content = json_decode(curl_exec( $ch ),true);
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            
            curl_close($ch);

            if($content['status']) {
                    $response['account_number'] = $content['data']['account_number'];
                    $response['account_name'] = $content['data']['account_name'];

                    echo json_encode($response);
            } else {
                echo $content['message'];
            }
            
        