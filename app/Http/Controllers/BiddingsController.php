<?php

namespace App\Http\Controllers;

use Exception;
use App\Events\BidCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actions\MakeBidAction;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MakeBidRequest;

class BiddingsController extends Controller
{
    /**
     * Make bid
     *
     * @param \App\Http\Requests\MakeBidRequest $request
     * @param \App\Actions\MakeBidAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MakeBidRequest $request, MakeBidAction $action): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id;

            $bidding = $action->execute($data);

            BidCreated::dispatch($bidding);

            return response()->json($bidding, Response::HTTP_CREATED);
        } catch (Exception $ex) {
            // logger($ex);
            return response()->json([
                'message' => $ex->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
