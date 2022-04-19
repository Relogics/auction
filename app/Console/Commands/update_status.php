<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Auction;
use \App\Models\Bid;
use \App\Models\UserAttributesData;

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

    public function send_msg($mobile_num,$msg)
        {
                if($mobile_num)
                {
                        $sms_message = "Hi! Your ARYA verification code is " . $otpcode;
                        
                        try {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://alerts.cbis.in/SMSApi/send",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "userid=aryacma&password=fqGnYXMS&mobile=".$mobile_num."&msg=".$msg."&senderid=ARYACM&msgType=text&dltEntityId=&dltTemplateId=&duplicatecheck=true&output=json&sendMethod=quick",
                        CURLOPT_HTTPHEADER => array(
                        "apikey: somerandomuniquekey",
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded"
                        ),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);

                        curl_close($curl);

                        if ($err) {
                        //echo "cURL Error #:" . $err;
                                return $this->respondWithErrorValidation(trans("messages.An_error_occurred"));
                        } else {
                        //echo '<pre>';print_r($response);
                                return $this->respondWithoutPagination(trans("messages.Verification_code_sent"));
                        }
                        } catch (Exception $e) {

                        //echo 'Message:' . $e->getMessage();
                        }
                        /*
                        event(new OtpRegisterEvent($post['mobile'], $otpcode));
                        return $this->respondWithoutPagination(trans("messages.Verification_code_sent"));
                        */
                }
                else {
                        return $this->respondWithError(trans("messages.An_error_occurred"));
                }
        }

    public function handle()
    {   
        $auction=Auction::where('auction_end_date_time','<',date('Y-m-d H:i:s'))->get();
        foreach($auction as $auction_data){
            $auction_data->auction_status='expired';
            $auction_data->save();
            
            //if auction type is english
            if($auction_data->auction_type=='english'){
                $max_bid = Bid::where('auction_id',$auction->id)->orderBy('bid_price','desc')->orderBy('bid_time','asc')->first();
                if($max_bid){
                    $max_bid->status='won';
                    $max_bid->save();
                    $user_data = UserAttributeData::where('user_id',$max_bid->user_id)->first();
                    $mobile_num = $user_data->mobile;
                    $msg = "Dear User, auction".$auction->auction_id." has ended. You have won the auction. Visit ARYAMKP site for more details. -ARYAMKP";
                    $this->send_msg($mobile_num,$msg);
                }
                $remaining_bid = Bid::where('auction_id',$auction->id)->where('bid_id','<>',$max_bid->bid_id)->get();
                foreach($remaining_bid as $bid){
                    $bid->status='lost';
                    $bid->save();
                    $user_data = UserAttributeData::where('user_id',$max_bid->user_id)->first();
                    $mobile_num = $user_data->mobile;
                    $msg = "Dear User, auction - ".$auction->auction_id." has ended. You have lost the auction. -ARYAMKP";
                    $this->send_msg($mobile_num,$msg);
                }
        }
            //if auction type is yankee
            elseif($auction_data->auction_type=='yankee'){
                $max_bid = Bid::where('auction_id',$auction->id)->orderBy('bid_qty','desc')->orderBy('bid_time','asc')->first();
                $rem_quant = $auction_data->auction_quantity - $max_bid->bid_qty;
                if($max_bid){
                    $max_bid->status='won';
                    $max_bid->save();
                    $user_data = UserAttributeData::where('user_id',$max_bid->user_id)->first();
                    $mobile_num = $user_data->mobile;
                    $msg = "Dear User, you were declared winner in auction ".$auction->auction_id." Click here to contact Seller ".$auction->seller_name." for further steps.";
                    $this->send_msg($mobile_num,$msg);
                }
                $rem_bid = Bid::where('auction_id',$auction->id)->where('bid_id','<>',$max_bid->bid_id)->orderBy('bid_qty','desc')->orderBy('bid_time','asc')->get();
                foreach($rem_bid as $bid){    
                    if($bid->bid_qty<=$rem_quant){
                        $bid->status='won';
                        $bid->save();
                        $msg = "Dear User, you were declared winner in auction ".$auction->auction_id." Click here to contact Seller ".$auction->seller_name." for further steps.";
                        $this->send_msg($mobile_num,$msg);
                        $rem_quant = $rem_quant - $bid->bid_qty;
                    }else{
                        $bid->status='lost';
                        $bid->save();
                        $msg = "Dear User, auction - ".$auction->auction_id." has ended. You have lost the auction. -ARYAMKP";
                        $this->send_msg($mobile_num,$msg);
                    }
                }
            }

        }

        return 0;
    }
}
