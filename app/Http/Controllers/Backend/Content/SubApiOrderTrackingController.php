<?php
// FOR MAIN DOMAIN
namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\SubApiOrder;
use Illuminate\Http\Request;

class SubApiOrderTrackingController extends Controller
{
    public function update(Request $request)
    {
        SubApiOrder::updateOrCreate(
            [
                'domain' => $request->domain
            ],
            [
                'total_invoices' => $request->total_invoices,
                'total_orders' => $request->total_orders
            ]
        );

        return $this->success([
            'status' => 'success'
        ]);
    }
}
// FOR MAIN DOMAIN
