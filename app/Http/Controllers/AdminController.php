<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    function __construct()
    {
        // $this->authorizeResource(Admin::class, 'admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('roles')->get();
        return view('cms.admins.index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('guard_name', '=', 'admin')->get();
        return view('cms.admins.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'fullName' => 'required|string|max:20',
            'phoneNumber' => 'required|string|unique:admins,phone_number|max:15',
            'email' => 'required|string|unique:admins,email',
            'password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->symbols()
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised(), 'confirmed'
            ],
            'role' => 'required|exists:roles,id'
        ]);



        if (!$validator->fails()) {
            $admin = new Admin();
            $admin->full_name = $request->input('fullName');
            $admin->phone_number = $request->input('phoneNumber');
            $admin->email = $request->input('email');
            $admin->password = Hash::make($request->input('password'));
            $isSaved = $admin->save();
            if ($isSaved) {
                $admin->assignRole($request->input('role'));
            }
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Admin Added Successfully" : "Admin Added Failed!"
            ], $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $admin = Admin::where('id', '=', $admin->id)->with('roles')->first();
        $roles = Role::where('guard_name', '=', 'admin')->get();
        return view('cms.admins.edit', ['roles' => $roles, 'admin' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validator = Validator($request->all(), [
            'fullName' => 'required|string|max:20',
            'phoneNumber' => 'required|string|max:15|unique:admins,phone_number,' . $admin->id,
            'email' => 'required|string|unique:admins,email,' . $admin->id,
            'role' => 'required|exists:roles,id'
        ]);

        if (!$validator->fails()) {
            $admin->full_name = $request->input('fullName');
            $admin->phone_number = $request->input('phoneNumber');
            if ($request->input('email') !== $admin->email) {
                $admin->email_verified_at = null;
                $admin->email = $request->input('email');
            }
            $isSaved = $admin->save();
            if ($isSaved) {
                $admin->syncRoles($request->input('role'));
            }
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Admin Updated Successfully" : "Admin Updated Failed!"
            ], $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $isDeleted = $admin->delete();
        return response()->json([
            'status' => $isDeleted,
            'message' => $isDeleted ? "Admin Deleted Successfully" : "Admin Deleted Failed!"
        ], $isDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function showDeleted()
    {
        $adminsDeleted = Admin::onlyTrashed()->get();
        return view('cms.admins.deleted', ['adminsDeleted' => $adminsDeleted]);
    }

    public function restore($id)
    {
        $this->authorize('restore', Admin::withTrashed()->findOrFail($id));
        $admin = Admin::onlyTrashed()->findOrFail($id);
        $isUpdated = $admin->restore();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Admin Restore Successfully" : "Admin Restore Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function forceDelete($id)
    {
        $this->authorize('forceDelete', Admin::withTrashed()->findOrFail($id));
        $admin = Admin::onlyTrashed()->findOrFail($id);
        $isUpdated = $admin->forceDelete();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Admin Deleted Successfully" : "Admin Deleted Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}