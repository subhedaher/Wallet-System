<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentCategoryResource;
use App\Models\PaymentCategory;
use App\Models\PaymentProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PaymentCategoryController extends Controller
{

    function __construct()
    {
        $this->authorizeResource(PaymentCategory::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paymentCategories = PaymentCategory::withCount('payment_providers')->get();
        if ($request->expectsJson()) {
            $recourse = PaymentCategoryResource::collection($paymentCategories);
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $recourse
            ]);
        }
        return view('cms.paymentCategories.index', ['paymentCategories' => $paymentCategories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.paymentCategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:20|unique:payment_categories,name',
            'image' => 'required|image|mimes:jpg,png',
        ]);

        if (!$validator->fails()) {
            $paymentCategory = new PaymentCategory();
            $paymentCategory->name = $request->input('name');
            $imageFile = $request->file('image');
            $name = $imageFile->store('paymentCategories', ['disk', 'public']);
            $paymentCategory->image = $name;
            $paymentCategory->admin_id = auth('admin')->user()->id;
            $isSaved = $paymentCategory->save();
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Payment Category Added Successfully" : "Payment Category Added Failed!"
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
    public function show(PaymentCategory $paymentCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentCategory $paymentCategory)
    {
        return view('cms.paymentCategories.edit', ['paymentCategory' => $paymentCategory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentCategory $paymentCategory)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|max:20|unique:payment_categories,name,' . $paymentCategory->id,
            'image' => 'nullable|image|mimes:jpg,png',
        ]);

        if (!$validator->fails()) {
            $paymentCategory->name = $request->input('name');
            if ($request->hasFile('image')) {
                Storage::delete($paymentCategory->image);
                $imageFile = $request->file('image');
                $name = $imageFile->store('paymentCategories', ['disk' => 'public']);
                $paymentCategory->image = $name;
            }
            $isUpdated = $paymentCategory->save();
            return response()->json([
                'status' => $isUpdated,
                'message' => $isUpdated ? "Payment Category Updated Successfully" : "Payment Category Updated Failed!"
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
    public function destroy(PaymentCategory $paymentCategory)
    {
        $getPaymentProviders = PaymentProvider::withTrashed()->count();
        if ($getPaymentProviders > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot be Deleted'
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $isDeleted = $paymentCategory->delete();
            return response()->json([
                'status' => $isDeleted,
                'message' => $isDeleted ? "Payment Category Deleted Successfully" : "Payment Category Deleted Failed!"
            ], $isDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        }
    }

    public function showDeleted()
    {
        $paymentCategoriesDeleted = PaymentCategory::with('payment_providers')->onlyTrashed()->get();
        return view('cms.paymentCategories.deleted', ['paymentCategoriesDeleted' => $paymentCategoriesDeleted]);
    }

    public function restore($id)
    {
        $this->authorize('restore', PaymentCategory::withTrashed()->findOrFail($id));
        $paymentCategory = PaymentCategory::onlyTrashed()->findOrFail($id);
        $isUpdated = $paymentCategory->restore();
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "Payment Category Restore Successfully" : "Payment Category Restore Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function forceDelete($id)
    {
        $this->authorize('forceDelete', PaymentCategory::withTrashed()->findOrFail($id));
        $paymentCategory = PaymentCategory::onlyTrashed()->findOrFail($id);
        $isUpdated = $paymentCategory->forceDelete();
        if ($isUpdated) {
            Storage::delete($paymentCategory->image);
        }
        return response()->json([
            'status' => $isUpdated,
            'message' => $isUpdated ? "payment Category Deleted Successfully" : "payment Category Deleted Failed!"
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}