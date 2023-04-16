<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use GuzzleHttp\Client;

class SettingController extends Controller
{

    public function general()
    {
        return view('backend.content.settings.general.general');
    }

    public function logoStore(Request $request)
    {
        if (\request()->hasFile('frontend_logo_menu')) {
            $data['frontend_logo_menu'] = store_picture(\request()->file('frontend_logo_menu'), 'setting/logo');
        }
        if (\request()->hasFile('frontend_logo_footer')) {
            $data['frontend_logo_footer'] = store_picture(\request()->file('frontend_logo_footer'), 'setting/logo');
        }
        if (\request()->hasFile('frontend_logo_footer_two')) {
            $data['frontend_logo_footer_two'] = store_picture(\request()->file('frontend_logo_footer_two'), 'setting/logo');
        }
        if (\request()->hasFile('admin_logo')) {
            $data['admin_logo'] = store_picture(\request()->file('admin_logo'), 'setting/logo');
        }
        if (\request()->hasFile('favicon')) {
            $data['favicon'] = store_picture(\request()->file('favicon'), 'setting/logo');
        }
        Setting::save_settings($data);
        Cache::forget('settings'); // remove setting cache

        return redirect()->back()->withFlashSuccess('Logo Updated Successfully');
    }


    public function socialStore(Request $request)
    {
        $data = request()->all();
        unset($data['_token']);

        if (\request()->hasFile('meta_image')) {
            $data['meta_image'] = store_picture(\request()->file('meta_image'), 'setting/meta');
        }

        Setting::save_settings($data);

        // FOR MAIN DOMAIN
        $query = [
            'currency_rate' => $data['currency_rate'],
            'increase_rate' => $data['increase_rate']
        ];

        $client = new Client();
        $response = $client->request('POST', 'https://admin.dokaner.com/api/v1/update-currency-rates', ['query' => $query]);

        $client2 = new Client();
        $response = $client2->request('POST', 'https://admin.alibainternational.com/api/v1/update-currency-rates', ['query' => $query]);
        // FOR MAIN DOMAIN

        Cache::forget('settings'); // remove setting cache

        return redirect()->back()->withFlashSuccess('Setting Updated Successfully');
    }


    public function price()
    {
        return view('backend.content.settings.price-setting');
    }

    public function limit()
    {
        return view('backend.content.settings.order-limit-setting');
    }


    public function limitationStore()
    {
        $data = request()->all();
        unset($data['_token']);

        Setting::save_settings($data);
        Cache::forget('settings'); // remove setting cache

        return redirect()->back()->withFlashSuccess('Setting Updated Successfully');
    }


    public function message()
    {
        return view('backend.content.settings.message-setting');
    }


    public function messageStore()
    {
        $sms = \request('sms') ? 'sms_' : '';
        if ($sms) {
            $data['sms_active_otp_message'] = \request('sms_active_otp_message', null);
            $data['sms_otp_message'] = \request('sms_otp_message', null);
        }
        $data[$sms . 'active_waiting_for_payment'] = \request($sms . 'active_waiting_for_payment', null);
        $data[$sms . 'waiting_for_payment'] = \request($sms . 'waiting_for_payment', null);
        $data[$sms . 'active_partial_paid'] = \request($sms . 'active_partial_paid', null);
        $data[$sms . 'partial_paid'] = \request($sms . 'partial_paid', null);
        $data[$sms . 'active_purchased_message'] = \request($sms . 'active_purchased_message', null);
        $data[$sms . 'purchased_message'] = \request($sms . 'purchased_message', null);
        $data[$sms . 'active_shipped_from_suppliers'] = \request($sms . 'active_shipped_from_suppliers', null);
        $data[$sms . 'shipped_from_suppliers'] = \request($sms . 'shipped_from_suppliers', null);
        $data[$sms . 'active_received_in_china_warehouse'] = \request($sms . 'active_received_in_china_warehouse', null);
        $data[$sms . 'received_in_china_warehouse'] = \request($sms . 'received_in_china_warehouse', null);
        $data[$sms . 'active_shipped_from_china_warehouse'] = \request($sms . 'active_shipped_from_china_warehouse', null);
        $data[$sms . 'shipped_from_china_warehouse'] = \request($sms . 'shipped_from_china_warehouse', null);
        $data[$sms . 'active_received_in_bd_warehouse'] = \request($sms . 'active_received_in_bd_warehouse', null);
        $data[$sms . 'received_in_bd_warehouse'] = \request($sms . 'received_in_bd_warehouse', null);
        $data[$sms . 'active_on_transit_to_customer'] = \request($sms . 'active_on_transit_to_customer', null);
        $data[$sms . 'on_transit_to_customer'] = \request($sms . 'on_transit_to_customer', null);
        $data[$sms . 'active_delivered_completed'] = \request($sms . 'active_delivered_completed', null);
        $data[$sms . 'delivered_completed'] = \request($sms . 'delivered_completed', null);
        $data[$sms . 'active_adjustment'] = \request($sms . 'active_adjustment', null);
        $data[$sms . 'adjustment'] = \request($sms . 'adjustment', null);
        $data[$sms . 'active_canceled_by_seller'] = \request($sms . 'active_canceled_by_seller', null);
        $data[$sms . 'canceled_by_seller'] = \request($sms . 'canceled_by_seller', null);
        $data[$sms . 'active_out_of_stock'] = \request($sms . 'active_out_of_stock', null);
        $data[$sms . 'out_of_stock'] = \request($sms . 'out_of_stock', null);
        $data[$sms . 'active_refunded'] = \request($sms . 'active_refunded', null);
        $data[$sms . 'refunded'] = \request($sms . 'refunded', null);

        Setting::save_settings($data);
        Cache::forget('settings'); // remove setting cache

        return redirect()->back()->withFlashSuccess('Message Updated Successfully');
    }


    public function airShippingStore()
    {
        $shipping = request('shipping');
        $data['air_shipping_charges'] = json_encode($shipping);
        Setting::save_settings($data);
        Cache::forget('settings'); // remove setting cache

        return redirect()->back()->withFlashSuccess('Shipping Charges Updated Successfully');
    }


    public function cacheControl()
    {
        $path = storage_path('app/browsing/');
        $files = File::allFiles($path);
        // foreach ($files as $key => $file) {
        //   dd($file);
        // }
        return view('backend.content.settings.cache-control', compact('files'));
    }

    public function cacheClear()
    {
        $pathname = \request('pathname');
        if (File::exists($pathname)) {
            File::delete($pathname);
            return redirect()->back()->withFlashWarning('Browsing Cache Remove Successfully');
        }
        return redirect()->back()->withFlashDanger('Cache Type Not Found');
    }

    public function cacheClearAll()
    {
        $path = storage_path('app/browsing/');
        File::cleanDirectory($path);

        return redirect()->back()->withFlashSuccess('Browsing Cache Remove Successfully');
    }


    public function manageSections()
    {
        return view('backend.content.settings.manage-sections.index');
    }

    public function manageSectionsStore()
    {
        $data = \request()->all();
        unset($data['_token']);

        if (\request()->hasFile('section_one_title_image')) {
            $data['section_one_title_image'] = store_picture(\request()->file('section_one_title_image'), 'setting');
        }

        // if (isset($data['section_super_deals_timer'])) {
        //     if ($data['section_super_deals_timer'] != null) {
        //         $data['section_super_deals_timer'] = strtotime($data['section_super_deals_timer']);
        //         $data['section_super_deals_timer'] = date('d-m-Y H:i:s', $data['section_super_deals_timer']);
        //     }
        // }

        Setting::save_settings($data);
        return redirect()->back()->withFlashSuccess('Section Updated  Successfully');
    }


    public function bannerRight()
    {
        return view('backend.content.settings.banner-right');
    }


    public function bannerRightStore()
    {
        $data = request()->only('top_image_link', 'bottom_image_link');

        $rightBanner = json_decode(get_setting('banner_right_images'));

        if (\request()->hasFile('top_image')) {
            $data['top_image'] = store_picture(\request()->file('top_image'), 'setting/banner-right');
        } else {
            $data['top_image'] = $rightBanner->top_image ?? null;
        }
        if (\request()->hasFile('bottom_image')) {
            $data['bottom_image'] = store_picture(\request()->file('bottom_image'), 'setting/banner-right');
        } else {
            $data['bottom_image'] = $rightBanner->bottom_image ?? null;
        }

        Setting::save_settings(['banner_right_images' => json_encode($data)]);

        return redirect()->back()->withFlashSuccess('Banner Right Image Set Successfully');
    }


    public function topNoticeCreate()
    {
        return view('backend.content.settings.top-notice');
    }

    public function topNoticeStore()
    {
        $active = request('top_notice_text_active');
        $top_notice_text = request('top_notice_text');

        $data['top_notice_text_active'] = null;
        if ($active) {
            $data['top_notice_text_active'] = $active;
        }
        $data['top_notice_text'] = $top_notice_text;

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Top Notice Updated  Successfully');
    }

    public function createImageLoader()
    {
        return view('backend.content.settings.image-loader-setting');
    }

    public function storeImageLoader()
    {
        $data = [];
        if (\request()->hasFile('banner_image_loader')) {
            $data['banner_image_loader'] = store_picture(\request()->file('banner_image_loader'), 'setting/loader');
        }

        if (\request()->hasFile('category_image_loader')) {
            $data['category_image_loader'] = store_picture(\request()->file('category_image_loader'), 'setting/loader');
        }

        if (\request()->hasFile('sub_category_image_loader')) {
            $data['sub_category_image_loader'] = store_picture(\request()->file('sub_category_image_loader'), 'setting/loader');
        }

        if (\request()->hasFile('product_image_loader')) {
            $data['product_image_loader'] = store_picture(\request()->file('product_image_loader'), 'setting/loader');
        }

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Loading Image Store Successfully');
    }


    public function shortMessageStore()
    {
        $data = \request()->only(['approx_weight_message', 'china_local_delivery_message', 'china_to_bd_bottom_message', 'order_summary_bottom_message', 'payment_summary_bottom_message']);
        Setting::save_settings($data);
        return redirect()->back()->withFlashSuccess('Short Message\'s Updated  Successfully');
    }

    public function manageProductPageCards()
    {
        return view('backend.content.settings.manage-product-page-cards.index');
    }

    public function manageProductPageCardsStore()
    {
        $data = \request()->all();
        unset($data['_token']);

        if (\request()->hasFile('card_one_image')) {
            $data['card_one_image'] = store_picture(\request()->file('card_one_image'), 'setting');
        }

        if (\request()->hasFile('card_two_image')) {
            $data['card_two_image'] = store_picture(\request()->file('card_two_image'), 'setting');
        }

        if (\request()->hasFile('card_three_image')) {
            $data['card_three_image'] = store_picture(\request()->file('card_three_image'), 'setting');
        }

        Setting::save_settings($data);
        return redirect()->back()->withFlashSuccess('Product Page Card Updated Successfully');
    }

    public function manageHomepageCards()
    {
        return view('backend.content.settings.manage-homepage-cards.index');
    }

    public function manageHomepageCardsStore()
    {
        $data = \request()->all();

        if (request()->has('rate')) {
            $rate = request('rate');
            unset($data['rate']);
        }

        unset($data['_token']);
        $rate = get_setting('increase_rate', 20);

        if (\request()->hasFile('hp_card_one_image')) {
            $data['hp_card_one_image'] = store_picture(\request()->file('hp_card_one_image'), 'setting');
        }

        if (\request()->hasFile('hp_card_two_image')) {
            $data['hp_card_two_image'] = store_picture(\request()->file('hp_card_two_image'), 'setting');
        }

        if (\request()->hasFile('hp_card_three_image')) {
            $data['hp_card_three_image'] = store_picture(\request()->file('hp_card_three_image'), 'setting');
        }

        if (\request()->hasFile('hp_card_four_image')) {
            $data['hp_card_four_image'] = store_picture(\request()->file('hp_card_four_image'), 'setting');
        }

        if (\request()->hasFile('hp_card_five_image')) {
            $data['hp_card_five_image'] = store_picture(\request()->file('hp_card_five_image'), 'setting');
        }

        if (isset($data['hp_card_five_product_one_id'])) {
            if ($data['hp_card_five_product_one_id'] != null) {
                $exclusive_data = getSaleOfferProducts($data['hp_card_five_product_one_id'], $rate);
                $data['hp_card_five_product_one_image'] = $exclusive_data['image'];
                $data['hp_card_five_product_one_price'] = $exclusive_data['price'];
            }
        }

        if (isset($data['hp_card_five_product_two_id'])) {
            if ($data['hp_card_five_product_two_id'] != null) {
                $exclusive_data = getSaleOfferProducts($data['hp_card_five_product_two_id'], $rate);
                $data['hp_card_five_product_two_image'] = $exclusive_data['image'];
                $data['hp_card_five_product_two_price'] = $exclusive_data['price'];
            }
        }

        if (isset($data['hp_card_five_product_three_id'])) {
            if ($data['hp_card_five_product_three_id'] != null) {
                $exclusive_data = getSaleOfferProducts($data['hp_card_five_product_three_id'], $rate);
                $data['hp_card_five_product_three_image'] = $exclusive_data['image'];
                $data['hp_card_five_product_three_price'] = $exclusive_data['price'];
            }
        }

        if (isset($data['hp_card_five_product_four_id'])) {
            if ($data['hp_card_five_product_four_id'] != null) {
                $exclusive_data = getSaleOfferProducts($data['hp_card_five_product_four_id'], $rate);
                $data['hp_card_five_product_four_image'] = $exclusive_data['image'];
                $data['hp_card_five_product_four_price'] = $exclusive_data['price'];
            }
        }

        if (isset($data['hp_card_five_product_five_id'])) {
            if ($data['hp_card_five_product_five_id'] != null) {
                $exclusive_data = getSaleOfferProducts($data['hp_card_five_product_five_id'], $rate);
                $data['hp_card_five_product_five_image'] = $exclusive_data['image'];
                $data['hp_card_five_product_five_price'] = $exclusive_data['price'];
            }
        }

        if (isset($data['hp_card_five_product_six_id'])) {
            if ($data['hp_card_five_product_six_id'] != null) {
                $exclusive_data = getSaleOfferProducts($data['hp_card_five_product_six_id'], $rate);
                $data['hp_card_five_product_six_image'] = $exclusive_data['image'];
                $data['hp_card_five_product_six_price'] = $exclusive_data['price'];
            }
        }

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Homepage Card Updated Successfully');
    }

    public function checkoutDiscounts()
    {
        return view('backend.content.settings.checkout-discounts');
    }


    public function checkoutDiscountsStore()
    {
        $data = \request()->all();
        unset($data['_token']);

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Checkout Discount Updated Successfully');
    }

    public function footerBrandSettings()
    {
        return view('backend.content.settings.footer-brands');
    }

    public function footerBrandSettingsStore()
    {
        $data = \request()->all();
        unset($data['_token']);

        if (\request()->hasFile('popup_banner')) {
            $data['popup_banner'] = store_picture(\request()->file('popup_banner'), 'setting/loader');
        }

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Pop-up Banner Updated Successfully');
    }

    public function paymentQrCodeSettings()
    {
        return view('backend.content.settings.paymentQrCodes');
    }

    public function paymentQrCodeSettingsStore()
    {
        $data = \request()->all();
        unset($data['_token']);

        if (\request()->hasFile('qr_code_bkash')) {
            $data['qr_code_bkash'] = store_picture(\request()->file('qr_code_bkash'), 'setting/loader');
        }

        if (\request()->hasFile('qr_code_nagad')) {
            $data['qr_code_nagad'] = store_picture(\request()->file('qr_code_nagad'), 'setting/loader');
        }

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Payment QR Codes Updated Successfully');
    }

    public function manageFeaturedCategories()
    {
        return view('backend.content.settings.manage-featured-categories.index');
    }

    public function manageFeaturedCategoriesStore()
    {
        $data = \request()->all();
        unset($data['_token']);

        if (\request()->hasFile('hp_cat_feat_banner')) {
            $data['hp_cat_feat_banner'] = store_picture(\request()->file('hp_cat_feat_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_feat_section_one_banner')) {
            $data['hp_cat_feat_section_one_banner'] = store_picture(\request()->file('hp_cat_feat_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_feat_section_two_banner')) {
            $data['hp_cat_feat_section_two_banner'] = store_picture(\request()->file('hp_cat_feat_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_feat_section_three_banner')) {
            $data['hp_cat_feat_section_three_banner'] = store_picture(\request()->file('hp_cat_feat_section_three_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_one_section_one_banner')) {
            $data['hp_cat_one_section_one_banner'] = store_picture(\request()->file('hp_cat_one_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_one_section_two_banner')) {
            $data['hp_cat_one_section_two_banner'] = store_picture(\request()->file('hp_cat_one_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_one_section_three_banner')) {
            $data['hp_cat_one_section_three_banner'] = store_picture(\request()->file('hp_cat_one_section_three_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_two_section_one_banner')) {
            $data['hp_cat_two_section_one_banner'] = store_picture(\request()->file('hp_cat_two_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_two_section_two_banner')) {
            $data['hp_cat_two_section_two_banner'] = store_picture(\request()->file('hp_cat_two_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_two_section_three_banner')) {
            $data['hp_cat_two_section_three_banner'] = store_picture(\request()->file('hp_cat_two_section_three_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_three_section_one_banner')) {
            $data['hp_cat_three_section_one_banner'] = store_picture(\request()->file('hp_cat_three_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_three_section_two_banner')) {
            $data['hp_cat_three_section_two_banner'] = store_picture(\request()->file('hp_cat_three_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_three_section_three_banner')) {
            $data['hp_cat_three_section_three_banner'] = store_picture(\request()->file('hp_cat_three_section_three_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_four_section_one_banner')) {
            $data['hp_cat_four_section_one_banner'] = store_picture(\request()->file('hp_cat_four_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_four_section_two_banner')) {
            $data['hp_cat_four_section_two_banner'] = store_picture(\request()->file('hp_cat_four_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_four_section_three_banner')) {
            $data['hp_cat_four_section_three_banner'] = store_picture(\request()->file('hp_cat_four_section_three_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_five_section_one_banner')) {
            $data['hp_cat_five_section_one_banner'] = store_picture(\request()->file('hp_cat_five_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_five_section_two_banner')) {
            $data['hp_cat_five_section_two_banner'] = store_picture(\request()->file('hp_cat_five_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_five_section_three_banner')) {
            $data['hp_cat_five_section_three_banner'] = store_picture(\request()->file('hp_cat_five_section_three_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_six_section_one_banner')) {
            $data['hp_cat_six_section_one_banner'] = store_picture(\request()->file('hp_cat_six_section_one_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_six_section_two_banner')) {
            $data['hp_cat_six_section_two_banner'] = store_picture(\request()->file('hp_cat_six_section_two_banner'), 'setting');
        }

        if (\request()->hasFile('hp_cat_six_section_three_banner')) {
            $data['hp_cat_six_section_three_banner'] = store_picture(\request()->file('hp_cat_six_section_three_banner'), 'setting');
        }

        Setting::save_settings($data);
        return redirect()->back()->withFlashSuccess('Homepage Card Updated Successfully');
    }

    public function cacheSetting()
    {
        $data = \request()->all();
        unset($data['_token']);

        Setting::save_settings($data);

        return redirect()->back()->withFlashSuccess('Cache Settings Updated Successfully');
    }
}
