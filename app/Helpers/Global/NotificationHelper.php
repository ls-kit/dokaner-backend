<?php


if (!function_exists('generate_customer_notifications')) {
    function generate_customer_notifications($status, $user, $order_number = '', $amount = '', $tracking = '')
    {
        $emailActive = null;
        $text = null;
        $smsActive = null;
        $smsText = null;

        if ($status == 'purchased') {
            $emailActive = get_setting('active_purchased_message');
            $text = get_setting('purchased_message');
            $smsActive = get_setting('sms_active_purchased_message');
            $smsText = get_setting('sms_purchased_message');
        } elseif ($status == 'shipped-from-suppliers') {
            $emailActive = get_setting('active_shipped_from_suppliers');
            $text = get_setting('shipped_from_suppliers');
            $smsActive = get_setting('sms_active_shipped_from_suppliers');
            $smsText = get_setting('sms_shipped_from_suppliers');
        } elseif ($status == 'received-in-china-warehouse') {
            $emailActive = get_setting('active_received_in_china_warehouse');
            $text = get_setting('received_in_china_warehouse');
            $smsActive = get_setting('sms_active_received_in_china_warehouse');
            $smsText = get_setting('sms_received_in_china_warehouse');
        } elseif ($status == 'shipped-from-china-warehouse') {
            $emailActive = get_setting('active_shipped_from_china_warehouse');
            $text = get_setting('shipped_from_china_warehouse');
            $smsActive = get_setting('sms_active_shipped_from_china_warehouse');
            $smsText = get_setting('sms_shipped_from_china_warehouse');
        } elseif ($status == 'received-in-BD-warehouse') {
            $emailActive = get_setting('active_received_in_bd_warehouse');
            $text = get_setting('received_in_bd_warehouse');
            $smsActive = get_setting('sms_active_received_in_bd_warehouse');
            $smsText = get_setting('sms_received_in_bd_warehouse');
        } elseif ($status == 'on-transit-to-customer') {

            $emailActive = get_setting('active_on_transit_to_customer');
            $text = get_setting('on_transit_to_customer');
            $smsActive = get_setting('sms_active_on_transit_to_customer');
            $smsText = get_setting('sms_on_transit_to_customer');
        } elseif ($status == 'delivered') {

            $emailActive = get_setting('active_delivered_completed');
            $text = get_setting('delivered_completed');
            $smsActive = get_setting('sms_active_delivered_completed');
            $smsText = get_setting('sms_delivered_completed');
        } elseif ($status == 'out-of-stock') {

            $emailActive = get_setting('active_out_of_stock');
            $text = get_setting('out_of_stock');
            $smsActive = get_setting('sms_active_out_of_stock');
            $smsText = get_setting('sms_out_of_stock');
        } elseif ($status == 'adjustment') {

            $emailActive = get_setting('active_adjustment');
            $text = get_setting('adjustment');
            $smsActive = get_setting('sms_active_adjustment');
            $smsText = get_setting('sms_adjustment');
        } elseif ($status == 'refunded') {

            $emailActive = get_setting('active_refunded');
            $text = get_setting('refunded');
            $smsActive = get_setting('sms_active_refunded');
            $smsText = get_setting('sms_refunded');
        }

        $subject = str_replace('-', ' ', $status);
        $subject = ucwords($subject);
        if ($emailActive) {
            $generateText = generate_text_for_customer($text, "<b>{$order_number}</b>", "<b>{$amount}</b>", "<b>{$tracking}</b>");
            send_status_email($generateText, $subject, $user);
        }

        if ($smsActive) {
            $generateText = generate_text_for_customer($smsText, $order_number, $amount, $tracking);
            send_status_sms($generateText, $user);
        }
    }
}

if (!function_exists('generate_text_for_customer')) {

    function generate_text_for_customer($text, $replace_order_number, $replace_amount, $replace_tracking)
    {
        $app_url = config('app.url');
        $text = str_replace('[orderNumber]', $replace_order_number, $text);
        $text = str_replace('[amount]', $replace_amount, $text);
        $text = str_replace('[appUrl]', $app_url, $text);
        return str_replace('[tracking]', $replace_tracking, $text);
    }
}


if (!function_exists('send_status_email')) {
    function send_status_email($text, $subject, $user)
    {
        try {
            //  $when = now()->addSeconds(10);
            //  $user->notify((new \App\Notifications\Backend\OrderStatus($text, $subject))->delay($when));
            $email = $user->email ?? null;
            if ($email) {
                $user->notify((new \App\Notifications\Backend\OrderStatus($text, $subject)));
            }
        } catch (Exception $ex) {
            // dd($ex);
        }
    }
}

if (!function_exists('send_status_sms')) {
    function send_status_sms($text, $user)
    {
        try {
            $phone = $user->phone ?? null;
            if ($phone) {
                $status = send_adn_single_sms($text, $phone);
            }
        } catch (Exception $ex) {
        }
    }
}
