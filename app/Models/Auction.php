<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    public $table="auction";
    protected $primaryKey = 'auction_id';
    use HasFactory;
    public $timestamps=false;
}
