<?php

namespace App\Http\Controllers;

use App\Models\ShippingPoint;
use App\Notifications\FrozenNotification;
use App\Notifications\WorkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class ShippingPointController extends Controller
{
    function __construct()
    {
        $this->authorizeResource(ShippingPoint::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingPoints = ShippingPoint::with('roles')->get();
        return view('cms.shippingPoints.index', ['shippingPoints' => $shippingPoints]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.shippingPoints.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:20|unique:shipping_points,full_name',
            'phoneNumber' => 'required|string|unique:shipping_points,phone_number|max:15',
            'address' => 'required|string|max:20',
            'email' => 'required|string|unique:shipping_points,email',
            'password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->symbols()
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised(), 'confirmed'
            ]
        ]);

        if (!$validator->fails()) {
            $shippingPoint = new ShippingPoint();
            $shippingPoint->full_name = $request->input('name');
            $shippingPoint->phone_number = $request->input('phoneNumber');
            $shippingPoint->address = $request->input('address');
            $shippingPoint->email = $request->input('email');
            $shippingPoint->password = Hash::make($request->input('password'));
            $shippingPoint->admin_id = auth('admin')->user()->id;
            $isSaved = $shippingPoint->save();
            if ($isSaved) {
                $role = Role::where('name', '=', 'Shipping-Point')->first();
                $shippingPoint->assignRole($role);
            }
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Shipping Point Added Successfully" : "Shipping Poin Added Failed!"
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
    public function show(ShippingPoint $shippingPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingPoint $shippingPoint)
    {
        return view('cms.shippingPoints.edit', ['shippingPoint' => $shippingPoint]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingPoint $shippingPoint)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:20|unique:shipping_points,full_name,' . $shippingPoint->id,
            'phoneNumber' => 'required|string|max:15|unique:shipping_points,phone_number,' . $shippingPoint->id,
            'address' => 'required|string|max:20',
            'email' => 'required|string|unique:shipping_points,email,' . $shippingPoint->id,
        ]);

        if (!$validator->fails()) {
            $shippingPoint->full_name = $request->input('name');
            $shippingPoint->phone_number = $request->input('phoneNumber');
            $shippingPoint->address = $request->input('address');
            if ($request->input('email') !== $shippingPoint->email) {
                $shippingPoint->email_verified_at = null;
                $shippingPoint->email = $request->input('email');
            }
            $isUpdated = $shippingPoint->save();
            return response()->json([
                'status' => $isUpdated,
                'message' => $isUpdated ? "Shipping Point Updated Successfully" : "Shipping Point Updated Failed!"
            ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
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
    public function destroy(ShippingPoint $shippingPoint)
    {
        $isDeleted = $shippingPoint->delete();
        return response()->json([
            'status' => $isDeleted,
            'message' => $isDeleted ? "Shipping Point Deleted Successfully" : "Shipping Point Deleted Failed!"
        ], $isDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function showDeleted()
    {
        $shippingPointsDeleted = ShippingPoint::onlyTrashed()->get();
        return view('cms.shippingPoints.deleted', ['shippingPointsDeleted' => $shippingPointsDeleted]);
    }

    public function restore($id)
    {
        $this->authorize('restore', ShippingPoint::withTrashed()->findOrFail($id));
        $shippingPoint = ShippingPoint::onlyTrashed()->findOrFail($id);
        $isUpdated = $shippingPoint->restore();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Shipping Point Restore Successfully" : "Shipping Point Restore Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function forceDelete($id)
    {
        $this->authorize('forceDelete', ShippingPoint::withTrashed()->findOrFail($id));
        $shippingPoint = ShippingPoint::onlyTrashed()->findOrFail($id);
        $isUpdated = $shippingPoint->forceDelete();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Shipping Point Deleted Successfully" : "Shipping Point Deleted Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function frozen($id)
    {
        $shippingPoint = ShippingPoint::findOrFail($id);
        if ($shippingPoint->status == 'f') {
            $shippingPoint->status = 'w';
            Notification::send($shippingPoint, new WorkNotification());
        } else {
            $shippingPoint->status = 'f';
            Notification::send($shippingPoint, new FrozenNotification());
        }
        $isUpdated = $shippingPoint->save();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Shipping Point Updated Successfully" : "Shipping Point Updated Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
