<?php

error_reporting(E_ALL);

if (isset($_GET['_url'])) {
     $_GET['_url'] = strtolower($_GET['_url']);
}

require dirname(__DIR__) . '/apps/bootstrap.php';
$bootstrap = new Bootstrap();
$bootstrap->execInvestigator2();


/* index_micro.php ends here */