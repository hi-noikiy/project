<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 14-7-6
 * Time: 下午10:43
 */
include 'path.php';

$sumDb    = db('analysis');
$souceDb = db('gamedata');
$pl = new PlayerLevel($souceDb,$sumDb);
$pl->run();