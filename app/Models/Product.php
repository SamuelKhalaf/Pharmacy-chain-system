<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Product
 *
 * @property string $name
 * @property string|null $description
 * @property int $category_id
 */
class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'description',
        'category_id',
    ];

    public function getCategoryNameAttribute()
    {
        return Category::where('id' , $this->category_id)->pluck('name')->first();
    }
}
