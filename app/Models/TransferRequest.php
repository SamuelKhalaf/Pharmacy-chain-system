<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $from_branch_id
 * @property int $to_branch_id
 * @property int $product_id
 * @property int $quantity
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TransferRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable =[
        'from_branch_id',
        'to_branch_id',
        'product_id',
        'quantity',
        'status',
    ];

    public function getFromBranchNameAttribute()
    {
        return Branch::where('id',$this->from_branch_id)->pluck('name')->first();
    }

    public function getToBranchNameAttribute()
    {
        return Branch::where('id',$this->to_branch_id)->pluck('name')->first();
    }

    public function getProductNameAttribute()
    {
        return Product::where('id',$this->product_id)->pluck('name')->first();
    }
}
