<?php
$type = '10_minutes';
$opts=array(
    'http'=>array(
        'method'=>'POST',
        'header'=>"Content-type: application/x-www-form-urlencoded/r/n",
    ),
);
$context=stream_context_create($opts);

$html=file_get_contents('http://127.0.0.1/corn/bat?type='.$type,false,$context);

echo $html;