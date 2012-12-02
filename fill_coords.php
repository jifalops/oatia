<?php
    require_once('header.php');

    $sql = 'select member_id from member where latitude is null or longitude is null;';
    $missing = $db->query($sql);
    foreach ($missing as $m) {
        $lat = mt_rand(380000000, 430000000) / 10000000;
        $lon = mt_rand(-850000000, -800000000) / 10000000;
        $sql = 'update member set latitude='.$lat.', longitude='.$lon.', location_time=now() where member_id='.$m['member_id'].';';
        $db->query($sql);
    }
    

    $key = '0iINBr1ZSri9x_vs_3IYtXE8X2dO0plUIlYTa3g';
    $geo = new GoogleGeocoder($key);
    
    $sql =  'select person_id, address, city, state, zip from person where latitude is null or longitude is null;';    
    $missing = $db->query($sql);
    foreach ($missing as $m) {
        $info = $geo->get_info($m['address']." ".$m['city'].", ".$m['state']." ".$m['zip']);
        $sql = 'update person set latitude='.$info['latitude'].', longitude='.$info['longitude'].' where person_id='.$m['person_id'].';';
        $db->query($sql);
    }
    
    $sql =  'select location_id, address, city, state, zip from organization_location where latitude is null or longitude is null;';    
    $missing = $db->query($sql);
    foreach ($missing as $m) {
        $info = $geo->get_info($m['address']." ".$m['city'].", ".$m['state']." ".$m['zip']);
        $sql = 'update organization_location set latitude='.$info['latitude'].', longitude='.$info['longitude'].' where location_id='.$m['location_id'].';';
        $db->query($sql);
    }