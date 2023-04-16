<?php



if (!function_exists('send_ware_SMS')) {
  /**
   * @param $txt
   * @param $phone
   * @return bool|string
   */
  function send_ware_SMS($txt, $phone)
  {
    $active_sms = get_setting('active_sms');

    if ($active_sms == 'mim_sms') {
      return mim_sms($txt, $phone);
    }

    if ($active_sms == 'adn_sms') {
      return send_adn_single_sms($txt, $phone);
    }

    // $csms_id = uniqid();
    // return singleSms($phone, $txt, $csms_id);
    // return mdl_sms($txt, $phone);
  }
}



if (!function_exists('mim_sms')) {
  function mim_sms($txt, $phone)
  {
    try {
      $url = "https://esms.mimsms.com/smsapi";
      $data = [
        "api_key" => get_setting('mim_api_key'),
        "type" => "text",
        "contacts" => $phone,
        "senderid" => get_setting('mim_senderid'),
        "msg" => $txt,
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      curl_close($ch);
      return $response;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
}




if (!function_exists('singleSms')) {
  function singleSms($msisdn, $messageBody, $csmsId)
  {
    $API_TOKEN = config('app.ISMS_API_TOKEN'); //put ssl provided api_token here
    $SID = config('app.ISMS_SID'); // put ssl provided sid here
    $DOMAIN = "https://smsplus.sslwireless.com";

    $params = [
      "api_token" => $API_TOKEN,
      "sid" => $SID,
      "msisdn" => $msisdn,
      "sms" => $messageBody,
      "csms_id" => $csmsId
    ];
    $url = trim($DOMAIN, '/') . "/api/v3/send-sms";
    $params = json_encode($params);

    return callApi($url, $params);
    // dd($params);
  }
}



if (!function_exists('bulkSms')) {
  function bulkSms($msisdns, $messageBody, $batchCsmsId)
  {
    $params = [
      "api_token" => API_TOKEN,
      "sid" => SID,
      "msisdn" => $msisdns,
      "sms" => $messageBody,
      "batch_csms_id" => $batchCsmsId
    ];
    $url = trim(DOMAIN, '/') . "/api/v3/send-sms/bulk";
    $params = json_encode($params);

    return callApi($url, $params);
  }
}



if (!function_exists('dynamicSms')) {
  function dynamicSms($messageData)
  {
    $params = [
      "api_token" => API_TOKEN,
      "sid" => SID,
      "sms" => $messageData,
    ];
    $params = json_encode($params);
    $url = trim(DOMAIN, '/') . "/api/v3/send-sms/dynamic";
    return callApi($url, $params);
  }
}

if (!function_exists('callApi')) {
  function callApi($url, $params)
  {
    $ch = curl_init(); // Initialize cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($params),
      'accept:application/json'
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}
