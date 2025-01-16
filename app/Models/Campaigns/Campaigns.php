<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;

    public const TYPE_DISCOUNT = 0;
    public const TYPE_BUNDLE = 1;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public $timestamps = true;
    protected $table = 'campaigns';
    protected $fillable = [
        'name',
        'slug',
        'type_value',
        'start_date',
        'end_date',
    ];
    protected $attributes = [
        'type' => self::TYPE_DISCOUNT,
        'status' => self::STATUS_INACTIVE,
    ];

    public function getTypeOptions()
    {
        return [
            self::TYPE_DISCOUNT => 'Discount',
            self::TYPE_BUNDLE => 'Bundle',
        ];
    }

    public function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    public function textType(): Attribute
    {
        return new Attribute(
            get: fn($value, $attribute) => match ($attribute['type']) {
                self::TYPE_DISCOUNT => 'Discount',
                self::TYPE_BUNDLE => 'Bundle',
            },
        );
    }
}
