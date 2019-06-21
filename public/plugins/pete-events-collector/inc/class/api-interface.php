<?php     
abstract class apiInterface {

    private $endpoint; // String
    private $apiName; // String
    private $response; // JSON object
    private $parsedResponse; // JSON Object


    /**
    * Append source-specific headers to the API request
    * 
    * @return Array(XHTTP Request Headers)
    */
    abstract protected function applySourceAPIHeaders();


    /**
     * Map the external API response to our internal model
     * 
     * @return Array(Events)
     */
    abstract protected function applySourceMappings( $response_items );


    /**
     * Consume the API on creation
     * 
     * @return void
     */
    public function __construct( $endpoint, $apiName ) {
        if (DEBUG) { error_log('API Interface created'); }

        $this->apiName = $apiName;
        $this->endpoint = $endpoint;
        $this->response = $this->fetchEndpoint( $endpoint );
        $this->parsedResponse = $this->parseResponse( $this->response );
    }


    /**
    * Return the parsed response (presumably, event items)
    * 
    * @return WP_Error | Array(Root response items)
    */
    public function getParsedResponse() {
        return $this->parsedResponse;
    }


    /**
     * Determine the passed response is actually valid JSON
     * 
     * @return WP_Error | Array(Root response items)
     */
    protected function processJSON( $json_string ) {

        $json_data = json_decode( $json_string, TRUE );
        //Ensure the response is actual JSON
        if ( ! $json_data ) {
            return new WP_Error(
                '0',
                'API Returned data which was malformed, or not originally JSON',
                array( $json_data, $json_string )
            );
        }

        return $json_data;

    }


    /**
     * Basic filtering of the endpoint in memory for safety
     * 
     * @return WP_Error | String $endpoint
     */
    protected function validateEndpoint( $endpoint ) {
        // Deny any malformed URLS before fetching
        if ( ! filter_var( $endpoint, FILTER_VALIDATE_URL ) ) {
            return new WP_Error( 
                '1', // Error Code
                'Fetch API Data routine was not fed a valid API endpoint', // Error Message 
                $endpoint //Data passed to Error routine
            );
        } 
        if ( DEBUG ) { error_log('endpoint was validated'); }
        return $endpoint;
    }


    /**
     * Ensure the remote API request did not encounter an error, 
     * or produce unexpected results
     * 
     * @return WP_Error | original response
     */
    protected function validateFetchedResults( $response ) { 

        if ( is_wp_error( $response ) ) {
            return new WP_Error(
                '1',
                'Error requesting data in fetchEndpoint: ' . $response->get_error_message(),
                $response
            );
        }
        elseif ( ! is_array( $response )  ) { 
            return new WP_Error( 
                '0', 
                "API Response was malformed/not an array (expected: array of Events).", 
                $response 
            ); 
        }

        return $response;
    }


    /** 
    * Perform an API request
    * 
    * @return String (JSON) API Data
    */
    protected function fetchEndpoint( $endpoint ) {
        if ( DEBUG ) { error_log('fetchEndpoint called'); }
        
        //Basic endpoint validation
        $endpoint = $this->validateEndpoint( $endpoint );
        if ( is_wp_error( $endpoint ) ) { 
            throw new Exception( 
                $endpoint->get_error_message() . ' | data: ' .
                print_r( $endpoint->get_error_data(), true ) 
            );
        }

        //Form the request body, any special auth or protocols here
        $request_headers =  $this->applySourceAPIHeaders();

        // Submit the API Request
        $response = wp_remote_get(
            $endpoint,
            array(
                'headers' => $request_headers
            )
        );
        if ( DEBUG ) { error_log('wp_remote_get performed'); }
        
        //@TODO Handle responses with multiple pages/needing mutliple requests

        //Validate the results can be used
        $response = $this->validateFetchedResults( $response );
        if ( is_wp_error( $response ) ) { 
            throw new Exception ( $response->get_error_message() ); 
        }
        
        if ( DEBUG ) {
            //error_log( '$response: ' . print_r( $response, true )  );
            error_log( '$response["body"]: ' . print_r( $response["body"], true )  );
        }

        //Update
        $this->response = $response['body'];
        return $this->response;
    
    }


    /**
    * Map the external API response to our internal model
    * 
    * @return Array(Events)
    */
    protected function parseResponse( $response ) {
        if (DEBUG) { error_log('parseResponse called - $response: ' . print_r( $response, true ) ); }

        //Ensure response is JSON
        $response_items = $this->processJSON( $response );
        if ( is_wp_error( $response_items ) ) { throw new Exception( $response_items->get_error_message() ); }

        $parsed_events = $this->applySourceMappings( $response_items );

        if ( DEBUG ) { error_log( "Processed events: " . print_r( $parsed_events, true ) ); }

        return $parsed_events;
    }

}