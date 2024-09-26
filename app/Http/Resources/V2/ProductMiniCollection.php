<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $item = array();
                if($data->variant_product){
                    foreach ($data->stocks as $stockItem){
                            $item[] = $stockItem->variant;
                    }
                }
                $wholesale_product =
                    ($data->wholesale_product == 1) ? true : false;
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'discount' => "-" . discount_in_percentage($data) . "%",
                    // 'discount' => (double) $data->discount,
                    // 'discount_type' => $data->discount_type,
                    'stroked_price' => home_base_price($data),
                    'main_price' => home_discounted_base_price($data),
                    'rating' => (float) $data->rating,
                    'sales' => (int) $data->num_of_sale,
                    'is_wholesale' => $wholesale_product,
                    'slug' => $data->slug,
                    'whatsapp_status' => 0,
                    'whatsapp_number' => $data->user->phone,
                    'variants'  => $item,
                    'min_qty'  => 1,
                    'max_qty'  => 0,
                    'top_label' => ($data->top_label)?$data->top_label:'',
                    'bottom_label' => ($data->bottom_label)?$data->bottom_label:'',
                    'links' => [
                        'details' => route('products.show', $data->id),
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
