<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Auction;
use \App\Models\Bid;
class auctionAPI extends Controller
{
    // creating a new auction
    function add_auction(Request $req)
    {
        echo("started saving");
        $value="world";
        $auction=new Auction;
        $auction->user_id=$req->user_id;
        $auction->warehouse_type=$req->warehouse_type;
        $auction->commodity_name=$req->commodity_name;
        $auction->variety_name=$req->variety_name;
        $auction->commodity_image=$req->commodity_image;
        $auction->season=$req->season;
        $auction->warehouse_id=$req->warehouse_id;
        $auction->auction_type=$req->auction_type;
        $auction->auction_quantity=$req->auction_quantity;
        $auction->min_quantity_bid=$req->min_quantity_bid;
        // $auction->min_quantity_bid=$req->min_quantity_bid;
        $auction->reserve_price=$req->reserve_price;
        $auction->seller_name=$req->seller_name; 
        $auction->incremental_bid_price=$req->incremental_bid_price;
        $auction->display_bid_history=$req->display_bid_history;
        $auction->auction_creation_time=$req->auction_creation_time;
        $auction->auction_duration=$req->auction_duration;
        $auction->warehouse_pin=$req->warehouse_pin;
        $auction->warehouse_city=$req->warehouse_city;
        $auction->warehouse_state=$req->warehouse_state;
        $auction->warehouse_name_addresss=$req->warehouse_name_addresss;
        $auction->contact_details=$req->contact_details;
        $auction->auction_status=$req->auction_status;
        $auction->auction_start_date_time=$req->auction_start_date_time;
        $auction->auction_end_date_time=$req->auction_end_date_time;
        $auction->certified_auction=$req->certified_auction;

        echo("about to save");
        $result=$auction->save();
        echo("saved");

        if($result)
        {
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"upload successful"
                ],
                "auction_start_date_time"=>$auction->auction_start_date_time,
                "auction_id"=>$auction->auction_id
            ];
        }
        else{
            echo("that is an error ");
            return[
                'status' => [
                    "code"=>500,
                    "message"=>"upload not successful"
                ],
            ];
        }
    }

    // uploading a new auction image
    function upload_image(Request $req){
        $result=$req->file('image')->store('auction_images');

        if($result)
        {
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"upload successful"
                ],
                "result"=>$result
            ];
        }
        else{
            return[
                'status' => [
                    "code"=>500,
                    "message"=>"upload not successful"
                ],
            ];
        }
    }

    // either returns a particular auction's details or if the particular auction is not
    // found then returns all the auctions in the database in a single request. (can optimize)
    function get_auction($auction_id=null){
        if($auction_id){
            $result = Auction::where('auction_id',$auction_id)->first();
            if(empty($result)){
                return [
                    'status' => [
                        "code"=>204,
                        "message"=>"No content"
                    ],
                ];
            }
            else{
                return [
                    'status' => [
                        "code"=>200,
                        "message"=>"success"
                    ],
                    "result"=>$result
                ];
            }
        }
        else{
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error displaying"
                ],
            ];
        }
        // return $auction_id?Auction::where('auction_id',$auction_id)->first():Auction::all();
    }

    // filter auctions based on commodity, state and status
    function filter_auction(Request $req){
        $result=Auction::where([
            ['commodity_name', $req->commodity_name],
            ['warehouse_state', $req->warehouse_state],
            ['auction_status', $req->auction_status]
        ])->get();
        
        if($result){
            if(empty($result)){
                // if we are unable to fetch any data from DB then show no content
                return [
                    'status' => [
                        "code"=>204,
                        "message"=>"No content"
                    ],
                ];
            }
            else{
                // if we were able to fetch some data based on the given filters then
                // return the list of auctions as output
                return [
                    'status' => [
                        "code"=>200,
                        "message"=>"success"
                    ],
                    "result"=>$result
                ];
            }
        }
        else{
            // if something goes wrong return error
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error displaying"
                ],
            ];
        }
        // return $result;
    }

    // returns similar auctions based on given filters 
    function similar($auction_id){
        $auction=Auction::where('auction_id', $auction_id)->first();
        
        if($auction){
            $commodity_name=Auction::where('commodity_name', $auction->commodity_name)->get();
            $warehouse_state=Auction::where('warehouse_state', $auction->warehouse_state)->get();
            
            return [
                "auction_based" => $auction,
                "commodity_name_based" => $commodity_name,
                "warehouse_state_based" => $warehouse_state
            ];
        }
        else {
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
            ];
        }
        // echo($auction);
        // echo($commodity_name);
        // echo($warehouse_state);
    }

    // returns a list of bids sorted in descending order of prices
    function get_bids($auction_id){
        $result =  Bid::where('auction_id', $auction_id)->orderBy('bid_price', 'desc')->get();
        if($result){
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"success"
                ],
                "result"=>$result
            ];
        }
        else{
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
            ];
        }
    }

    // returns summary of all bids for a particular auction
    // returns the count of the total number of bids under that auction
    // as well as the max bid under that particular auction

    // in case no bids exist : { count : 0, bid_max : null }
    function bid_summary($auction_id){
        $auction = Auction::where('auction_id', $auction_id);
        if($auction){
            $bid_count=Bid::where('auction_id', $auction_id)->count();
            $bid_max=Bid::where('auction_id', $auction_id)->max('bid_price');
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"success"
                ],
                "count"=>$bid_count, 
                "bid_max"=>$bid_max
            ];
        }
        else{
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
            ];
        }
    }

    // placing a new bid
    function place_bid(Request $req){
        $bid = new Bid;
        $bid->auction_id=$req->auction_id;
        $bid->bid_price=$req->bid_price;
        $bid->bid_qty=$req->bid_qty;
        $bid->user_id=$req->user_id;
        $bid->bid_time=$req->bid_time;
        $result=$bid->save();

        if($result){
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"successful"
                ],
                "bid_id"=>$bid->bid_id
            ];
        }
        else{
            return[
                'status' => [
                    "code"=>500,
                    "message"=>"upload not successful"
                ],
            ];
        }
    }

    // returns details of a particular bid
    function my_trades($bid_id){
        $bid = Bid::where('bid_id', $bid_id)->first();
        if($bid == null){
            return[
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
            ];
        }

        $auction_id = $bid->auction_id;
        $auction = Auction::where('auction_id', $auction_id)->first();

        if($auction && $bid){
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"successful"
                ],
                "commodity_name"=>$auction->commodity_name,
                "variety"=>$auction->variety_name,
                "city"=>$auction->warehouse_city,
                "state"=>$auction->warehouse_state,
                "auction_id"=>$auction->auction_id,
                "bid_qty"=>$bid->bid_qty,
                "bid_price"=>$bid->bid_price,
                "auction_end_date"=>$auction->auction_end_date_time,
                "winning_bid_qty"=>$bid->winning_bid_qty
            ];
        }
        else {
            return[
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
            ];
        }
    }

    // update advance payment status
    function update_payment_advance(Request $req){
        $bid_id = (int) $req->bid_id;
        $bid = Bid::where('bid_id', $req->bid_id)->first();

        if($bid){
            $bid->advance_amount_payment_status = $req->advance_amount_payment_status;
            $bid->save();
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"successful"
                ],
                "bid_id"=>$bid_id,
                "bid_data"=>$bid
            ];
        }
        else{
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
                "error"=>"update unsuccessful"
            ];
        }
    }

    // update full payment details 
    function update_payment_full(Request $req){
        $bid_id = (int) $req->bid_id;
        $bid = Bid::where('bid_id', $req->bid_id)->first();

        if($bid){
            $bid->full_amount_payment_status = $req->full_amount_payment_status;
            $bid->save();
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"successful"
                ],
                "bid_id"=>$bid_id,
                "bid_data"=>$bid
            ];
        }
        else{
            return [
                'status' => [
                    "code"=>500,
                    "message"=>"error"
                ],
                "error"=>"update unsuccessful"
            ];
        }
    }
}