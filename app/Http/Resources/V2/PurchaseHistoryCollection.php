<?php

namespace App\Http\Resources\V2;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseHistoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $pickup_point = null;
                if ($data->shipping_type == 'pickup_point' && $data->pickup_point_id) {
                    $pickup_point = $data->pickup_point;
                }

                return [
                    'id' => $data->id,
                    'code' => $data->code,
                    'user_id' => (int) $data->user_id,
                    'shipping_address' => json_decode($data->shipping_address),
                    'payment_type' => ucwords(str_replace('_', ' ',translate($data->payment_type))),
                    'pickup_point' => $pickup_point??"",
                    'shipping_type' => $data->shipping_type,
                    'shipping_type_string' => $data->shipping_type != null ? ucwords(str_replace('_', ' ', translate($data->shipping_type))) : "",
                    'payment_status' => $data->payment_status,
                    'payment_status_string' => ucwords(str_replace('_', ' ', translate($data->payment_status))),
                    'delivery_status' => $data->delivery_status,
                    'delivery_status_string' => $data->delivery_status == translate('pending') ? translate("Order Placed") : ucwords(str_replace('_', ' ',  translate($data->delivery_status))),
                    'grand_total' => format_price(convert_price($data->grand_total)),
                    'plane_grand_total' => $data->grand_total,
                    'coupon_discount' => format_price(convert_price($data->coupon_discount)),
                    'shipping_cost' => format_price(convert_price($data->orderDetails->sum('shipping_cost'))),
                    'subtotal' => format_price(convert_price($data->orderDetails->sum('price'))),
                    'tax' => format_price(convert_price($data->orderDetails->sum('tax'))),
                    'date' => Carbon::createFromTimestamp($data->date)->format('d-m-Y'),
                    'cancel_request' => $data->cancel_request == 1,
                    'manually_payable' => $data->manual_payment && $data->manual_payment_data == null,
                    
                    'cod_cost' => $data->cod_cost != null? format_price(($data->cod_cost)): "",
                    'estimated_delivery_date' => Carbon::parse($data->date)->format('d-m-Y'),
                    'wallet_discount' => "",
                    'cancel_order' => true,
                    'cancel_order_label' => $data->return_request_status != null ? ucwords($data->return_request_status) : ucwords('Cancel Order'),
                    'checkout_description' => ($data->checkout_description)?$data->checkout_description:'',
                    'checkout_date_time' => ($data->checkout_date_time)?$data->checkout_date_time:'',
                    'links' => [
                        'details' => ''
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
