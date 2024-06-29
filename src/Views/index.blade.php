<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AlphansoTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/livestream/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/livestream/wowza/css/main.css' )}}" />

</head>

<body>
    {{-- @if($error = \Session::get('error'))
        <div class="alert alert-danger">{{ $error }}</div>   
    @endif
    @if($success = Session::get('success'))
        <div class="alert alert-success">{{ $success }}</div>
    @endif --}}
    <div class="container">
        <h1 class="head_first">Welcome To The Alphanso Live Streaming </h1>
    </div>

    <div class="alert alert-info m-1">
        <p class="m-0">For Live Streaming Please use Google Chrome or Safari Browser.</p>
    </div>
    <div class="m-1" id="transcoder-warning" style="display:none;">
        <div class="alert alert-warning p-2 text-center">
            Please visit <a href="https://cloud.wowza.com">cloud.wowza.com</a> to start your live stream.
        </div>
    </div>
    <div class="invisible m-1" id="error-panel">
        <div class="alert alert-danger alert-dismissible p-2 mb-0">
            <button id="error-panel-close" type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div id="error-messages"></div>
        </div>
    </div>
    <div class="content" id="publish-content">
        <div class="row">
            <div class="col-xl-7 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-black mb-0">Live Streaming Player</h4>
                    </div>
                    <div class="card-body">
                        <div id="publish-video-container" style="position:relative;">
                            <video id="publisher-video" autoplay playsinline muted controls></video>
                            <div id="video-live-indicator" style="left:0.5em">
                                <span id="video-live-indicator-live" class="badge badge-pill badge-danger" style="display:none;">LIVE</span>
                                <span id="video-live-indicator-error" class="badge badge-pill badge-warning" style="display:none;">ERROR</span>
                            </div>
                        </div>
                        <br/>
                        <div class="" id="publish-settings">
                            <form id="publish-settings-form">
                                <input type="hidden" class="form-control" id="sdpURL" name="sdpURL" maxlength="1024" value="{{ isset($streamingData->source_connection_information->sdp_url)?$streamingData->source_connection_information->sdp_url:''  }}" readonly />
                                <input type="hidden" class="form-control" id="applicationName" name="applicationName" maxlength="256" value="{{ isset($streamingData->source_connection_information->application_name)?$streamingData->source_connection_information->application_name:'' }}" readonly />
                                <input type="hidden" class="form-control" id="streamName" name="streamName" maxlength="256" value="{{ $streamingData->source_connection_information->stream_name }}" readonly />
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="camera-list-select">
                                                        Input Camera
                                                    </label>
                                                    <select id="camera-list-select" class="form-control">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2 pl-0">
                                                <button id="camera-toggle" class="control-button" style="margin-top: 30px;">
                                                    <img alt="" class="noll" id="video-off" src="{{ asset('wowza/images/videocam-32px.svg') }}" />
                                                    <img alt="" class="noll" id="video-on" src="{{ asset('wowza/images/videocam-off-32px.svg') }}" style="display:none;"/>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="mic-list-select">
                                                        Input Microphone
                                                    </label>
                                                    <select id="mic-list-select" class="form-control">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2 pl-0">
                                                <button id="mute-toggle" class="control-button" style="margin-top: 30px;">
                                                    <img alt="" class="noll" id="mute-off" src="{{ asset('wowza/images/mic-32px.svg') }}" />
                                                    <img alt="" class="noll" id="mute-on" src="{{ asset('wowza/images/mic-off-32px.svg') }}" style="display:none;"/>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10">
                                        <button id="publish-toggle" type="button" class="btn btn-dark btn-flat mr-1 mb-2 " >Play</button>
                                        <a href="{{ route('stop.live.stream', ['stream_id' => $getLiveStreaming->stream_id, 'wowza_id' => $getLiveStreaming->wowza_id]) }}" class="btn btn-danger btn-flat mr-1 mb-2 stopConfirm">Stop Live Stream</a>

                                    </div>
                                    <?php /* * ?>
                                    <div class="col-2">
                                        <button id="publish-share-link" type="button" class="control-button mt-0">
                                            <img alt="" class="noll" id="mute-off" src="{{ asset('wowza/images/file_copy-24px.svg') }}" />
                                        </button>
                                    </div>
                                    <?php /* */ ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-black mb-0">Live Streaming Data</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="control-label font-weight-bold">Live Streaming Title</label>
                            <label class="control-label form-control">{{ $getLiveStreaming->stream_title }}</label>
                        </div>
                        <div class="form-group">
                            <label class="control-label font-weight-bold">Live Streaming Id</label>
                            <label class="control-label form-control">{!! $getLiveStreaming->id !!}</label>
                        </div>
                        <div class="form-group">
                            <label class="control-label font-weight-bold">Live Streaming Wowza Id</label>
                            <label class="control-label form-control">{!! $getLiveStreaming->wowza_id !!}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <span id="sdpDataTag"></span>
    </div>
    
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/datetime.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/jquery.form.min.js') }}"></script>
    <script type="module" src="{{ asset('vendor/livestream/wowza/publish.js') }}"></script>
    <script type="module" src="{{ asset('vendor/livestream/wowza/play.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/js/custome.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/livestream/wowza/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

   {{--  <script type="text/javascript">
        $(window).on('beforeunload', function(){
            $("#wait").hide();
        });

        $(document).ready(function() {
            
            $('sdpURL').val( "{{ isset($streamingData->source_connection_information->sdp_url)?$streamingData->source_connection_information->sdp_url:''  }}" )
            $('applicationName').val( "{{ isset($streamingData->source_connection_information->application_name)?$streamingData->source_connection_information->application_name:'' }}" )
            $('streamName').val( "{{ $streamingData->source_connection_information->stream_name }}" )
            $("#wait").hide();
        });

    </script> --}}
</body>
</html>
