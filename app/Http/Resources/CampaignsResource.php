<?php

namespace App\Http\Resources;

use App\Models\Campaigns\Campaigns;
use App\Models\Catalogs\Products;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CampaignsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        parent::toArray($request);
        $campains = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->textType,
            'discount_value' => '',
            'bundle_value' => '',
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if ($this->type == Campaigns::TYPE_DISCOUNT) {
            $campains['discount_value'] = $this->type_value;
        } else {
            $campains['bundle_value'] = Products::where('id', $this->type_value)->pluck('name', 'sku');
        }
        return $campains;
    }
}
