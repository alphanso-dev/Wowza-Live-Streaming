<?php

namespace AlphansoTech\LiveStreaming\Controllers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use AlphansoTech\LiveStreaming\Models\LiveStream;
use Illuminate\Http\Request;

class LiveStreamingController  
{

    public function __construct()
    {
        
        $this->LiveStreamModel = new LiveStream();
    }


    public function HomePage(Request $request){
        
        return view('LiveStream::index');
    }

    /* BrodeCast Location List */

    public function BroadcastLocationList()
    {
        
        $broadcastLocationList = ATLSP_BroadcastLocation();
        
        $response =  ATLSP_generateResponse(true,200,'Broadcast Location List.',$broadcastLocationList);
        return $response;
    }

    /* Camera Encoder List */

    public function CameraEncoderList()
    {
        
        $cameraEncoderList = ATLSP_CameraEncoder();
        
        $response =  ATLSP_generateResponse(true,200,'Camera Encoder List.',$cameraEncoderList);
        return $response;
    }


    /* Live Stream Store */

    public function LiveStreamStore(array $request=[]){

        $input = $request;
        /*Check Validation*/

        $validateInputs = ATLSP_RequestValidation($request, 'store');

        if($validateInputs['status'] == false){

            $response =  ATLSP_generateResponse(false,422,$validateInputs['message'],$validateInputs['data']);
            return $response;
            
        }

        /*Create Stream In Wowza*/

        $inputdata['live_stream'] = [
            "name"                  => $input['stream_title'],
            "broadcast_location"    => $input['broadcast_location'],
            "description"           => $input['description'],
            "transcoder_type"       => "transcoded",
            "billing_mode"          => "pay_as_you_go",
            "encoder"               => $input['encoder'],
            "disable_authentication" => true,
            "aspect_ratio_height"   => "720",
            "aspect_ratio_width"    => "1280",
            "delivery_method"       => "push",
            "player_responsive"     => true,
            "low_latency"           => true,
            "recording"             => false
        ];
            
        $url = "/live_streams";

        $stremCreate = ATLSP_RunApi($url,"POST",$inputdata);

        if(isset($stremCreate->live_stream)) {
            
            $outputData = $stremCreate->live_stream;

            $stream_id                  = (int)ATLSP_RendomString(10, 'number');
            $input['stream_id']         = $stream_id;
            $input['wowza_id']          = $outputData->id;
            $input['state']             = $outputData->state;
            $input['billing_mode']      = $outputData->billing_mode;
            $input['recording']         = $outputData->recording;
            $input['delivery_method']   = $outputData->delivery_method;
            $input['sdp_url']           = $outputData->source_connection_information->sdp_url;
            
            $image_name   = '';
            $storage_disk = 'public';

            if (isset($input['image'])) {
                $image_name = ATLSP_UploadCustomeImage($input['image'],$stream_id,$storage_disk, 'stream_image', 'stream_image', 'crop', 640, 360);
            }


            $input['image']             = $image_name;            
            $input['application_name']  = $outputData->source_connection_information->application_name;
            $input['stream_name']       = $outputData->source_connection_information->stream_name;
            $input['hls_playback_url']  = $outputData->hls_playback_url;
            $input['player_id']         = $outputData->player_id;            

            $createLiveStremDB          = $this->LiveStreamModel->InsertData($input);

            if(isset($createLiveStremDB->wowza_id)) {
                
                 $response =  ATLSP_generateResponse(true,200,'Live Stream created successully.');
                 
            }else{

                /* Remove Stream From Wowza */

                $url = "/live_streams/$outputData->id";
                $removeStream = ATLSP_RunApi($url, "DELETE");

                $response =  ATLSP_generateResponse(false,202,'Live Stream not crete please try again.');
            }
        }else if(isset($stremCreate->meta)){
            
            $response =  ATLSP_generateResponse(false,$stremCreate->meta->status,$stremCreate->meta->message);
            
        }else{
            
            $response =  ATLSP_generateResponse(false,202,'Live Stream not creted please try again');            
        }
        
        return $response;
    }

    /*Live Streaming List*/

    public function StreamList(array $filterData = [], bool $pagination=true, int $limit=10, array $order_by=['created_at', 'desc']){

        $filterData = isset($filterData) ? $filterData : [];
        $pagination = isset($pagination) ? $pagination : false;
        $limit      = isset($limit) ? $limit : -1;
        $order_by   = isset($order_by) ? $order_by : ['created_at', 'desc'];
        

        $streamList = $this->LiveStreamModel->GetAllStreamList($filterData, $pagination, $limit, $order_by);

        if(count($streamList) > 0){
            
            $response =  ATLSP_generateResponse(true,200,'Stream List.',$streamList);
        }else{
            $response =  ATLSP_generateResponse(false,202,'Stream List Not available.');
        }   
        
        return $response;    
    }


    /*Get Single Live Stream*/

    public function GetSingleLiveStream($stream_id, $wowza_id){

        $streamData = $this->LiveStreamModel->GetSingleData($stream_id, $wowza_id);
        
        if(!is_null($streamData)){

            $url = "/live_streams/$wowza_id";
            $wowzaData = ATLSP_RunApi($url, "GET");
            
            $data['stream_detail']  = $streamData;
            $data['wowza_data']     = $wowzaData;
            
            $response =  ATLSP_generateResponse(true,200,'Stream Detail.',$data);

        }else{
            
            $response =  ATLSP_generateResponse(false,202,'Live stream details not found.');
        }
        return $response;
    }

    /*Stream Update*/

    public function StreamUpdate($request, $stream_id, $wowza_id){

        $input = $request;

        /* Check Validation */

        $validateInputs = ATLSP_RequestValidation($request, 'update');

         if($validateInputs['status'] == false){

            $response =  ATLSP_generateResponse(false,422,$validateInputs['message'],$validateInputs['data']);
            return $response;

        }

        $singleStream = $this->LiveStreamModel->GetSingleData($stream_id, $wowza_id);
        
        if(!is_null($singleStream) && $singleStream->state == 'stopped'){
            $inputdata['live_stream'] = [
                "name"                  => $input['stream_title'],
                "description"           => $input['description'],
                "transcoder_type"       => "transcoded",
                "billing_mode"          => "pay_as_you_go",
                "encoder"               => $input['encoder'],
                "disable_authentication" => true,
                "aspect_ratio_height"   => "720",
                "aspect_ratio_width"    => "1280",
                "delivery_method"       => "push",
                "player_responsive"     => true,
                "low_latency"           => true,
                "recording"             => false
            ];
            
            /* Update Wowza Stream */

            $url = "/live_streams/$wowza_id";
            $updateStream = ATLSP_RunApi($url, "PATCH", $inputdata);

            if(isset($updateStream->live_stream)) {
                $outputData = $updateStream->live_stream;

                $input['stream_id']         = $stream_id;
                $input['wowza_id']          = $wowza_id;
                $input['state']             = $outputData->state;
                $input['billing_mode']      = $outputData->billing_mode;
                $input['recording']         = $outputData->recording;
                $input['delivery_method']   = $outputData->delivery_method;
                $input['hls_playback_url']  = $outputData->hls_playback_url;
                $input['player_id']         = $outputData->player_id;

                /* model call to update stream data */
                $update = $this->LiveStreamModel->UpdateData($input, $stream_id, $wowza_id);

                if($update) {
                    
                    $response =  ATLSP_generateResponse(true,200,'Live Stream created successully.');

                }else{
                    $response =  ATLSP_generateResponse(false,202,'Live Stream not update please try again.');
                    
                }
            }else if(isset($updateStream->meta)) {
                
                $response =  ATLSP_generateResponse(fales,202, $updateStream->meta->message);

            }else{
                
                $response =  ATLSP_generateResponse(false,202,'Server Not Responding please try again.');
            }
        }else if(!is_null($singleStream) && $singleStream->state == 'started'){
            
            $response =  ATLSP_generateResponse(false,202,'Live stream is started, please stop first and then update.');
        }else{

            $response =  ATLSP_generateResponse(false,202,'Live stream details not found.');
        }

        return $response;

    }

    /* Remove Live Stream */

    public function RemoveLiveStream($stream_id, $wowza_id){
    
        $singleStream = $this->LiveStreamModel->GetSingleData($stream_id, $wowza_id);

        if(!is_null($singleStream) && $singleStream->state == 'started'){

            $response =  ATLSP_generateResponse(false,202,'Live stream is started, please stop and then after remove.');
            return $response;
        }

        if(!is_null($singleStream) && $singleStream->state == 'stopped'){

            $url = "/live_streams/$wowza_id";
            $removeStream = ATLSP_RunApi($url, "DELETE");
            

            if($removeStream == null){
                    
                $removeStreamFromDb = $this->LiveStreamModel->DeleteData($stream_id, $wowza_id);

                if($removeStreamFromDb){
                    
                    $response =  ATLSP_generateResponse(true,200,'Live stream removed successfully.');

                }else{

                    $response =  ATLSP_generateResponse(false,202,'Live stream is not removed from database, please try again.');
                }

            }else if(isset($removeStream->meta)){
                 
                 $response =  ATLSP_generateResponse(false,$removeStream->meta->status,$removeStream->meta->message);

            }else{

                $response =  ATLSP_generateResponse(false,202,'Live stream is not removed, please try again.');
            }
    
        }else{
    
            $response =  ATLSP_generateResponse(false,202,'Live stream details not found.');
        }

        return $response;   
    }


    /* Start stream */

    public function StartLiveStream($stream_id,$wowza_id){

        $streamData   = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if(empty($streamData['data'])){
             $response =  ATLSP_generateResponse(false,202,'Live stream not found.');
             return $response;
        }

        if(!isset($streamData['data']['wowza_data']->live_stream)){
            $response =  ATLSP_generateResponse(false,202,$streamData['data']['wowza_data']->meta->message);
             return $response;
        }

        $streamStatusUrl       = "/live_streams/$wowza_id/state";
        $streamStatusResponce  = ATLSP_RunApi($streamStatusUrl, "GET");
        $streamStatus          = $streamStatusResponce->live_stream->state;
            
        if($streamData['status'] == true && isset($streamStatus) && $streamStatus == 'started'){  
            $response =  ATLSP_generateResponse(false,202,'Live stream already started.');
            return $response;
        }
        
        if($streamData['status'] == true && isset($streamStatus) && $streamStatus == 'stopped'){

            /*Live Stream Start Wowza*/

            $url = "/live_streams/$wowza_id/start";
            $liveStream = ATLSP_RunApi($url, "PUT");

            do{
                $streamStatusResponce  = ATLSP_RunApi($streamStatusUrl, "GET");

            }while ($streamStatusResponce->live_stream->state != 'started');
            
            $response =  ATLSP_generateResponse(true,200,'Live stream started successully.');
            
        }else{
        
            $response =  ATLSP_generateResponse(false,202,'Please Try Again.');
        }

        return $response;

    }

    /*Publish Live Stream*/

    public function PublishStream($stream_id,$wowza_id){

        $streamData   = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if(empty($streamData['data'])){
             $response =  ATLSP_generateResponse(false,202,'Live stream not found.');
             return $response;
        }

        if(!isset($streamData['data']['wowza_data']->live_stream)){
            $response =  ATLSP_generateResponse(false,202,$streamData['data']['wowza_data']->meta->message);
            return $response;
        }

        $streamStatusUrl       = "/live_streams/$wowza_id/state";
        $streamStatusResponce  = ATLSP_RunApi($streamStatusUrl, "GET");
        $streamStatus          = $streamStatusResponce->live_stream->state;
        if(isset($streamStatus) && $streamStatus == 'stopped' ){
            $response =  ATLSP_generateResponse(false,202,'Live Streaming is not started please try again.');
            return $response;
        }

        if(isset($streamStatus)){
            if($streamStatus == 'started' || $streamStatus == '' ){
                do{
                    $streamStatus  = ATLSP_RunApi($streamStatusUrl, "GET");

                } while ($streamStatus->live_stream->state != 'started');
                
                $inputStore = ['state' => 'started', 'stream_status' => 1];

                $update = $this->LiveStreamModel->UpdateData($inputStore,$stream_id,$wowza_id);  


                $response =  ATLSP_generateResponse(true,200,'Live stream published.');
                    
            } else {
                
                $response =  ATLSP_generateResponse(false,202,'Live Streaming is not started please try again.');
            }

        }else{
            
            $response =  ATLSP_generateResponse(false,202,'Live Streaming is not started please try again.');
        }

            return $response;
    }


       /*Stop Live Stream*/

    public function StopLiveStream($stream_id,$wowza_id){

        $streamData   = $this->GetSingleLiveStream($stream_id, $wowza_id);
        
        if(empty($streamData['data'])){
             $response =  ATLSP_generateResponse(false,202,'Live stream not found.');
             return $response;
        }

        if(!isset($streamData['data']['wowza_data']->live_stream)){
            $response =  ATLSP_generateResponse(false,202,$streamData['data']['wowza_data']->meta->message);
            return $response;
        }

        $streamStatusUrl       = "/live_streams/$wowza_id/state";
        $streamStatusResponce  = ATLSP_RunApi($streamStatusUrl,"GET");
        $streamStatus          = $streamStatusResponce->live_stream->state;
        
        if(isset($streamStatus) && $streamStatus == 'stopped' ){
            $response =  ATLSP_generateResponse(false,202,'Live stream already stopped.');
            return $response;
        }


        $stopLiveStreamurl          = "/live_streams/$wowza_id/stop";
        $streamStopData             = ATLSP_RunApi($stopLiveStreamurl,"PUT");

        if(isset($streamStopData->live_stream) && $streamStopData->live_stream->state == 'stopped'){
    
            $inputdata = ['state' => 'stopped', 'stream_status' => 0, 'advertisement_status' => 0];

            $updateData = $this->LiveStreamModel->UpdateData($inputdata, $stream_id, $wowza_id);

            if($updateData){
                    
                $response =  ATLSP_generateResponse(true,200,'Live stream stopped.');
                
            }else{
                
                $response =  ATLSP_generateResponse(false,202,'Live stream not stopped, please try again.');
            }

        }else{

            $response =  ATLSP_generateResponse(false,202,$streamStopData->meta->message);
        }

        return  $response;

    }

    /*Live Stream Statistics*/

    public function LiveStreamStatistics($stream_id, $wowza_id){
        
        $streamData   = $this->GetSingleLiveStream($stream_id, $wowza_id);

        if(empty($streamData['data'])){
             $response =  ATLSP_generateResponse(false,202,'Live stream not found.');
             return $response;
        }

        if(!isset($streamData['data']['wowza_data']->live_stream)){
            $response =  ATLSP_generateResponse(false,202,$streamData['data']['wowza_data']->meta->message);
            return $response;
        }

        $ingestUrl    = "/analytics/ingest/live_streams/$wowza_id";
        $viewerUrl    = "/analytics/viewers/live_streams/$wowza_id";
        
        $ingestoutput = ATLSP_RunApi($ingestUrl, "GET");
        $vieweroutput = ATLSP_RunApi($viewerUrl, "GET");



       if(isset($ingestoutput->live_stream) && isset($vieweroutput->live_stream)){
            $outputData = [
                'inbound' => $ingestoutput->live_stream->connected->value,
                'inbound_bit_rate' => $ingestoutput->live_stream->bytes_in_rate->value,
                'frame_size' => $ingestoutput->live_stream->frame_size->value,
                'frame_rate' => $ingestoutput->live_stream->frame_rate->value,
                'keyframe_interval' => $ingestoutput->live_stream->keyframe_interval->value,
                'unique_views' => $vieweroutput->live_stream->viewers
            ];
        
            $response =  ATLSP_generateResponse(true,200,'Statistic details Found.',$outputData);

        }else{
            $response =  ATLSP_generateResponse(false,202,'Statistic details not found.');
        }

        return $response;
    }

}


