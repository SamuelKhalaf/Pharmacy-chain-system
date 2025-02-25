<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @property int $branch_id
 * @property array $product_id
 * @property array $quantity
 * @property array $price
 */
class CreateBranchInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id'   => 'required|exists:branches,id',
            'product_id'  => 'required|array',
            'product_id.*'=> 'exists:products,id',
            'quantity'    => 'required|array',
            'quantity.*'  => 'integer|min:1',
            'price'       => 'required|array',
            'price.*'     => 'numeric|min:1'
        ];
    }
}
