<?php 
class Event {

    public $name;
    public $addressLines = array();
    public $description;
    public $latitude;
    public $longitude;
    public $originalID;
    public $source;
    public $link;
    public $start_date;
    public $end_date;
    public $town;
    public $state;
    public $zip;

    /*

    `"event" : {
        "originalID" : "87626",
        "name": "Chelsea's Place",
        "address_lines":[
            "107 S Mary Avenue",
            "San Jose, CA 95003"
            ]
        "description" : "This will be a great event where we watch Pete arm-wrestle Trump on live TV.  Come join us for a great party including yummy snacks.",
        "location": {
            "lat" : 37.245786218032435,
            "lng" : -121.78339102296239
        },
        "source" : "Action Network",
        "link" :  "https://www.mobilize.us/peteforamerica/event/87626/",
        "start_date" : 1551722400,
        "end_date" : 1551722400
        }' 
    */

    public function __construct( $name, $addressLines, $description, $latitude, $longitude, $originalID, $source, $link, $start_date, $end_date, $town, $state, $zip ) {
        error_log('Database Record created');

        $this->name = $name;
        $this->addressLines = $addressLines;
        $this->description = $description;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->originalID = $originalID;
        $this->source = $source;
        $this->link = $link;
        $this->town = $town;
        $this->state = $state;
        $this->zip = $zip;

        //Build the final address line as if a letter
        $this->addressLines[] = $this->town . ', ' . $this->state . ' ' . $this->zip;

        // Determine if the api's original time format was UNIX or one of many string formats
        $this->state_date = $this->parepareTime( $this->state_date );

        if ( ! $end_date ) { 
            $this->end_date = null; 
        } else {
            $this->end_date = $this->parepareTime( $this->state_date );
        }
        
    }


    /**
     * Output the record in our internal format
     */
    public function getEventRecord() {

        $event_data = array(
            
            'originalID' => $this->originalID,
            'name' => $this->name,
            'address_lines' => $this->addressLines,
            'description' => $this->description,
            'location' => array(
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ),
            'source' => $this->source,
            'link' => $this->link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'town' => $this->town,
            'state' => $this->state,
            'zip' => $this->zip

            
        );
    
        return $event_data;
    }

    public function getName() {
        return $this->name;
    }

    public function getSource() {
        return $this->source;
    }

    public function getOriginalID() {
        return $this->originalID;
    }

    private function prepareTime( $unknown_time ) {
        $unix_time = strtotime( $unknown_time );
        if ( $unix_time ) { 
            return date( DATE_W3C, $unix_time );
        } else {
            return date( DATE_W3C, $unknown_time );
        }
    }
}