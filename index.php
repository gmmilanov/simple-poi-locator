<?php

#read http://flightphp.com/
require_once 'lib/flight/Flight.php';
#read http://mobiledetect.net/
require_once 'lib/mobile_detect/Mobile_Detect.php';
#require settins file
require_once 'common.php';


Flight::register('db', 'PDO', array('mysql:host='.DBHOST.';dbname=' . DBNAME, DBUSER, DBPASS ));


Flight::route('/', function () {
    /*
    Read https://packagist.org/packages/mobiledetect/mobiledetectlib
    */
    $detect = new Mobile_Detect;
    
    if ($detect->isMobile()) {
        //mobile devices
        Flight::render('mobile');
    } else {
        //desktop computers
        Flight::render('desktop');
    }
});


/*
Finding POIs
lat/lon -> user's positions
radius -> max distance between user ans POI
msr - units -> 0 -> miles ; 1 -> milometetes
*/
Flight::route('/get/@lat/@lng/@radius/@limit/@msr', function ($lat, $lng, $radius, $limit, $msr) {
    //CREATE CONNECTION
    $db = Flight::db();
    //shop query
    // gather the data
    // $earth_radius = $msr == 0 ? 3959 : 6371;
    $msr = (int)$msr;
    $earth_radius = earth_radius($msr);

    //casting data to prevent SQL injection
    $lat = (float)$lat;
    $lng = (float)$lng;
    $radius = (int)$radius;
    $limit = (int)$limit;
    
    //TODO rewite query using param
    $query = <<<_
        SELECT id, name, address , 
        	ROUND( 
        		( {$earth_radius} * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) 
        	, 2 ) AS distance 
        FROM pois 
        HAVING distance < {$radius} 
        ORDER BY distance 
        LIMIT 0 , {$limit} ;

_;
    //only for debug
    // echo $query;
    $stmt = $db->prepare($query);
    $stmt->execute();

    header('Content-type: application/json');
    echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS));

    // close connection
    $db = null;
});


/*
Getting full poi data by poi_id
*/
Flight::route('/poi/@poi_id/@lat/@lng/@msr', function ($poi_id, $lat, $lng, $msr) {
    //CREATE CONNECTION
    $db = Flight::db();
    //poi query
    // gather the data
    $earth_radius = earth_radius($msr);
    
    $query = <<<_
        SELECT id, name, address , 
            ROUND( 
                ( {$earth_radius} * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( lat ) ) ) ) 
                , 2 ) AS distance 
        FROM pois 
        WHERE id = {$poi_id} 
        ORDER BY id 
        LIMIT 1;

_;
    
    $stmt = $db->prepare($query);
    $stmt->execute();

    header('Content-type: application/json');
    echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS));

    $db = null;
});


Flight::start();
