<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentProviderResource;
use App\Models\PaymentCategory;
use App\Models\PaymentProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PaymentProviderController extends Controller
{

    function __construct()
    {
        $this->authorizeResource(PaymentProvider::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paymentProviders = PaymentProvider::with('payment_category')->get();
        if ($request->expectsJson()) {
            $recourse = PaymentProviderResource::collection($paymentProviders);
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $recourse
            ]);
        }
        return view('cms.paymentProviders.index', ['paymentProviders' => $paymentProviders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentCategories = PaymentCategory::all();
        return view('cms.paymentProviders.create', ['paymentCategories' => $paymentCategories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:20|unique:payment_providers,name',
            'phoneNumber' => 'required|string|unique:payment_providers,phone_number|max:15',
            'paymentCategory' => 'required|string|exists:payment_categories,id',
            'image' => 'required|image|mimes:jpg,png',
        ]);

        if (!$validator->fails()) {
            $paymentProvider = new PaymentProvider();
            $paymentProvider->name = $request->input('name');
            $imageFile = $request->file('image');
            $name = $imageFile->store('paymentProviders', ['disk', 'public']);
            $paymentProvider->image = $name;
            $paymentProvider->phone_number = $request->input('phoneNumber');
            $paymentProvider->payment_category_id = $request->input('paymentCategory');
            $paymentProvider->admin_id = auth('admin')->user()->id;
            $isSaved = $paymentProvider->save();
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Payment Provider Added Successfully" : "Payment Provider Added Failed!"
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
    public function show(PaymentProvider $paymentProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentProvider $paymentProvider)
    {
        $paymentCategories = PaymentCategory::all();
        return view('cms.paymentProviders.edit', ['paymentProvider' => $paymentProvider, 'paymentCategories' => $paymentCategories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentProvider $paymentProvider)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:20|unique:payment_providers,name,' . $paymentProvider->id,
            'phoneNumber' => 'required|string|max:15|unique:payment_providers,phone_number,' . $paymentProvider->id,
            'paymentCategory' => 'required|string|exists:payment_categories,id',
            'image' => 'nullable|image|mimes:jpg,png',
        ]);

        if (!$validator->fails()) {
            $paymentProvider->name = $request->input('name');
            if ($request->hasFile('image')) {
                Storage::delete($paymentProvider->image);
                $imageFile = $request->file('image');
                $name = $imageFile->store('paymentProvider', ['disk' => 'public']);
                $paymentProvider->image = $name;
            }
            $paymentProvider->phone_number = $request->input('phoneNumber');
            $paymentProvider->payment_category_id = $request->input('paymentCategory');
            $isUpdated = $paymentProvider->save();
            return response()->json([
                'status' => $isUpdated,
                'message' => $isUpdated ? "Payment Provider Updated Successfully" : "Payment Provider Updated Failed!"
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
    public function destroy(PaymentProvider $paymentProvider)
    {
        $isDeleted = $paymentProvider->delete();
        return response()->json([
            'status' => $isDeleted,
            'message' => $isDeleted ? "Payment Provider Deleted Successfully" : "Payment Provider Deleted Failed!"
        ], $isDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function showDeleted()
    {
        $paymentProvidersDeleted = PaymentProvider::onlyTrashed()->get();
        return view('cms.paymentProviders.deleted', ['paymentProvidersDeleted' => $paymentProvidersDeleted]);
    }

    public function restore($id)
    {
        $this->authorize('restore', PaymentProvider::withTrashed()->findOrFail($id));
        $paymentProvider = PaymentProvider::onlyTrashed()->findOrFail($id);
        $isUpdated = $paymentProvider->restore();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Payment Provider Restore Successfully" : "Payment Provider Restore Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function forceDelete($id)
    {
        $this->authorize('forceDelete', PaymentProvider::onlyTrashed()->findOrFail($id));
        $paymentProvider = PaymentProvider::onlyTrashed()->findOrFail($id);
        $isUpdated = $paymentProvider->forceDelete();
        if ($isUpdated) {
            Storage::delete($paymentProvider->image);
        }
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "payment Provider Deleted Successfully" : "payment Provider Deleted Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
