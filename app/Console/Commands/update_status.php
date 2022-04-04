<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Auction;
use \App\Models\Bid;

class update_status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update auction status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $auction=Auction::where('auction_end_date_time','<',date('Y-m-d H:i:s'))->get();
        foreach($auction as $auction_data){
            $auction_data->auction_status='expired';
            $auction_data->save();
            
            //if auction type is english
            if($auction_data->auction_type=='english'){
                $max_bid = Bid::where('auction_id',$auction_data->id)->orderBy('bid_price','desc')->orderBy('bid_time','asc')->first();
                if($max_bid){
                    $max_bid->status='won';
                    $max_bid->save();
                    echo "Auction ID: ".$auction_data->id." has been won by ".$max_bid->user_id."\n";   //replace with sms topic
                }
                $remaining_bid = Bid::where('auction_id',$auction_data->id)->where('bid_id','<>',$max_bid->bid_id)->get();
                foreach($remaining_bid as $bid){
                    $bid->status='lost';
                    $bid->save();
                    echo "Auction ID: ".$auction_data->id." has been lost by ".$bid->user_id."\n"; //replace with sms topic
                }
            }
            //if auction type is yankee
            //logic to be changed based on what to do with qty
            elseif($auction_data->auction_type=='yankee'){
                $max_bid = Bid::where('auction_id',$auction_data->id)->orderBy('bid_price','desc')->orderBy('bid_time','asc')->first();
                if($max_bid){
                    $max_bid->status='won';
                    $max_bid->save();
                    echo "Auction ID: ".$auction_data->id." has been won by ".$max_bid->user_id."\n";   //replace with sms topic
                }
                $remaining_bid = Bid::where('auction_id',$auction_data->id)->where('bid_price','<>',$max_bid->bid_price)->get();
                foreach($remaining_bid as $bid){
                    $bid->status='lost';
                    $bid->save();
                    echo "Auction ID: ".$auction_data->id." has been lost by ".$bid->user_id."\n"; //replace with sms topic
                }
            }

        }

        return 0;
    }
}
