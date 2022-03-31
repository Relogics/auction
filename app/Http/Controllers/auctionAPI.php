<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Auction;
use \App\Models\Bid;
class auctionAPI extends Controller
{
    //
    function add_auction(Request $req)
    {
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
        $auction->min_quantity_bid=$req->min_quantity_bid;
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

        $result=$auction->save();

        if($result)
        {
            return [
                'status' => [
                    "code"=>200,
                    "message"=>"upload successful"
                ],
                "auction_start_date_time"=>$auction->auction_start_date_time,
                "auction_id"=>$auction->id
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

    function get_auction($auction_id=null){
        return $auction_id?Auction::where('auction_id',$auction_id)->first():Auction::all();
    }

    
    function filter_auction(Request $req){
        $result=Auction::where([
            ['commodity_name', $req->commodity_name],
            ['warehouse_state', $req->warehouse_state],
            ['auction_status', $req->auction_status]
        ])->get();
        return $result;

    }

    function similar($auction_id){
        $auction=Auction::where('auction_id', $auction_id)->first();
        $commodity_name=Auction::where('commodity_name', $auction->commodity_name)->get();
        $warehouse_state=Auction::where('warehouse_state', $auction->warehouse_state)->get();

        return [
            $auction, $commodity_name, $warehouse_state
        ];

    }




    function get_bids($auction_id){
        return Bid::where('auction_id', $auction_id)->get();
    }


    function bid_summary($auction_id){
        $bid_count=Bid::where('auction_id', $auction_id)->count();
        $bid_max=Bid::where('auction_id', $auction_id)->max('bid_price');

        return ["count"=>$bid_count, "bid_max"=>$bid_max];

    }

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
                    "message"=>"upload successful"
                ],
                "bid_id"=>$bid->id
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


    function my_trades($bid_id){
        $bid = Bid::where('bid_id', $bid_id)->first();
        
        $auction_id = $bid->auction_id;
        $auction= Auction::where('auction_id', $auction_id)->first();
        return [
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



    function update_payment_advance(Request $req){
        $bid_id=$req->bid_id;
        $bid = Bid::where('bid_id',$bid_id)->first();
        
        $bid->advance_amount_payment_status=$req->advance_amount_payment_status;
        
        $bid->save();
        return $bid;

        return ["error"=>"update unsuccesfull"];

    }

    function update_payment_full(Request $req){
        $bid_id=$req->bid_id;
        $bid = Bid::where('bid_id',$bid_id)->first();
        
        $bid->full_amount_payment_status=$req->full_amount_payment_status;
        
        $bid->save();
        return $bid;

        return ["error"=>"update unsuccesfull"];

    }

}
