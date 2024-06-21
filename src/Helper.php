<?php
	

	/* BrodCast Location List */

	if(!function_exists('ATLSP_BroadcastLocation') ){
		function ATLSP_BroadcastLocation(){

			$broadcast_location = [
				"asia_pacific_australia"	=> "Asia Pacific Australia",
				"asia_pacific_india"		=> "Asia Pacific India",
				"asia_pacific_japan"		=> "Asia Pacific Japan",
				"asia_pacific_singapore"	=> "Asia Pacific Singapore",
				"asia_pacific_s_korea"		=> "Asia Pacific South Korea",
				"asia_pacific_taiwan"		=> "Asia Pacific Taiwan",
				"eu_belgium"				=> "Europ Belgium",
				"eu_germany"				=> "Europ Germany",
				"eu_ireland"				=> "Europ Ireland",
				"south_america_brazil"		=> "South America Brazil",
				"us_central_iowa"			=> "US Central Iowa",
				"us_east_s_carolina"		=> "US East Carolina",
				"us_east_virginia"			=> "US East Virginia",
				"us_west_california"		=> "US West California",
				"us_west_oregon"			=> "US West Oregon"
			];
			return $broadcast_location;
		}
	}


    /* Camera Encoder List Function*/
	if(!function_exists('ATLSP_CameraEncoder') ){
		function ATLSP_CameraEncoder(){
			$camera_encoder = [
				"other_webrtc"				=> "Webrtc",
				"media_ds"					=> "Media DS",
				"axis"						=> "Axis",
				"epiphan"					=> "Epiphan",
				"hauppauge"					=> "Hauppauge",
				"jvc"						=> "JVC",
				"live_u"					=> "Live U",
				"matrox"					=> "Matrox",
				"newtek_tricaster"			=> "Newtek Tricaster",
				"osprey"					=> "Osprey",
				"sony"						=> "Sony",
				"telestream_wirecast"		=> "Telestream Wirecast",
				"teradek_cube"				=> "Teradek Cube",
				"vmix"						=> "Vmix",
				"x_split"					=> "X Split",
				"ipcamera"					=> "ip Camera",
				"other_rtmp"				=> "Other RTMP",
				"other_rtsp"				=> "Other RTSP",
				"other_srt"					=> "Other SRT",
				"other_udp"					=> "Other UDP",
				// "file"						=> "File",
			];
		
			return $camera_encoder;   
		}
	}

		
	/* Call Api Using Curl Function*/

	if(!function_exists('ATLSP_RunApi') ){
		function ATLSP_RunApi(string $url, string $method, array $data = []){
			$wowza_live_stream_api_endpoint = config('livestream.livestream_endpoint');
	
			$wowza_live_stream_api_header = [
				"Content-Type:"  	. "application/json",
				"charset:"			. "utf-8",
				"Authorization: Bearer ". config('livestream.livestream_token')
			];
	
			$final_url = $wowza_live_stream_api_endpoint.$url;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST , $method);
			curl_setopt($ch, CURLOPT_URL,$final_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $wowza_live_stream_api_header);
			if(count($data) > 0 && ($method == 'POST' || $method == 'PATCH')){
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			}
	
			$server_output = curl_exec($ch);
			$err = curl_error($ch);
			curl_close ($ch);
			$output = json_decode($server_output);
			return $output;
		}

	}



	/*Function For Response Update*/
	if(!function_exists('ATLSP_generateResponse') ){
		function ATLSP_generateResponse($status,$statusCode,$message,$data = [])
		{
			return [
				'data' => $data,
				'message' => $message,
				'status_code' => $statusCode,
				'status' => $status
			];
		}

	}


	/* Validation Response - request_type = store or update */
	
	if(!function_exists('ATLSP_RequestValidation')){
		function ATLSP_RequestValidation($request, $request_type='store'){
			if($request_type == 'store'){
				$validator = Validator::make($request, [
					'user_id'               => 'required|integer',
					'stream_title'			=> 'required|max:100',
					'broadcast_location'	=> 'required',
					'encoder'		        => 'required',
					'description'			=> 'nullable|max:10000',
					'image'					=> 'required',
					'stream_price'			=> 'nullable|decimal:0,2',
					'price_currency'        => 'required',
					'stream_date'			=> 'required|date',
					'stream_time'			=> 'required',
				],[
					'stream_title.required' => 'stream title field is required'
				]);
			}else{
				$validator = Validator::make($request, [
					
					'stream_title'			=> 'required|max:100',
					'encoder'		        => 'required',
					'description'			=> 'nullable|max:10000',
					'image'					=> 'required',
					'stream_price'			=> 'nullable|decimal:0,2',
					'price_currency'        => 'required',
					'stream_date'			=> 'required|date',
					'stream_time'			=> 'required',
				],[
					'stream_title.required' => 'stream title field is required'
				]);
			}
			if ($validator->fails()) {
				$response = ['status' => false, 'message' => 'Validation Failed.', 'data' => $validator->errors()];
			}else{
				$response = ['status' => true, 'messgae' => 'Validation Success.'];
			}
			return $response;
		}
	}


	/*Rendom String Genrate Function*/

	if(!function_exists('ATLSP_RendomString')){

		function ATLSP_RendomString($size=10, $type='mix'){
			/* Type : 'number','string','mix' */
			$size = $size==null?10:$size;
			$code = '';
			if ($type == 'number'){
				$akeys = range('0', '9');
				for ($i = 0; $i < $size; $i++) {
					$code .= $akeys[array_rand($akeys)];
				}
			} elseif ($type == 'string') {
				$akeys = range('A', 'Z');
				$bkeys = range('a', 'z');
				$ckeys = array_merge($akeys,$bkeys);
				for ($i = 0; $i < $size; $i++) {
					$code .= $ckeys[array_rand($ckeys)];
				}
			}else{
				$code = Str::random($size);
			}
			return str_shuffle($code);
		}
	}


/*Upload Image Function*/

if(!function_exists('ATLSP_UploadCustomeImage')){

	function ATLSP_UploadCustomeImage($image, $image_name='', $storage_disk="public", $upath='', $prefix='', $type='resize', $width=512, $height=512) {
		$path = ($upath == '') ? 'images/' : $upath;
	
		if($storage_disk == 's3') {
			$storepath = \Storage::disk('s3')->path($path);
		} else {
			$storepath = \Storage::disk('public')->path($path);
		}
	
		if (!is_dir($storepath)) {
			\File::makeDirectory($storepath, 0775, true);
		}
	
		$image_name	= ($image_name == '') ? RendomString(10) : $image_name;
		$imageName	= ($prefix != '') ? $prefix . '-' : '';
		$imageName .= $image_name . '.' . $image->getClientOriginalExtension();
	
		if ($type == 'crop') {
			$upload_image = Image::make($image->getRealPath())->fit($width, $height, function ($constraint) {
				$constraint->aspectRatio();
			});
		} else {
			$upload_image = Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
				$constraint->aspectRatio();
			});
		}
		if($storage_disk == 's3') {
			Storage::disk('s3')->put($path . '/' . $imageName, $upload_image->stream());
		} else {
			Storage::disk('public')->put($path . '/' . $imageName, $upload_image->stream());
		}
		$post_image = ['image_path' => $path . '', 'image_name' => $imageName];
	
		return json_encode($post_image);
	}
}

