<?php

namespace AlphansoTech\LiveStreaming;
use AlphansoTech\LiveStreaming\Controllers\LiveStreamingController;
use Illuminate\Support\Facades\Http;

class LiveStreamingFunction {

     public function __construct(){
        
        $this->LiveStream = new LiveStreamingController();
    }

    /* BrodeCast Location List */

    public function BroadcastLocationList() {
    
        $response = $this->LiveStream->BroadcastLocationList();
        return $response;
    }

    /* Camera Encoder List */

    public function CameraEncoderList() {
    
        $response = $this->LiveStream->CameraEncoderList();
        return $response;
    }


    /*Live Stream Store*/

    public function LiveStreamStore($request){


        if(!is_array($request)){
             
            $response = ATLSP_generateResponse(false,422,'Input field must be a valid array.');

            return  $response;
        }
        $response = $this->LiveStream->LiveStreamStore($request);

        return $response;
    
    }


     /* Get All Stream List */

    public function StreamList($filterData,$pagination,$limit,$order_by){

        if(!is_array($filterData)){
             
             $response = ATLSP_generateResponse(false,422,'FilterData must be a valid array.');
             return  $response;
        }

        if(!is_bool($pagination)){
            
            $response = ATLSP_generateResponse(false,422, 'Pagination must be a valid boolean value.');
            return  $response;

        }

        if(!is_array($order_by)){
             
             $response = ATLSP_generateResponse(false,422,'order by must be a valid array.');
             return  $response;

        }
    
        $response = $this->LiveStream->StreamList($filterData,$pagination,$limit,$order_by);

        return  $response;

    }

    /* Get Single Stream By Id */


    public function GetSingleLiveStream($stream_id, $wowza_id){


        if (!is_numeric($stream_id)) {
            $response =  ATLSP_generateResponse(false, 422, 'Stream ID must be a valid number.');
            return  $response;
        }

        $response = $this->LiveStream->GetSingleLiveStream($stream_id, $wowza_id);
        return  $response;

    }


    /*Stream Update*/

    public function StreamUpdate($request, $stream_id, $wowza_id){
        
        if(!is_array($request)){
             
             $response = ATLSP_generateResponse(false,422,'Input field must be a valid array.');
             return  $response;
        }

        if(!isset($stream_id) && !is_numeric($stream_id)){
            $response =  ATLSP_generateResponse(false,202,'stream id is missing or pass properly.');       
            return $response; 
        }

        $response = $this->LiveStream->StreamUpdate($request,$stream_id, $wowza_id);
        return $response;
    }


    /* Remove Stream */

    public function RemoveLiveStream($stream_id, $wowza_id){

        if(!isset($stream_id) && !is_numeric($stream_id)){
            $response =  ATLSP_generateResponse(false,202,'stream id is missing or pass properly.');       
            return $response; 
        }

        $response = $this->LiveStream->RemoveLiveStream($stream_id, $wowza_id);
        return $response;
    }

    /* Start stream */

    public function StartLiveStream($stream_id, $wowza_id){
        
        if(!isset($stream_id) && !is_numeric($stream_id)){
            $response =  ATLSP_generateResponse(false,202,'stream id is missing or pass properly.');       
            return $response; 
        }

        $response = $this->LiveStream->StartLiveStream($stream_id, $wowza_id);
        
        return $response;        
    }
    /*Publish Live Stream*/

    

    public function PublishStream($stream_id, $wowza_id){
    
        if(!isset($stream_id) && !is_numeric($stream_id)){
            $response =  ATLSP_generateResponse(false,202,'stream id is missing or pass properly.');       
            return $response; 
        }

        $response = $this->LiveStream->PublishStream($stream_id, $wowza_id);
        
        return $response;        
    }

    /*Sotop Live Stream*/    

    public function StopLiveStream($stream_id,$wowza_id){

        if(!isset($stream_id) && !is_numeric($stream_id)){
            $response =  ATLSP_generateResponse(false,202,'stream id is missing or pass properly.');       
            return $response; 
        }

        $response = $this->LiveStream->StopLiveStream($stream_id,$wowza_id);
        
        return $response;        
    
    }

    /*Live Stream Statistics*/
    
    public function LiveStreamStatistics($stream_id,$wowza_id){

        if(!isset($stream_id) && !is_numeric($stream_id)){
            $response =  ATLSP_generateResponse(false,202,'stream id is missing or pass properly.');       
            return $response; 
        }

        $response = $this->LiveStream->LiveStreamStatistics($stream_id,$wowza_id);
        
        return $response;        
    
    }


}
