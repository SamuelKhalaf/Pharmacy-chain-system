<?php

namespace App\Http\Requests;

use App\Rules\AvailableQuantityRule;
use App\Rules\ProductExistsInBranch;
use Illuminate\Foundation\Http\FormRequest;
/**
 * Class RequsetProductsRequest
 *
 * @property string $request_type
 * @property int $from_branch_id
 * @property int $to_branch_id
 * @property array<int> $product_id
 * @property array<int> $quantity
 */
class RequsetProductsRequest extends FormRequest
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
            'request_type' => ['required', 'in:send,receive'],
            'to_branch_id' => ['required_if:request_type,send', 'exists:branches,id'],
            'from_branch_id' => ['required_if:request_type,receive', 'exists:branch_inventory,branch_id'],
            'product_id' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
        ];

        $branchId = $this->request_type === 'send' ? auth()->user()->branch_id : $this->from_branch_id;
        foreach ($this->product_id ?? [] as $index => $product_id) {
            // Check if the product exists in the branch_inventory table for the given branch
            $rules["product_id.$index"] = [
                'required',
                'integer',
                new ProductExistsInBranch($branchId),
            ];
        }
        // Apply custom validation for each product quantity
        foreach ($this->quantity ?? [] as $index => $qty) {
            $rules["quantity.$index"] = [
                'required',
                'integer',
                'min:1',
                new AvailableQuantityRule($this->product_id[$index] ?? null , $branchId),
            ];
        }

        return $rules;
    }
}
