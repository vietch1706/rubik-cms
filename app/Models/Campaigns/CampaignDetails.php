<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignDetails extends Model
{
    use HasFactory;

    public const TYPE_DISCOUNT = 0;
    public const TYPE_BUNDLE = 1;
    public $timestamps = true;
    protected $table = 'campaign_details';
    protected $fillable = [
        'product_id',
        'discount_percent',
        'bundle_product_id',
    ];
    protected $attributes = [
        'type' => self::TYPE_DISCOUNT,
    ];

    public function getTypeOptions()
    {
        return [
            self::TYPE_DISCOUNT => 'Discount',
            self::TYPE_BUNDLE => 'Bundle',
        ];
    }
}
