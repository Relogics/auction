<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auctionAPI;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('create_auction', [auctionAPI::class, 'add_auction']);
Route::get('get_auction/{auction_id?}', [auctionAPI::class, 'get_auction']);
// Route::get('get_auction', [auctionAPI::class, 'get_auction']);

Route::post('filter_auction', [auctionAPI::class, 'filter_auction']);


Route::GET('bid/{auction_id}', [auctionAPI::class, 'get_bids']);
Route::GET('bid_summary/{auction_id}', [auctionAPI::class, 'bid_summary']);