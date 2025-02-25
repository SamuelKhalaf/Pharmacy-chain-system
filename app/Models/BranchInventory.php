<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class BranchInventory
 *
 * @property int $branch_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 */
class BranchInventory extends Model
{
    use HasFactory;

    protected $table = 'branch_inventory';

    protected $fillable = [
        'branch_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function getProductNameAttribute()
    {
        return Product::where('id',$this->product_id)->pluck('name')->first() ?? 'N/A';
    }

    public function getBranchNameAttribute()
    {
        return Branch::where('id',$this->branch_id)->pluck('name')->first() ?? 'N/A';
    }
}
