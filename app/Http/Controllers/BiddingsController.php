<?php

namespace App\Http\Controllers;

use Exception;
use App\Events\BidCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AutoBidConfig;
use App\Actions\MakeBidAction;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MakeBidRequest;
use App\Actions\ToggleAutoBidAction;
use App\Http\Requests\ToggleAutoBidRequest;

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
            logger($ex);
            return response()->json([
                'message' => $ex->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Save auto bidding configs
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveConfigs(Request $request): JsonResponse
    {
        $amount = (float) $request->max_bid_amount;

        if (!$amount) {
            return response()->json([
                'message' => 'The Maximum auto bidding parameter must be a valid amount'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $config = auth()->user()->autoBidConfig;

        if (!$config) $config = new AutoBidConfig;

        $config->user_id = auth()->user()->id;
        $config->max_bid_amount = $amount;
        $config->save();

        $user = auth()->user()->load('autoBidConfig');

        return response()->json([
            'message' => 'The Maximum auto bidding parameter has been set',
            'user' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Toogle auto bid
     *
     * @param \App\Http\Requests\ToggleAutoBidRequest $request
     * @param \App\Actions\ToggleAutoBidAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleAutoBidding(ToggleAutoBidRequest $request, ToggleAutoBidAction $action): JsonResponse
    {
        $action->execute([
            'user_id' => auth()->user()->id,
            'item_id' => $request->item_id,
            'auto_bidding' => $request->auto_bidding
        ]);

        return response()->json(['message' => 'Saved'], Response::HTTP_OK);
    }
}
