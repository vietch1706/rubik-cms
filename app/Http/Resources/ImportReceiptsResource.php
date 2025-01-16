<?php

namespace App\Http\Resources;

use App\Models\Users\Users;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ImportReceiptsResource extends JsonResource
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
        $employeeFullname = Users::join('employees', 'users.id', '=', 'employees.user_id')
            ->select('employees.id as id', 'users.first_name', 'users.last_name')
            ->where('employees.id', $this->employee_id)
            ->get(['id', 'first_name', 'last_name'])
            ->pluck('fullName', 'id')
            ->toArray();
        return [
            'id' => $this->id,
            'order_number' => $this->order->order_no,
            'employee' => $employeeFullname,
            'date' => $this->date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
