<?php

namespace App\Http\Controllers\Users;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\EmployeeRequest;
use App\Http\Resources\UsersResource;
use App\Models\Users\Employees;
use App\Models\Users\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function back;
use function redirect;
use function request;
use function response;
use function view;

class EmployeesController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Employees $employees;
    private Users $users;

    public function __construct(
        Employees $employee,
        Users     $user
    )
    {
        $this->employees = $employee;
        $this->users = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $employees = $this->employees
            ->whereHas('user', function ($query) {
                return $query->whereNot('deleted_at', '!=', null);
            })
            ->paginate(self::PAGE_LIMIT);
        return view('users.employees.list', [
            'employees' => UsersResource::collection($employees)->toArray(request()),
            'link' => $employees->links(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('users.employees.create', [
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(EmployeeRequest $request)
    {
        //
        $user = new $this->users;
        $employee = new $this->employees;
        DB::beginTransaction();
        try {
            $user->role_id = $this->users::ROLE_EMPLOYEE;
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->gender = $request->input('gender');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->avatar = Helper::setStoragePath('avatars', $request->file('avatar'));
            $user->password = Hash::make($request->input('password'));
            $user->is_activated = $request->input('is_activated');
            $user->activated_at = $request->input('activated_at');
            $user->save();
            $employee->user_id = $user->id;
            $employee->salary = $request->input('salary');
            $employee->save();
            DB::commit();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('employees')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $employee = new UsersResource($this->employees->find($id));
        return view('users.employees.edit', [
            'employee' => $employee->toArray(request()),
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
        $employee = $this->employees->find($id);
        $user = $employee->users()->first();
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        try {
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->gender = $request->input('gender');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->avatar = Helper::setStoragePath('avatars', $request->file('avatar'));
            $user->is_activated = $request->input('is_activated');
            $user->activated_at = $request->input('activated_at');
            $user->save();
            $employee->salary = $request->input('salary');
            $employee->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('employees')->with('success', 'Updated Successfully!');
            }
            return back()->with('success', 'Updated Successfully!');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        //
        $ids = $request->ids;
        $this->employees->whereIn('id', $ids)->each(function ($employee) {
            $employee->users()->delete();
        });
        return response()->json(
            ["success" => 'Users have been deleted']
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $employees = $this->employees->whereHas('user', function ($query) use ($search) {
            return $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%');
        })
            ->paginate(self::PAGE_LIMIT);
        if ($employees->count() > 0) {
            return response()->json([
                'employees' => view('users.employees.search', [
                    'employees' => UsersResource::collection($employees)->toArray(request()),
                ])->render(),
                'pagination' => $employees->links()->render()
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
