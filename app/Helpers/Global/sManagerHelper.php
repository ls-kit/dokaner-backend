<?php


if (!function_exists('sManagerInitiatePayment')) {
  function sManagerInitiatePayment($info)
  {

    $smanager_env = get_setting('smanager_env');
    $client_id = get_setting('smanager_client_id');
    $client_secret = get_setting('smanager_client_secret');

    $base_url = "https://api.dev-sheba.xyz/v1/ecom-payment/initiate";
    if ($smanager_env == "smanager_live") {
      $base_url = "https://api.sheba.xyz/v1/ecom-payment/initiate";
    }

    try {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $info,
        CURLOPT_HTTPHEADER => array(
          'client-id:' . $client_id,
          'client-secret:' . $client_secret,
          'Accept: application/json'
        ),
      ));

      $response = curl_exec($curl);
      $response = json_decode($response, true) ?? [];

      // $code = array_key_exists('code', $response) ? $response['code'] : [];
      // $data = array_key_exists('data', $response) ? $response['data'] : [];
      // $message = array_key_exists('message', $response) ? $response['message'] : [];

      return $response;

      curl_close($curl);
    } catch (\Exception $ex) {
      return Redirect::back()
        ->withErrors([$ex->getMessage()]);
    }
  }
}


if (!function_exists('sManagerTrnxDetails')) {
  function sManagerTrnxDetails($transaction_id)
  {
    $curl = curl_init();

    $base_url = config('smanager.s_manager_base_url') . "/v1/ecom-payment/details?transaction_id=" . $transaction_id;
    $client_id = config('smanager.s_manager_client_id');
    $client_secret = config('smanager.s_manager_client_secret');

    curl_setopt_array($curl, array(
      CURLOPT_URL => $base_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'client-id:' . $client_id,
        'client-secret:' . $client_secret
      ),
    ));

    $response = curl_exec($curl);
    $response = json_decode($response, true) ?? [];
    // dd($response);
    if (is_array($response)) {
      $response = array_key_exists('data', $response) ? $response['data'] : [];
    } else {
      $response = [];
    }
    curl_close($curl);
    return $response;
  }
}
