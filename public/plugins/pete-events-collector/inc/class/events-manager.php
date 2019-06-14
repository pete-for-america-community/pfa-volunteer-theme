<?php 
class eventsManager {

    private $events; //Array of Events objects

    public function __construct() {
        $this->events = array();
    }

    /**
     * Adds a new Event to the list (if unique)
     * 
     * @return void
     */
    public function addEvent( $event ) {

        if ( DEBUG ) { 
            //error_log( var_export( $event ) );
            //error_log('[Events Collector] Appending Event: ' . $event->getName() ); 
        }

        // verify event is not a duplicate
        if ( $this->verifyUniqueness( $event ) ) {
            array_push( $this->events, $event );
        } else {
            if (DEBUG) { error_log( 'Duplicate Event "' . $event->getName() . '" was not added to Events list.' ); }
        }
        
    }


    /**
    * Creates new Event objects from an array of Events
    */
    public function addEvents( $events ) {

        if (DEBUG) { error_log('adding events: ' . var_export($events, true) ); }

        if ( is_array( $events ) ) {
            foreach( $events as $event ) {
                $this->addEvent( $event );
            }
            return TRUE;
        }
        throw new Exception( '$events to add was not an array, in addEvents()' );
    }


    /**
     * Reorder the internal events list, before returning
     */
    public function sortEventsList() {

        //placeholder @todo
        //provide some capability to reorder based on any number of parameters

    }


    /** 
     * @return the internal list of Events
     */
    public function getEvents() {

        return $this->prepareEventList( $this->events );
    }


    /**
     * @return the internal list of Events, in JSON format
     */
    public function getEventsJSON() {
        $output = array();

        foreach ( $this->events as $event ) {
            //$output[] = $event->getJSON();
            $output[] = $event->getEventRecord();
        }

        return json_encode( $this->prepareEventList( $output ), true );
    }


    /**
     * Wrapper for the Events output
     */
    private function prepareEventList( $list ) {
        //Wrap the output in an additional "events" layer
        return array( 'events' => $list);
    }


    /**
     * Submits a given Event to a series of tests to determine uniqueness
     * 
     * @return boolean
     */
    private function verifyUniqueness( $incomingEvent ) {
        
        foreach( $this->events as $event ) {

            //Make sure they don't share exactly the same name
            if ( $event->getName() == $incomingEvent->getName() ) { return FALSE; }

            //Make sure they don't share exactly the samre source ID
            if ( $event->getOriginalID() == $incomingEvent->getOriginalID() ) { return FALSE; }

        }

        return TRUE;

    }
}