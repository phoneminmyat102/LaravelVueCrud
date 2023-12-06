<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'price' => 'required|string',
            'description' => 'required|string',
            'item_condition' => 'required|string',
            'item_type' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string',
            'owner_name' => 'required|string',
            'phone_number' => 'required|regex:/^\+[0-9]+$/',
            'address' => 'required|string',
        ];
    }
}
