<?php     
    include_once("geophp/geoPHP.inc");
    include_once("connection.php");
    
    // CHANGE MySQL TO JSON :
    $SQL = "SELECT cat, name, astext(shape) as shape FROM airports";
    $result = mysql_query($SQL, $connection);
    $features = array();    
    while($row = mysql_fetch_array($result)){
        $geom = geoPHP::load($row["shape"],'wkt');
        $json = $geom->out('json');
        $features[] = array( 
        			"type" => "Feature",
        			"properties" => array(
		        			"cat" => $row["cat"],
		        			"name" => $row["name"], 
	        				"style" => array(
	        					"radius"=> 8,
					            "fillColor"=> "#ff7800",
					            "color"=> "#000",
					            "weight"=> 1,
					            "opacity"=> 1,
					            "fillOpacity"=> 0.8
        					),
        					"popupContent"=> $row["name"],
	        			),
        			"geometry" => json_decode($json),
	        );
    }
    $feature_collection = array(
    		"type" => "FeatureCollection",
    		"features" => $features,
    	);
    echo json_encode($feature_collection, true);
    
?>
