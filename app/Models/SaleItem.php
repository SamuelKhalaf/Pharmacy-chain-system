<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class SaleItem
 *
 * @property int $sale_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 */
class SaleItem extends Model
{
    use HasFactory;
    protected $fillable =[
        'sale_id',
        'product_id',
        'quantity',
        'price'
    ];
    public $timestamps=false;
}

