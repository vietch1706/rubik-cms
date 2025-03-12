<?php

namespace App\Http\Controllers\Users;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CustomerRequest;
use App\Http\Resources\UsersResource;
use App\Models\Users\Customers;
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

class CustomersController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Customers $customers;
    private Users $users;

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
     * @return Response
     */
    public function index()
    {
        //
        $customers = $this->customers
            ->whereHas('user', function ($query) {
                return $query->whereNull('deleted_at');
            })
            ->paginate(self::PAGE_LIMIT);
        return view('users.customers.list', [
            'customers' => UsersResource::collection($customers)->toArray(request()),
            'link' => $customers->links(),
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
        return view('users.customers.create', [
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
            'types' => $this->customers->getTypeOptions(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $customer = new UsersResource($this->customers->find($id));
        return $customer->toArray(request());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Users\CustomerRequest $request
     * @return Response
     */
    public function store(CustomerRequest $request)
    {
        //
        $user = new $this->users;
        $customer = new $this->customers;
        DB::beginTransaction();
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
            DB::commit();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('customers')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(['error' => $e->getMessage()]);
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
        $customer = new UsersResource($this->customers->find($id));
        return view('users.customers.edit', [
            'customer' => $customer->toArray(request()),
            'genders' => $this->users->getGenderOptions(),
            'isActivateds' => $this->users->getIsActivatedOptions(),
            'types' => $this->customers->getTypeOptions(),
        ]);
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
        $this->customers->whereIn('id', $ids)->each(function ($customer) {
            $customer->user()->delete();
        });
        return response()->json(
            ["success" => 'Users have been deleted']
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CustomerRequest $request, $id)
    {
        //
        $customer = $this->customers->find($id);
        $user = $customer->user()->first();
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
                return redirect()->route('customers')->with('success', 'Updated Successfully!');
            }
            return back()->with('success', 'Updated Successfully!');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $customers = $this->customers->whereHas('user', function ($query) use ($search) {
            return $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%');
        })
            ->orWhere('identity_number', 'like', '%' . $search . '%')
            ->paginate(self::PAGE_LIMIT);
        if ($customers->count() > 0) {
            return response()->json([
                'customers' => view('users.customers.search', [
                    'customers' => UsersResource::collection($customers)->toArray(request()),
                ])->render(),
                'pagination' => $customers->links()->render()
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
