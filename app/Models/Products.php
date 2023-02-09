<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'created_by',
        'category_id',
        'product_status'
    ];


     //relationship with product_category table
     public function product_category()
     {
         return $this->belongsTo(ProductCategory::class,'category_id','id');
     }

     //relationship with user table
     public function user()
     {
         return $this->belongsTo(User::class,'created_by','id');
     }

      /**
     * lets get all categories and products listed for a detailed response.
     *
     */
    protected static function getallproducts(){

        $data = SELF::with(['product_category','user'])->get();

         return $data;
    }


     /**
     * lets selected categories and products listed for a detailed response.
     *
     */
    protected static function getselectedproduct($id){

        $data = SELF::with(['product_category','user'])->where([['id', $id]])->get();

         return $data;
    }


}
