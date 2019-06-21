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
class APIInterface_ActionNetwork extends apiInterface {


    /**
     * 
     */
    protected function applySourceMappings( $response_items ) {
        //Action Network specific structure here
        $events = $response_items["_embedded"]["osdi:events"];
        $parsed_events = array();

        foreach( $events as $event ) {
            if ( DEBUG ) { error_log('[Events Collector] Creating new Event: ' . $event['title'] ); }
                
            $parsed_events[] = new Event( 
                $event['title'], //$name, 
                $event['location']['address_lines'], //$addressLines, 
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

}