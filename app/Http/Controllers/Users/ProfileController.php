<?php

namespace App\Http\Controllers\Users;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\UsersResource;
use App\Models\Users\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function auth;
use function back;
use function dd;
use function request;
use function view;

class ProfileController extends Controller
{
    private Users $users;

    public function __construct(
        Users $user,
    )
    {
        $this->users = $user;
    }

    public function edit()
    {
        //
        $user = auth()->user();
        $userId = $user->id;
        if ($user->role_id == $this->users::ROLE_EMPLOYEE) {
//            $employeeSchema = new EmployeeSchema($user->employees()->first());
//            $user = $employeeSchema->convertData();
            $user = new UsersResource($user->employee()->first());
        }
        $user = new UsersResource($user);
        return view('users.profile.edit', [
            'user' => $user->toArray(request()),
            'genders' => $this->users->getGenderOptions(),
        ]);
    }

    public function changePassword(UserRequest $request)
    {
        dd($request->user());

    }

    public function changePasswordView()
    {
        return view('users.profile.changePassword');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $user = $request->user();
        try {
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->gender = $request->input('gender');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            if ($request->file('avatar')) {
                $user->avatar = Helper::setStoragePath('avatars', $request->file('avatar'));
            }
            if ($user->role_id == $this->users::ROLE_EMPLOYEE) {
                $employee = $user->employees()->first();
                $employee->salary = $request->input('salary');
                $employee->save();
            }
            $user->save();
            return back()->with('success', 'Updated Successfully!');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
