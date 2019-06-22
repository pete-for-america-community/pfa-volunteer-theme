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
        "start_date" : "1551722400"
        }' 
    */

    public function __construct( $name, $addressLines, $description, $latitude, $longitude, $originalID, $source, $link, $start_date ) {
        error_log('Database Record created');

        $this->name = $name;
        $this->addressLines = $addressLines;
        $this->description = $description;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->originalID = $originalID;
        $this->source = $source;
        $this->link = $link;

        $unix_time = strtotime( $start_date );
        if ( $unix_time ) { 
            $this->start_date = $unix_time;
        } else {
            $this->start_date = "invalid start date";
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
            'start_date' => $this->start_date
            
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
}