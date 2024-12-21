<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $user = $this->users()->first();
        return [
            'id' => $this->id,
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->textGender,
            'phone' => $user->phone,
            'email' => $user->email,
            'identity_number' => $this->identity_number,
            'type' => $this->textType,
            'address' => $user->address,
            'avatar' => $user->avatar,
            'password' => $user->password,
            'is_activated' => $user->is_activated,
            'activated_at' => $user->activated_at,
            'deleted_at' => $user->deleted_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
