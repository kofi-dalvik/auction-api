<?php

namespace App\Actions;

use DB;
use Exception;
use App\Models\User;
use App\Models\Item;
use App\Models\Bidding;
use App\Models\AutoBidConfig;
use App\Models\AutoBidActivation;

class AutoBidAction
{
    public function execute(int $item_id): void
    {
        $item = Item::isActive()->find($item_id);

        if (!$item) return;

        $excluded_users = [];

        do {
            $activations = $this->getActivations($item_id, $excluded_users);

            foreach ($activations as $user) {
                try {
                    $this->makeBid($item, $user);
                } catch (Exception $ex) {
                    $excluded_users[] = $ex->getMessage();
                }
            }
        } while (!$activations->isEmpty());
    }

    public function makeBid(Item $item, $user)
    {
        DB::beginTransaction();
        try {
            $last_bidding = $this->getLastBidding($item->id);

            $user_config = User::find($user->user_id)->autoBidConfig;

            if ($user->max_bid_amount < 1) {
                throw new Exception($user->user_id);
            }

            $user_config->max_bid_amount -= 1;
            $user_config->save();

            $action = new MakeBidAction();

            $action->execute([
                'user_id' => $user->user_id,
                'item_id' => $item->id,
                'amount' => $last_bidding ? $last_bidding->amount + 1 : $item->amount + 1
            ]);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            //This user failed constraints check, we will exclude them from next cycle
            throw new Exception($user->user_id);
        }
    }

    public function getActivations(int $item_id, $excluded_users = [])
    {
        $last_bidding = $this->getLastBidding($item_id);

        if ($last_bidding) {
            $excluded_users[] = $last_bidding->user_id;
        }

        return AutoBidActivation::leftJoin('auto_bid_configs', 'auto_bid_activations.user_id', '=', 'auto_bid_configs.user_id')
                ->where('item_id', $item_id)
                ->select('auto_bid_activations.user_id', 'auto_bid_configs.max_bid_amount')
                ->whereNotNull('auto_bid_configs.max_bid_amount')
                //auto bid for users with enough funds
                ->where('auto_bid_configs.max_bid_amount', '>', 0)
                //exlude users who failed previous bidding constraints or are currently the highest bidders
                ->whereNotIn('auto_bid_activations.user_id', $excluded_users)
                //order by ascending order of max_bid_amount by this we can eliminate people with low funds
                ->orderBy('auto_bid_configs.max_bid_amount')
                ->get();
    }

    public function getLastBidding(int $item_id)
    {
        return Bidding::where('item_id', $item_id)
                ->orderBy('amount', 'desc')
                ->first();
    }
}
