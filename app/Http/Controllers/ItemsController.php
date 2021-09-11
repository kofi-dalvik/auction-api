<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Actions\ListItemAction;

class ItemsController extends Controller
{
    /**
     * Get items listings
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Actions\ListItemAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListItemAction $action): JsonResponse
    {
        $params = $request->only(['sort_price', 'keyword']);

        return response()->json($action->execute($params), Response::HTTP_OK);
    }

    /**
     * Show given item
     *
     * @param mixed $id
     * @param \App\Actions\ShowItemAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, ShowItemAction $action): JsonResponse
    {
        try {
            $item = $action->execute($id);

            return response()->json($item, Response::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
