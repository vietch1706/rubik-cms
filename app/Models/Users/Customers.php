<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    public const TYPE_REGULAR = 0;
    public const TYPE_LOYAL = 1;

    protected $table = 'customers';
    public $timestamps = true;
    protected $fillable = [
        'identity_number',
        'type'
    ];
    protected $attributes = [
        'type' => 0,
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function getTypeOptions()
    {
        return [
            self::TYPE_REGULAR => 'Regular',
            self::TYPE_LOYAL => 'Loyal',
        ];
    }

    public function textType(): Attribute
    {
        return new Attribute(
            get: fn($value, $attribute) => match ($attribute['type']) {
                self::TYPE_LOYAL => 'Loyal',
                self::TYPE_REGULAR => 'Regular',
            },
            set: fn($value) => match ($value) {
                'Loyal' => self::TYPE_LOYAL,
                'Regular' => self::TYPE_REGULAR,
            }
        );
    }
}
