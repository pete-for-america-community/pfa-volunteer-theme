<?php 
/*
{
    count: 657,
    next: "https://api.mobilize.us/v1/organizations/1297/events?page=2",
    previous: null,
    data: [
        {
            id: 87626,
            description: "Pete Buttigieg is the mayor of South Bend, Indiana and recently launched a presidential exploratory committee. He's coming to Davenport on Monday, March 4th -- come meet Pete!",
            timezone: "America/Chicago",
            title: "Meet Pete in Davenport",
            summary: "With Scott County Dems and Tim Chen",
            sponsor: {
                id: 1297,
                name: "Pete for America",
                slug: "peteforamerica",
                is_coordinated: true,
                is_independent: false,
                is_primary_campaign: true,
                state: "",
                district: "",
                candidate_name: "Pete Buttigieg",
                race_type: "PRESIDENTIAL",
                event_feed_url: "https://www.mobilize.us/peteforamerica/",
                created_date: 1551221043,
                modified_date: 1560609420,
                org_type: "CAMPAIGN"
            },
            featured_image_url: "https://mobilizeamerica.imgix.net/uploads/event/Mayor_Pete_2_20190228155530051688.jpg",
            timeslots: [
                {
                    id: 529725,
                    start_date: 1551722400,
                    end_date: 1551726000,
                    is_full: false
                }
            ],
            location: {
                venue: "Brew in the Village",
                address_lines: [
                    "1104 Jersey Ridge Road",
                    ""
                ],
                locality: "Davenport",
                region: "IA",
                postal_code: "52803",
                location: {
                    latitude: 41.5311966,
                    longitude: -90.5441371
                },
                congressional_district: "2",
                state_leg_district: "93",
                state_senate_district: "47"
            },
            event_type: "MEET_GREET",
            created_date: 1551369346,
            modified_date: 1558565019,
            browser_url: "https://www.mobilize.us/peteforamerica/event/87626/",
            high_priority: false,
            contact: null,
            visibility: "PUBLIC",
            created_by_volunteer_host: false,
            address_visibility: "PUBLIC",
            virtual_action_url: null
        },
*/
class APIInterface_Mobilize extends apiInterface {


    /**
    * 
    */
    protected function applySourceMappings( $response_items ) {
        //Action Network specific structure here
        $events = $response_items["data"];
        $parsed_events = array();

        foreach( $events as $event ) {
            if ( DEBUG ) { error_log('[Events Collector] Creating new Event: ' . $event['title'] ); }
                
            $parsed_events[] = new Event( 
                $event['title'], //$name, 
                $event['location']['address_lines'], //$addressLines, 
                $event['description'], //$description, 
                $event['location']['location']['latitude'], //$latitude, 
                $event['location']['location']['longitude'], //$longitude, 
                'mobilize-' . $event['id'], //Mobilize doesn't prefix its identifier.  id: 87626
                $this->apiName, //$source, 
                $event['browser_url'], //$link
                $event['timeslots'][0]['start_date'], //$start_date
                $event['timeslots'][0]['end_date'], //$end_date
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
    * @return array(XHTTPRequest Headers)
    */
    protected function applySourceAPIHeaders() {
        return array(); // Mobilize is public; no special request headers
    }


    /**
     * Source-specific results paging structure
     * 
     * @return String Endpoint | Boolean
     */
    protected function hasNextPage( $response ) {

        if ( array_key_exists( 'next', $response ) && $response['next'] ) {
            return $response['next'];
        }

        return FALSE;

    }

}