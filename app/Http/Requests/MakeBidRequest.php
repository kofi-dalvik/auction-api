<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id' => [
                'required',
                Rule::exists('items', 'id')->where(fn ($query) => $query->where('closing_date', '>', now()))
            ],
            'amount' => 'required|numeric',
            'auto_bidding' => 'required|in:0,1'
        ];
    }
}
