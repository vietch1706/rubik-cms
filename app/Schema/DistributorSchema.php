<?php

namespace App\Schema;

use App\Models\Catalogs\Distributors;

class DistributorSchema
{
    private Distributors $distributors;

    public function __construct(
        Distributors $distributor,
    )
    {
        $this->distributors = $distributor;

    }

    public function convertData()
    {
        return [
            'id' => $this->distributors->id,
            'name' => $this->distributors->name,
            'address' => $this->distributors->address,
            'country' => $this->distributors->country,
            'phone' => $this->distributors->phone,
            'email' => $this->distributors->email,
            'created_at' => $this->distributors->created_at,
            'updated_at' => $this->distributors->updated_at,
        ];
    }
}
