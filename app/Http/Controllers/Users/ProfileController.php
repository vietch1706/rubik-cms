<?php

namespace App\Http\Controllers\Users;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Models\Users\Users;
use App\Schema\EmployeeSchema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function auth;
use function back;
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
        $role = $user->textRole;
        $userId = $user->id;
        if ($user->role_id == $this->users::ROLE_EMPLOYEE) {
            $employeeSchema = new EmployeeSchema($user->employees()->first());
            $user = $employeeSchema->convertData();
        }
        return view('users.profile.edit', [
            'userId' => $userId,
            'user' => $user,
            'genders' => $this->users->getGenderOptions(),
            'role' => $role,
            'isActivateds' => $this->users->getIsActivatedOptions(),
        ]);
    }

    public function changePassword(UserRequest $request, $id)
    {
        dd($request->all());
        $user = $this->users->find($id);
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
        dd($request->all());
        $user = $this->users->find($id);
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
