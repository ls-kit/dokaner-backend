<?php

if (!function_exists('load_imagga_api')) {
    function load_imagga_api()
    {
        return get_setting('image_search_api_url');
    }
}

if (!function_exists('load_imagga_key')) {
    function load_imagga_key()
    {
        return get_setting('image_search_api_key');
    }
}

if (!function_exists('load_imagga_secret')) {
    function load_imagga_secret()
    {
        return get_setting('image_search_api_secret');
    }
}

if (!function_exists('getImageInfo')) {
    function getImageInfo($location)
    {
        $file_path = $location;
        $api_credentials = array(
            'key' => load_imagga_key(),
            'secret' => load_imagga_secret()
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, load_imagga_api() . '/tags');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERPWD, $api_credentials['key'] . ':' . $api_credentials['secret']);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        $fields = [
            'image' => new \CurlFile($file_path, 'image/jpeg', 'image.jpg'),
            'limit' => 1
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $response = curl_exec($ch);
        curl_close($ch);

        $json_response = json_decode($response);
        // var_dump($json_response);

        if ($json_response->status->type == 'success') {
            return $json_response->result->tags[0]->tag->en;
        } else {
            return $json_response->status->type;
        }
    }
}
