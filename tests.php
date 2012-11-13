<?php
	require_once('header.php');	

	LogTest::run();

    echo ('<hr /><hr />');
    
    DatabaseInterfaceTest::run($db);