# Alphanso-Tech Live Streaming Package
  ## This Laravel package provides functionality for live streaming integration.
  ### Installation
## You can install the package via Composer. Run the following command in your Laravel project
- composer require alphanso-tech/livestreaming:dev-main
## Publish the package migrations and Config file 
- php artisan vendor:publish --tag=livestream-assets 
## Run migrations to create the necessary database tables :
- php artisan migrate
## Set the endpoint and live stream token in your .env file
- LIVESTREAM_ENDPOINT=https://example.com/live-stream-api
- LIVESTREAM_TOKEN=your-live-stream-token
# Usage
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
## Available Methods 
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
      - **The filterData array can include the following criteria to filter the list of streams**
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
### GetSingleLiveStream ()
  -The GetSingleLiveStream function retrieves the details of a single live stream based on the provided stream ID and Wowza ID.
  ```
    public function GetSingleLiveStream($stream_id, $wowza_id)
    {
        // Add your code to retrieve the details of a single live stream here
    }
  ```
- **Parameters**
  - stream_id: (mixed, required) The ID of the live stream to retrieve.
  - wowza_id: (mixed, required) The Wowza ID associated with the live stream.

  ```
    $stream_id = 3658369383;
    $wowza_id = '7gfqkf6q';
  
    // Call the GetSingleLiveStream function
    $streamDetails = $liveStreaming->GetSingleLiveStream($stream_id, $wowza_id);
  ```
  - The GetSingleLiveStream function is useful for retrieving detailed information about a specific live stream based on its stream ID and Wowza ID. This function can be used to display the details of a live     
    stream in your application.
### StreamUpdate ()
  - The StreamUpdate function updates the details of an existing live stream based on the provided stream ID and Wowza ID.
  - **Parameters**
    - request: (array, required) An array containing the updated live stream details. The array must include the following fields:
    - stream_title: (string, required, max: 100) The title of the live stream.
    - encoder: (string, required) The encoder used for the live stream.
    - description: (string, nullable, max: 10000) A description of the live stream.
    - image: (string, required) An image associated with the live stream.
    - stream_price: (decimal, nullable, format: 0,2) The price of the live stream.
    - price_currency: (string, required) The currency for the live stream price.
    - stream_date: (date, required) The date of the live stream.
    - stream_time: (string, required) The time of the live stream.
  - stream_id: (mixed, required) The ID of the live stream to update.
  - wowza_id: (mixed, required) The Wowza ID associated with the live stream.

  ```
    use AlphansoTech\LiveStreaming\LiveStreamingFunction;
      
      // Create an instance of the LiveStreamingFunction
      $liveStreaming = new LiveStreamingFunction();
      
      // Define the stream ID and Wowza ID
      $stream_id = 3658369383;
      $wowza_id = '7gfqkf6q';
      
      // Define the updated live stream details
      $request = [
          'stream_title' => 'Updated Live Stream Title',
          'encoder' => 'Updated Encoder',
          'description' => 'This is an updated description of the live stream.',
          'image' => 'path/to/updated_image.jpg',
          'stream_price' => 19.99,
          'price_currency' => 'USD',
          'stream_date' => '2023-07-01',
          'stream_time' => '16:00',
      ];
      
      try {
          $liveStreaming->StreamUpdate($request, $stream_id, $wowza_id);
          echo "Live stream updated successfully.";
      } catch (\Exception $e) {
          echo "Error: " . $e->getMessage();
      }
  ```
- The StreamUpdate function allows you to update the details of an existing live stream. It validates the input data to ensure all required fields are provided and formatted correctly. This function is useful for modifying live stream information after it has been created.

### RemoveLiveStream()
  - The RemoveLiveStream function deletes an existing live stream based on the provided stream ID and Wowza ID.
  ```
    public function RemoveLiveStream($stream_id, $wowza_id)
    {
        // Add your code to remove the live stream here
    }
  ```
  - **Parameters**
    - stream_id: (mixed, required) The ID of the live stream to delete.
    - wowza_id: (mixed, required) The Wowza ID associated with the live stream.
   
  - Here is an example of how to call the RemoveLiveStream function with the required parameters:

  ```
    use AlphansoTech\LiveStreaming\LiveStreamingFunction;
    
    // Create an instance of the LiveStreamingFunction
    $liveStreaming = new LiveStreamingFunction();
    
    // Define the stream ID and Wowza ID
    $stream_id = 3658369383;
    $wowza_id = '7gfqkf6q';
    
    try {
        $liveStreaming->RemoveLiveStream($stream_id, $wowza_id);
        echo "Live stream removed successfully.";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }

  ```
  - The RemoveLiveStream function allows you to delete an existing live stream based on its stream ID and Wowza ID. This function is useful for removing live streams that are no longer needed.

### StartLiveStream()
  - The StartLiveStream function initiates a live stream based on the provided stream ID and Wowza ID.

  ```
    public function StartLiveStream($stream_id, $wowza_id)
    {
        // Add your code to start the live stream here
    }
  ```
- **Parameters**
  - stream_id: (mixed, required) The ID of the live stream to start.
  - wowza_id: (mixed, required) The Wowza ID associated with the live stream.
- Here is an example of how to call the StartLiveStream function with the required parameters:
```
  use AlphansoTech\LiveStreaming\LiveStreamingFunction;
  
  // Create an instance of the LiveStreamingFunction
  $liveStreaming = new LiveStreamingFunction();
  
  // Define the stream ID and Wowza ID
  $stream_id = 3658369383;
  $wowza_id = '7gfqkf6q';
  
  try {
      $liveStreaming->StartLiveStream($stream_id, $wowza_id);
      echo "Live stream started successfully.";
  } catch (\Exception $e) {
      echo "Error: " . $e->getMessage();
  }
```
- The StartLiveStream function allows you to initiate an existing live stream based on its stream ID and Wowza ID. This function is useful for starting live streams that have been scheduled or created.

### PublishStream()
  - The PublishStream function publishes a live stream based on the provided stream ID and Wowza ID.

  ```
    public function PublishStream($stream_id, $wowza_id)
    {
        // Add your code to start the live stream here
    }
  ```
- **Parameters**
  - stream_id: (mixed, required) The ID of the live stream to start.
  - wowza_id: (mixed, required) The Wowza ID associated with the live stream.
- Here is an example of how to call the PublishStream function with the required parameters:
```
  use AlphansoTech\LiveStreaming\LiveStreamingFunction;

  // Create an instance of the LiveStreamingFunction
  $liveStreaming = new LiveStreamingFunction();
  
  // Define the stream ID and Wowza ID
  $stream_id = 1;
  $wowza_id = '12345';
  
  try {
      $liveStreaming->PublishStream($stream_id, $wowza_id);
      echo "Live stream published successfully.";
  } catch (\Exception $e) {
      echo "Error: " . $e->getMessage();
  }
```
- The PublishStream function allows you to publish an existing live stream based on its stream ID and Wowza ID. This function is useful for making live streams available for viewing.

### StopLiveStream()
  - The StopLiveStream function stops a live stream based on the provided stream ID and Wowza ID.

  ```
    public function StopLiveStream($stream_id, $wowza_id)
    {
        // Add your code to start the live stream here
    }
  ```
- **Parameters**
  - stream_id: (mixed, required) The ID of the live stream to start.
  - wowza_id: (mixed, required) The Wowza ID associated with the live stream.
- Here is an example of how to call the StopLiveStream function with the required parameters:
```
   use AlphansoTech\LiveStreaming\LiveStreamingFunction;
  
  // Create an instance of the LiveStreamingFunction
  $liveStreaming = new LiveStreamingFunction();
  
  // Define the stream ID and Wowza ID
  $stream_id = 1;
  $wowza_id = '12345';
  
  try {
      $liveStreaming->StopLiveStream($stream_id, $wowza_id);
      echo "Live stream stopped successfully.";
  } catch (\Exception $e) {
      echo "Error: " . $e->getMessage();
  }
```
- The StopLiveStream function allows you to stop an existing live stream based on its stream ID and Wowza ID. This function is useful for ending live streams that are currently in progress.

### LiveStreamStatistics()
  - The LiveStreamStatistics function retrieves statistics for a live stream based on the provided stream ID and Wowza ID.

  ```
    public function LiveStreamStatistics($stream_id, $wowza_id)
    {
        // Add your code to start the live stream here
    }
  ```
- **Parameters**
  - stream_id: (mixed, required) The ID of the live stream to start.
  - wowza_id: (mixed, required) The Wowza ID associated with the live stream.
- Here is an example of how to call the LiveStreamStatistics function with the required parameters:
```
  use AlphansoTech\LiveStreaming\LiveStreamingFunction;
  
  // Create an instance of the LiveStreamingFunction
  $liveStreaming = new LiveStreamingFunction();
  
  // Define the stream ID and Wowza ID
  $stream_id = 1;
  $wowza_id = '12345';
  
  try {
      $statistics = $liveStreaming->LiveStreamStatistics($stream_id, $wowza_id);
      echo "Live stream statistics retrieved successfully.";
      print_r($statistics);
  } catch (\Exception $e) {
      echo "Error: " . $e->getMessage();
  }
```
- The LiveStreamStatistics function allows you to retrieve detailed statistics for an existing live stream based on its stream ID and Wowza ID. This function is useful for monitoring the performance and engagement of live streams.
   

# Notes
  - Replace https://example.com/live-stream-api with the actual endpoint URL provided by your live streaming service provider.
  - Replace your-live-stream-token with the actual token or key required for authentication with the live streaming service.
  - Provide additional usage instructions, parameters, or examples as needed based on the specific functionality and methods your LiveStreamingFunction class provides.
  - This README file should give users and developers a clear understanding of how to install, configure, and utilize your Laravel package for live streaming integration. Adjust the content and sections according      to the specific features and requirements of your package.

