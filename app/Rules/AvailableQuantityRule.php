<?php

namespace App\Rules;

use App\Models\BranchInventory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\PotentiallyTranslatedString;

class AvailableQuantityRule implements ValidationRule
{
    protected $productId;
    protected $fromBranchId;

    public function __construct($productId, $fromBranchId)
    {
        $this->productId = $productId;
        $this->fromBranchId = $fromBranchId;
    }
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $inventory = BranchInventory::where('branch_id', $this->fromBranchId)
            ->where('product_id', $this->productId)
            ->first();

        if (!$inventory) {
            $fail('The selected product does not exist in inventory.');
            return;
        }

        if ($value > $inventory->quantity) {
            $fail("The requested quantity ($value) exceeds available stock ({$inventory->quantity}).");
        }
    }
}
