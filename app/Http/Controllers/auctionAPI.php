<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Auction;
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

    function get_auction($id=NULL){
        if($id){
            $result = Auction::find($id);
            return $result;
            
        }
        $result=Auction::all();
        return $result;
    }

    

    function filter_auction($commodity_name, $warehouse_state, $auction_status)
    {
        return Auction::where("commodity_name", $commodity_name)->orWhere("warehouse_state", $warehouse_state)->orWhere("auction_status", $auction_status)->get();
    }
}
