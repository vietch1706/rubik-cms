<?php

namespace App\Http\Controllers\Users;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\EmployeeRequest;
use App\Models\Users\Employees;
use App\Models\Users\Users;
use App\Schema\CustomerSchema;
use App\Schema\EmployeeSchema;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function back;
use function redirect;
use function response;
use function time;
use function view;

class EmployeesController extends Controller
{
    private Employees $employees;
    private Users $users;
    public const PAGE_LIMIT = 15;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $employees = $this->employees->whereHas('users', function ($query) {
            return $query->whereNot('deleted_at', '!=', null);
        })->paginate(self::PAGE_LIMIT);
        foreach ($employees as $key => $employee) {
            $employeeSchema = new EmployeeSchema($employee);
            $employees[$key] = $employeeSchema->convertData();
        }
        return view('users.employees.list', [
            'employees' => $employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        //
        $user = new $this->users;
        $employee = new $this->employees;
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
            $employee->type = $request->input('salary');
            $employee->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('employees')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $employees = $this->employees->find($id);
        $employeeSchema = new EmployeeSchema($employees);
        return view('users.employees.edit', [
            'employees' => $employeeSchema->convertData(),
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
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
                return redirect()->route('employees')->with('success', 'Update Successfully!');
            }
            return back()->with('success', 'Update Successfully!');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $ids = $request->ids;
        Users::whereIn('id', $ids)->update(['deleted_at' => Carbon::now()]);
        return response()->json(
            ["success" => 'Users have been deleted']
        );
    }
}
