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

// authentication middleware
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// for creating a new auction
// checked
Route::post('create_auction', [auctionAPI::class, 'add_auction']);

// get the results for a particular auction
// fetch starts at index 13 , before that table schema data is used
// checked
Route::get('get_auction/{auction_id?}', [auctionAPI::class, 'get_auction']);

// Route::get('get_auction', [auctionAPI::class, 'get_auction']);
// checked
Route::get('similar_auction/{auction_id?}', [auctionAPI::class, 'similar']);

// filters auction based on passed arguments
// checked
Route::post('filter_auction', [auctionAPI::class, 'filter_auction']);

// getting all the bids under a particular auction
// checked 
Route::get('bid/{auction_id}', [auctionAPI::class, 'get_bids']);

// get summary of all the bids for an auction so far. count and highest bid
// checked
Route::get('bid_summary/{auction_id}', [auctionAPI::class, 'bid_summary']);

// posting a bid
// checked
Route::post('bid', [auctionAPI::class, 'place_bid']);

// returns details of a particular bid
// checked
Route::get('bid_detail/{bid_id}', [auctionAPI::class, 'my_trades']);

// advance payment update
// checked
Route::put('bid_advance', [auctionAPI::class, 'update_payment_advance']);

// full payment update
// checked
Route::put('bid_full', [auctionAPI::class, 'update_payment_full']);

// uploads an image and sends its stored URL ( can be migrated to S3 in future )
// checked
Route::post('upload_image', [auctionAPI::class, 'upload_image']);