<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class InvoicesResource extends JsonResource
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
//        $employeeFullname = Users::join('employees', 'users.id', '=', 'employees.user_id')
//            ->select('employees.id as id', 'users.first_name', 'users.last_name')
//            ->where('employees.id', $this->employee_id)
//            ->get(['id', 'first_name', 'last_name'])
//            ->pluck('fullName', 'id')
//            ->toArray();
//        $customerFullname = Users::join('customers', 'users.id', '=', 'customers.user_id')
//            ->select('customers.id as id', 'users.first_name', 'users.last_name')
//            ->where('customers.id', $this->customer_id)
//            ->get(['id', 'first_name', 'last_name'])
//            ->pluck('fullName', 'id')
//            ->toArray();
        $employeeFullname = Helper::getUsersFullName('employees', $this->employee_id);
        $customerFullname = Helper::getUsersFullName('customers', $this->customer_id);
        return [
            'id' => $this->id,
            'employee' => $employeeFullname,
            'customer' => $customerFullname,
            'date' => $this->date,
            'status' => $this->status,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
