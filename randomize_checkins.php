<?php
    require_once('header.php');

    $sql = 'select member_id from member;';
    $members = $db->query($sql);
    foreach ($members as $m) {
        $lat = mt_rand(413000000, 419000000) / 10000000;
        $lon = mt_rand(-839000000, -833000000) / 10000000;
        $sql = 'update member set latitude='.$lat.', longitude='.$lon.', location_time=now() where member_id='.$m['member_id'].';';
        $db->query($sql);
    }