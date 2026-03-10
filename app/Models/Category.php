<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    public $timestamps = true;
    protected $guarded = [
        'id',
    ];
    protected $fillable = [
        'name','clasificacion',
    ];

    protected $casts = [
        'id'                          => 'integer',
        'clasificacion'               => 'string',
        'name'                        => 'string',
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'category_id', 'id');
    }

}
