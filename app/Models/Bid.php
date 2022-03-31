<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    public $table="bid";
    protected $primaryKey = 'bid_id';
    use HasFactory;
    public $timestamps=false;
}
