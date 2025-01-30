<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class BlogsResource extends JsonResource
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
        $category = null;
        if ($this->category_id) {
            $category = $this->categories()->pluck('name', 'id')->toArray();
        }
        $employeeFullname = Helper::getUsersFullName('employees', $this->employee_id);
//        $employeeFullname = Users::join('employees', 'users.id', '=', 'employees.user_id')
//            ->select('employees.id as id', 'users.first_name', 'users.last_name')
//            ->where('employees.id', $this->employee_id)
//            ->get(['id', 'first_name', 'last_name'])
//            ->pluck('fullName', 'id')
//            ->toArray();
        return [
            'id' => $this->id,
            'employee' => $employeeFullname,
            'title' => $this->title,
            'category' => $category,
            'slug' => $this->slug,
            'content' => $this->content,
            'date' => $this->date,
            'thumbnail' => $this->thumbnail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
