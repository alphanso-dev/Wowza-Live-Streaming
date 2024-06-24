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
  ### BroadcastLocationList()
    - Fetches a list of available broadcast locations.
  ### CameraEncoderList()
    - Fetches a list of camera encoders available for streaming.
  ### LiveStreamStore() 
  - **For Create A New Live Stream**
    - The LiveStreamStore function is used to store live stream details in your application. This function takes an array of parameters, validates them, and processes the live stream data accordingly.
    - **Parameters**
      - user_id: (integer, required) The ID of the user creating the live stream.
      - stream_title: (string, required, max: 100) The title of the live stream.
      - broadcast_location: (string, required) The location from where the live stream is broadcast.
      - encoder: (string, required) The encoder used for the live stream.
      - description: (string, nullable, max: 10000) A description of the live stream.
      - image: (string, required) An image associated with the live stream.
      - stream_price: (decimal, nullable, format: 0,2) The price of the live stream.
      - price_currency: (string, required) The currency for the live stream price.
      - stream_date: (date, required) The date of the live stream.
      - stream_time: (string, required) The time of the live stream.
### StreamList()
  - The StreamList function retrieves a list of live streams based on the provided filter criteria. This function supports pagination and allows sorting of the results.
    ```
      public function StreamList(array $filterData = [], bool $pagination=true, int $limit=10, array $order_by=['created_at', 'desc'])
      {
          // Add your code to retrieve and filter the stream list here
      }
    ```
  - **Parameters**
    - filterData: (array) An array of filter criteria to apply to the stream list.
  - **The filterData array can include the following criteria to filter the list of streams:**
    - search_text: (string, optional) Text to search within the stream titles and descriptions.
    - user_id: (integer, optional) Filter streams by the ID of the user who created them.
    - stream_status: (string, optional) Filter streams by their status (e.g., 'live', 'upcoming', 'ended').
  - pagination: (bool) Whether to paginate the results. Defaults to true.
  - limit: (int) The number of results per page when pagination is enabled. Defaults to 10.
  - order_by: (array) An array specifying the column and direction to sort the results. Defaults to ['created_at', 'desc'].
  ```
    $filterData = [
      'user_id' => 1,
      'search_text' => 'Alpha Stream',
    ];

   $streams = $liveStreaming->StreamList($filterData, true, 10, ['created_at', 'desc']);
  ```

# Notes:
  - Replace https://example.com/live-stream-api with the actual endpoint URL provided by your live streaming service provider.
  - Replace your-live-stream-token with the actual token or key required for authentication with the live streaming service.
  - Provide additional usage instructions, parameters, or examples as needed based on the specific functionality and methods your LiveStreamingFunction class provides.
  - This README file should give users and developers a clear understanding of how to install, configure, and utilize your Laravel package for live streaming integration. Adjust the content and sections according      to the specific features and requirements of your package.

