<?php 
/*
{
"identifiers": [
    "action_network:fc36cb3d-a92d-495f-9bb4-8a13d4f05a07"
],
"created_date": "2019-05-18T04:36:54Z",
"description": "<p>[Hosts, please add details for your guests here.]</p>",
"start_date": "2019-05-18T21:30:00Z",
"reminders": [
    {
    "method": "email",
    "minutes": 1440
    }
],
"total_accepted": 1,
"action_network:event_campaign_id": "aafc4b0f-38ff-4cae-8891-8b6dec64b170",
"location": {
    "venue": "Chelsea's Apartment",
    "address_lines": [
    "107 S Mary Avenue"
    ],
    "locality": "Sunnyvale",
    "region": "CA",
    "postal_code": "94086",
    "country": "US",
    "location": {
    "latitude": 37.382277603670104,
    "longitude": -122.04499231614619,
    "accuracy": "Rooftop"
    }
},
"modified_date": "2019-05-18T04:38:51Z",
"status": "confirmed",
"transparence": "opaque",
"visibility": "public",
"guests_can_invite_others": true,
"origin_system": "Action Network",
"title": "Fake Event",
"name": "",
"browser_url": "https://actionnetwork.org/events/fake-event-8",
"instructions": "<p>[Please put detailed instructions for your guests to see here. How to get to the location, ADA accessibility, and parking situation]</p>",
"action_network:hidden": false
}
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
                $this->apiName, //$source, 
                $event['browser_url'], //$link, 
                $event['start_date'], //$start_date, 
                $event['end_date'], //$start_date, 
                $event['location']['locality'], //$town, 
                $event['location']['region'], //$state, 
                $event['location']['postal_code'] //$zip, 
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