<?php

namespace App\Rules;

use App\Models\BranchInventory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductExistsInBranch implements ValidationRule
{
    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = BranchInventory::where('branch_id', $this->branchId)
            ->where('product_id', $value)
            ->exists();

        if (!$exists) {
            $fail('The selected product does not belong to the selected branch.');
        }
    }
}
