<?php

namespace App\Http\Controllers\Coupon;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\CouponStoreRequest;
use App\Http\Requests\Coupon\CouponUpdateRequest;
use App\Http\Resources\Coupon\CouponResource;
use App\Models\Coupon;
use Exception;

use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showOne;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $coupons = Coupon::where('status', Coupon::ACTIVE_COUPON)->get();

            return showAll(CouponResource::collection($coupons), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $coupon = Coupon::create($data);

            return showOne(new CouponResource($coupon), 201);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        try {
            return showOne(new CouponResource($coupon), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        try {
            $coupon = $coupon->fill($data);
            $coupon->save();

            return showOne(new CouponResource($coupon), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();

            return showOne(new CouponResource($coupon), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
