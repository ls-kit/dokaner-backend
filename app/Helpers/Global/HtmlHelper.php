<?php

use App\Helpers\General\HtmlHelper;

if (!function_exists('style')) {
  /**
   * @param       $url
   * @param array $attributes
   * @param null  $secure
   *
   * @return mixed
   */
  function style($url, $attributes = [], $secure = null)
  {
    return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
  }
}

if (!function_exists('script')) {
  /**
   * @param       $url
   * @param array $attributes
   * @param null  $secure
   *
   * @return mixed
   */
  function script($url, $attributes = [], $secure = null)
  {
    return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
  }
}

if (!function_exists('form_cancel')) {
  /**
   * @param        $cancel_to
   * @param        $title
   * @param string $classes
   *
   * @return mixed
   */
  function form_cancel($cancel_to, $title, $classes = 'btn btn-danger')
  {
    return resolve(HtmlHelper::class)->formCancel($cancel_to, $title, $classes);
  }
}

if (!function_exists('form_submit')) {
  /**
   * @param        $title
   * @param string $classes
   *
   * @return mixed
   */
  function form_submit($title, $classes = 'btn btn-success')
  {
    return resolve(HtmlHelper::class)->formSubmit($title, $classes);
  }
}

if (!function_exists('active_class')) {
  /**
   * Get the active class if the condition is not falsy.
   *
   * @param        $condition
   * @param string $activeClass
   * @param string $inactiveClass
   *
   * @return string
   */
  function active_class($condition, $activeClass = 'active', $inactiveClass = '')
  {
    return $condition ? $activeClass : $inactiveClass;
  }
}



if (!function_exists('url_exists')) {
  function url_exists($url)
  {
    return true;
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
  }
}
