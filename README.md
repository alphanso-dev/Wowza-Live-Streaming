# Alphanso-Tech Live Streaming Package
## This Laravel package provides functionality for live streaming integration.
### Installation
## You can install the package via Composer. Run the following command in your Laravel project :
- composer require alphanso-tech/livestreaming:dev-main
## Publish the package migrations :
- php artisan vendor:publish --tag=migrations
## Run migrations to create the necessary database tables :
- php artisan migrate
## Publish the config File :
- php artisan vendor:publish --tag=livestream-config
## Set the endpoint and live stream token in your .env file
- LIVESTREAM_ENDPOINT=https://example.com/live-stream-api
- LIVESTREAM_TOKEN=your-live-stream-token
# Usage :
## Importing and Using LiveStreamingFunction 
- In your controller or wherever you need to use the live streaming functionality, import the LiveStreamingFunction class and create an object
  use AlphansoTech\LiveStreaming\LiveStreamingFunction;

```
class YourController extends Controller
  {
      protected $LiveStreamingFunction;
  
      public function __construct()
      {
          $this->LiveStreamingFunction = new LiveStreamingFunction();
      }
  
      public function index()
      {
          // Example usage of methods from LiveStreamingFunction class
          $broadcastLocationList = $this->LiveStreamingFunction->BroadcastLocationList();
          $cameraEncoderList = $this->LiveStreamingFunction->CameraEncoderList();
  
          // Use $broadcastLocationList and $cameraEncoderList as needed
      }
  }
```
## Available Methods :
- BroadcastLocationList()
  -> Fetches a list of available broadcast locations.
- CameraEncoderList()
  -> Fetches a list of camera encoders available for streaming.


# Notes:
- Replace https://example.com/live-stream-api with the actual endpoint URL provided by your live streaming service provider.
- Replace your-live-stream-token with the actual token or key required for authentication with the live streaming service.
- Provide additional usage instructions, parameters, or examples as needed based on the specific functionality and methods your LiveStreamingFunction class provides.
- This README file should give users and developers a clear understanding of how to install, configure, and utilize your Laravel package for live streaming integration. Adjust the content and sections according to the specific features and requirements of your package.

