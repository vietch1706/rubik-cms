<?php

namespace App\Http\Controllers\Users;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CustomerRequest;
use App\Models\Users\Customers;
use App\Models\Users\Users;
use App\Schema\CustomerSchema;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function back;
use function redirect;
use function time;

class CustomersController extends Controller
{
    private Customers $customers;
    private Users $users;
    public const PAGE_LIMIT = 15;

    public function __construct(
        Customers $customer,
        Users     $user
    )
    {
        $this->customers = $customer;
        $this->users = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function index()
    {
        //
        $customers = $this->customers
            ->whereHas('users', function ($query) {
                return $query->whereNot('deleted_at', '!=', null);
            })
            ->paginate(self::PAGE_LIMIT);
        foreach ($customers as $key => $customer) {
            $customerSchema = new CustomerSchema($customer);
            $customers[$key] = $customerSchema->convertData();
        }
        return view('users.customers.list', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
        return view('users.customers.create', [
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
            'types' => $this->customers->getTypeOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Users\CustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        //
        $user = new $this->users;
        $customer = new $this->customers;
        try {
            $user->role_id = $this->users::ROLE_CUSTOMER;
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

            $customer->user_id = $user->id;
            $customer->identity_number = $request->input('identity_number');
            $customer->type = $request->input('type');
            $customer->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('customers')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (\Exception $e) {
            throw new \Exception(['error' => $e->getMessage()]);
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
        $customers = $this->customers->find($id);
        $customerSchema = new CustomerSchema($customers);
        return view('users.customers.edit', [
            'customers' => $customerSchema->convertData(),
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
            'types' => $this->customers->getTypeOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        //
        $customer = $this->customers->find($id);
        $user = $customer->users()->first();
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
            $customer->identity_number = $request->input('identity_number');
            $customer->type = $request->input('type');
            $customer->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('customers')->with('success', 'Update Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
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
