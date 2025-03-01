<?php

namespace App\Http\Requests;

use App\Rules\AvailableQuantityRule;
use App\Rules\ProductExistsInBranch;
use Illuminate\Foundation\Http\FormRequest;
/**
 * Class TransferredDataRequest
 *
 * @property int $from_branch_id
 * @property int $to_branch_id
 * @property array<int> $product_id
 * @property array<int> $quantity
 */
class TransferredDataRequest extends FormRequest
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
        $rules = [
            'from_branch_id' => 'required|exists:branch_inventory,branch_id|different:to_branch_id',
            'to_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
        ];

        foreach ($this->product_id ?? [] as $index => $product_id) {
            // Check if the product exists in the branch_inventory table for the given branch
            $rules["product_id.$index"] = [
                'required',
                'integer',
                new ProductExistsInBranch($this->from_branch_id),
            ];
        }
        // Apply custom validation for each product quantity
        foreach ($this->quantity ?? [] as $index => $qty) {
            $rules["quantity.$index"] = [
                'required',
                'integer',
                'min:1',
                new AvailableQuantityRule($this->product_id[$index] ?? null , $this->from_branch_id),
            ];
        }

        return $rules;
    }
}
