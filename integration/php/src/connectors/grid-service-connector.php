<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function GetRegionFlags($serviceUri, $regionId, $debug = FALSE)
{
    $params 
        = array(
            'SCOPEID' => UUID_ZERO,
            'REGIONID' => $regionId,
            'METHOD' => "get_region_flags");   
            
    return PostToService($serviceUri, http_build_query($params), $debug);            
}

function GetRegionRange($serviceUri, $minX, $minY, $maxX, $maxY, $debug = FALSE)
{
    $params 
        = array(
            'SCOPEID' => UUID_ZERO,
            'XMIN' => $minX,
            'YMIN' => $minY,
            'XMAX' => $maxX,
            'YMAX' => $maxY,
            'METHOD' => "get_region_range");
            
    return PostToService($serviceUri, http_build_query($params), $debug);    
}

?>