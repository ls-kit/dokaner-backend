<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

if (!function_exists('adnConfig')) {
  function adnConfig()
  {
    $api_key = get_setting('adn_api_key');
    $api_secret = get_setting('adn_api_secret');

    return [
      'domain' => "https://portal.adnsms.com",
      'apiCredentials' => [
        'api_key' => $api_key,
        'api_secret' => $api_secret,
      ],
      'apiUrl' => [
        'check_balance' => "/api/v1/secure/check-balance",
        'send_sms' => "/api/v1/secure/send-sms",
        'check_campaign_status' => "/api/v1/secure/campaign-status",
        'check_sms_status' => "/api/v1/secure/sms-status",
      ],
    ];
  }
}


if (!function_exists('load_ADN')) {
  function load_ADN()
  {
    $adnConfig = adnConfig();
    return new Client([
      'base_uri' => $adnConfig['domain'],
      // 'timeout' => 8.0
    ]);
  }
}


if (!function_exists('send_adn_single_sms')) {
  function send_adn_single_sms($txt, $phone)
  {
    $adnConfig = adnConfig();
    $api_url = $adnConfig['apiUrl']['send_sms'];
    $api_key = $adnConfig['apiCredentials']['api_key'];
    $api_secret = $adnConfig['apiCredentials']['api_secret'];

    $data = [
      'api_key' => $api_key,
      'api_secret' => $api_secret,
      'request_type' => 'OTP',
      'message_type' => 'TEXT',
      'mobile' => $phone,
      'message_body' => $txt
    ];
    return callToAdnApi($api_url, $data);
  }
}



if (!function_exists('callToAdnApi')) {
  function callToAdnApi($url, $params)
  {
    try {
      $response = load_ADN()->request('POST', $url,  ['query' => $params]);
      $statusCode = $response->getStatusCode();
      if ($statusCode == 200) {
        $content = json_decode($response->getBody(), true) ?? [];
        if (key_exists('api_response_message', $content)) {
          return $content['api_response_message'] == "SUCCESS" ? true : false;
        }
      }
    } catch (RequestException $e) {
      return $e->getMessage();
    }

    return false;
  }
}
