<?php 
/*
`"event" : {
    "name": "Chelsea's Place",
    "address_lines":[
        "107 S Mary Avenue",
        "San Jose, CA 95003"
        ]
    "description" : "This will be a great event where we watch Pete arm-wrestle Trump on live TV.  Come join us for a great party including yummy snacks."
    "location": {
        "lat" : 37.245786218032435,
        "lng" : -121.78339102296239
    }
    }' 
*/
class APIInterface_ANEventCampaign extends apiInterface {
    /**
    * Consume the API on creation
    * 
    * @return void
    */
    public function __construct( $feedsEndpoint, $apiName ) {
        if (DEBUG) { error_log('API Interface created'); }

        //$feeds_response = $this->fetchEndpoint( $feedsEndpoint );
        //$parsed_feeds_response = $this->parseResponse( $feeds_response );
        //if (DEBUG) { error_log( '$parsed_feeds_response:' .  var_export($parsed_feeds_response, true ) ); }
        //if (DEBUG) { error_log( '$parsed_feeds_response["identifiers"]:' .  print_r($parsed_feeds_response["identifiers"], true ) ); }
        
        //$eventsCampaignEndpoint = $parsed_feeds_response["osdi:events"];

        //$endpoint =   filter_var( $this->response["_links"]["osdi:events"]["href"], FILTER_VALIDATE_URL ) ? filter_var( $this->response["_links"]["osdi:events"]["href"], FILTER_VALIDATE_URL ) : $this->response;

        $this->apiName = $apiName;
        $this->endpoint = $endpoint;
        $this->parsedResponse = $this->processEndpoint( $endpoint );
    }



    /**
    * 
    */
    protected function applySourceMappings( $response_items ) {
        //Action Network specific structure here
        $events = $response_items["osdi:events"];
        //if (DEBUG) { error_log( '$response_items: ' . print_r( $response_items, true) ); }
        if (DEBUG) { error_log( '$events: ' . print_r( $events, true) ); }

        //$events_feed = $response_items["_links"]["osdi:events"]["href"];

        //$events = $response_items;

        //error_log( 'incoming $event_feed: ' . var_export( $events_feed, true ) );


            //$events = 

            $parsed_events = array();

            foreach( $events as $event ) {
                if ( DEBUG ) { error_log('[Events Collector] Creating new Event: ' . $event['title'] ); }
                    
                $parsed_events[] = new Event( 
                    $event['title'], //$name, 
                    $event['address_lines'], //$addressLines, 
                    $event['description'], //$description, 
                    $event['location']['location']['latitude'], //$latitude, 
                    $event['location']['location']['longitude'], //$longitude, 
                    $event['identifiers'][0], //$originalID
                    $this->apiName //$source, 
                );
            }

            return $parsed_events;



    }


    /** 
    * Source-specific request headers required by the API
    * 
    * @return array(XHTPPRequest Headers)
    */
    protected function applySourceAPIHeaders() {
        return array(
            'OSDI-API-Token' => ACTION_NETWORK_API_KEY
        );
    }


    /**
    * Source-specific results paging structure
    * 
    * @return Boolean
    */
    protected function hasNextPage( $response ) {

        return FALSE;

    }

}