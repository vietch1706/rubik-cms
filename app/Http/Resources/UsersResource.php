<?php

namespace App\Http\Resources;

use App\Models\Users\Users;
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
        parent::toArray($request);
        if (!$this->user) {
            $user = $this;
        } else {
            $user = $this->user;
        }
        $convertUser = [
            'id' => $this->id,
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->textGender,
            'phone' => $user->phone,
            'email' => $user->email,
            'salary' => $this->salary,
            'address' => $user->address,
            'avatar' => $user->avatar,
            'password' => $user->password,
            'is_activated' => $user->is_activated,
            'activated_at' => $user->activated_at,
            'deleted_at' => $user->deleted_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        switch ($user->role_id) {
            case Users::ROLE_ADMIN:
                break;
            case Users::ROLE_CUSTOMER:
                $convertUser['identity_number'] = $this->identity_number;
                $convertUser['type'] = $this->textType;
                break;
            case Users::ROLE_EMPLOYEE:
                $convertUser['salary'] = $this->salary;
                break;
            default:
        }
        return $convertUser;
    }
}
