const DEBUG = true;
// INC_ACTION_NETWORK should be true to include events from Action Network
const INC_ACTION_NETWORK = false;
// INC_MOBILIZE should be true to include events from Mobilize
const INC_MOBILIZE = true;
// USE_TEST_DATA should be true to use TEST_DATA file instead online data.
const USE_TEST_DATA = false;
const TEST_DATA = "full-test-data.json";

// Options file URI, pluginPath injected by PHP
if (typeof pluginPath !== 'undefined') {
    var mapOptionsFilename = pluginPath;
}

// Load events from server, eventsData is injected by PHP
if (typeof eventsData !== 'undefined') {
    var serverEventsList = eventsData;
}

// The map we're displaying
let map = null;

// All the markers on the map (visible or not)
let markers = [];

// Currently selected control (which should be highlighted)
let currentControl = null;
// All controls, indexed by string name
let controls = [];

const show_all_title = "Show All";
const upcoming_only_title = "Upcoming Only";

// Utility function useful for printing loc structures
// on the console.
function locToString(loc) {
    return "( " + loc.lat() + ", " + loc.lng() + ")";
}

// Build the content for an InfoBubble.
// name: Name of the event (string)
// date: A Date object representing the time of the event (Date).
// address: An array of strings representing the line by line text
//          of the address of the event (string[])
// description: Body of the content. (string)
function buildContentString(name, date, address, link, description) {

    let whenString = "";
    if (date !== null) {
        const dateOptions = { year: "numeric", month: "long", day: "numeric" };
        const formattedDate = date.toLocaleDateString("en-US", dateOptions);
        const timeOptions = { hour: "numeric", minute: "2-digit"};
        const formattedTime = date.toLocaleTimeString("en-US", timeOptions);
        whenString += `<p class="bubbleDate"><i>${formattedDate} at ${formattedTime}</i></p>`;
    }

    let addrString = "";

    if (address !== null) {
        addrString +=  '<p class="bubbleAddr">';
        for (let a of address) {
            if (a !== "") {
                addrString += `${a}<br>`;
            }
        }
        addrString += '<\p>';
    }

    let linkString = "";
    if (link !== null) {
        linkString += `<p class="bubbleLink"><a href=${link} target="_blank">${link}</a></p>`;
    }
    const cs = '<div class="bubbleText">' +
        `<h1 class="bubbleHeader">${name}</h1>` +
        addrString +
        whenString +
        linkString +
        '<div class="bubbleContent">' +
        `<p>${description}</p>` +
        '</div>' +
        '</div>';
    return cs;
}

// mapEvents take an array of events and creates a marker for each one, and an infoBubble (closed by default)
// displaying the information for each event.
// eventList: Array of events.
function mapEvents(eventList) {
    // Now map each event.
    for (let e of eventList.events) {
        let infoBubble = null;
        let marker = null;
        try {
            if (!INC_ACTION_NETWORK && e.source === "Action Network") {
                if (DEBUG) {
                    console.log("Skipping Action Network event: " + e.name);
                }
                continue;
            }

            if (!INC_MOBILIZE && e.source === "Mobilize") {
                if (DEBUG) {
                    console.log("Skipping Mobilize event: " + e.name);
                }
                continue;
            }

            if (e.location === null) {
                if (DEBUG) {
                    console.log("Skipping event " + e.name + " with no location.");
                    continue;
                }
            }
            const loc = new google.maps.LatLng(e.location.lat, e.location.lng);
            let date = null;
            if (typeof e.start_date !== 'undefined') {
                date = new Date(e.start_date);
            }

            if (DEBUG && e.address_lines === null) {
                console.log("No address in event " + e.name);
            }
            const contentString = buildContentString(e.name, date, e.address_lines, e.link, e.description);

            infoBubble = new InfoBubble({
                content: contentString,
                maxWidth: 400,
                minHeight: 10,
                // This gap is necessary to avoid the bubble changing the target
                // and causing a mouseout event (flicker of the bubble)
                pixelOffset: new google.maps.Size(0, -15),
                disableAnimation: true,
                visible: false,
                backgroundClassName: "bubble"
            });

            // WARNING: HACK
            // I'm not sure if this should be the same path or not.  Check with Jared.
            let path = null;

            if (date >= Date.now()) {
                path = "Pete Face.svg";
            } else path = "Past_Pete.svg";

            if (typeof mapOptionsFilename !== 'undefined') {
                path = mapOptionsFilename + path;
            }

            const icon_scale_factor = 3;
            marker = new google.maps.Marker({
                position: loc,
                title: name,
                map: map,
                icon: { url: path, scaledSize: new google.maps.Size(118/icon_scale_factor,158/icon_scale_factor) },
                opacity: 0.7,
                date: date
            });
        } catch (err) {
            // The event structure is missing data we expected.  We can just skip this event.
            if (DEBUG) {
                console.log("Skipping malformed event " + e + " reason: " + err);
            }
            continue;
        }


        marker.addListener("click", function clickListener() {
            if (infoBubble.visible) {
                infoBubble.visible = false;
                infoBubble.close(map, marker);
            } else {
                infoBubble.visible = true;
                google.maps.event.addListenerOnce(infoBubble, 'domready', function(){
                    // WARNING: COMPLETE HACK
                    // infoBubble.e is the compiled name for the content of the bubble.
                    google.maps.event.addDomListener(infoBubble.e, 'click',
                        clickListener);
                });
                infoBubble.open(map,marker);
            }
        });

        markers.push(marker);
    }
}



// showAllMarkers() makes all markers visible on the map.
// Used as the callback for that control listener.
function showAllMarkers() {
    toggleControlHighlight(currentControl, controls[show_all_title]);
    currentControl = controls[show_all_title];
    for (let m of markers) {
        m.setVisible(true);
    }
}

// showFutureEvents() makes all markers in the past invisible.
// Used as the call back for that control listener.
function showFutureEvents() {
    toggleControlHighlight(currentControl, controls[upcoming_only_title]);
    currentControl = controls[upcoming_only_title];
    const now = Date.now();
    for (let m of markers) {
        if (m.date > now) {
            m.setVisible(true);
        } else {
            m.setVisible(false);
        }
    }
}

// ShowControl adds a control to the control panel on the map.
// controlDiv: The control to add to.
// f: The listener callback (function).
// title: User-visible title to display for the control (string)
// returns The controlUI in case you need to use it later.
function ShowControl(controlDiv, f, title) {
    // Set CSS for the control border.
    let controlUI = document.createElement("div");
    controlUI.classList.add('control-panel-element');
    controlUI.title = title;
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    let controlText = document.createElement("div");
    controlText.innerHTML = title;
    controlUI.appendChild(controlText);

    // Setup the click event listeners: simply set the map to Chicago.
    controlUI.addEventListener("click", f);
    controls[title] = controlUI;

    return controlUI;
}

const standard_color = "#fff";
const highlight_color = "#ccc";



// toggleControlHighlight switchs highlight color from one control to another.
// Its legal for prev to be null if nothing was previously highlighted.
// prev: control
// next: control
function toggleControlHighlight(prev, next) {
    if (prev !== null) {
        prev. style.backgroundColor = standard_color;
    }
    next.style.backgroundColor = highlight_color;
}

// setupControl() sets up the control panel on the map.
function setupControl() {
    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    let centerControlDiv = document.createElement("div");
    centerControlDiv.id = "control-panel";
    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(centerControlDiv);

    // Create global controls
    currentControl = ShowControl(centerControlDiv, showAllMarkers, show_all_title);
    ShowControl(centerControlDiv, showFutureEvents, upcoming_only_title);

    toggleControlHighlight(null, currentControl);
}

// drawMap() is called after events are parsed from JSON, then draws
// the markers, and draws the control panel.
// eventList: Array of event objects to mark on the map.
function drawMap(eventList) {
    // Very simple function for now, but we may want many different types of things on this
    // map beyond events.
    mapEvents(eventList);
    setupControl();
}

// getEventJSON() asynchronously gets the event data in JSON format.  It then calls
// a callback with the JSON after its received.
// Currently the JSON is read from the eventmapper.js file.  In the future
// we'll obtain this from a backend in production and use the file for testing purposes only.
// callback: Function to call once the JSON is read. (function)
function getEventJSON(callback) {
    // For now we read this from a file.  Later we"ll make reading from the file test mode
    // and will call the server instead.
    let xobj = new XMLHttpRequest();
    let filename = TEST_DATA;
    if (typeof mapOptionsFilename !== 'undefined') {
        filename = mapOptionsFilename + filename;
    }
    xobj.overrideMimeType("application/json");
    xobj.open("GET", filename, true);
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
            callback(xobj.responseText);
        }
    };
    xobj.send(null);
}

// initMap() creates the map using the style elements specified in map-options.json.
// Its the entry point to this javascript as specified in the maps api script.
// After the map is set up, it initiates retrieval of the JSON, with callback
// to continue with a parsed set of events by calling drawMap().
function initMap() {
    let path = "map-options.json";
    if (typeof mapOptionsFilename !== 'undefined') {
        path = mapOptionsFilename + path;
    }
    $.getJSON(path, function (mapstyle) {
	let mElement = document.getElementById("map");
        map = new google.maps.Map(mElement, {	    
            zoom: 5,
            center: { lat: 37.897548, lng: -97.330796 },
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: true,
            mapTypeControl: false,
            fullscreenControl: true,
            streetViewControl: false,
            rotateControl: false,
            styles: mapstyle,
        });

        if (serverEventsList == null || USE_TEST_DATA) {
            getEventJSON(function (response) {
                let eventList = JSON.parse(response);
                drawMap(eventList);
            });
        } else {
            let eventList = serverEventsList;
            drawMap(eventList);
        }
    })

}
