<?php
define("LOGGEDIN", "Hello world.");
session_start();
/*
require "fb.php";
$facebook = new Facebook(array(  'appId'  => 'YOUR_APP_ID',  'secret' => 'YOUR_APP_SECRET',));
$req = $facebook->getSignedRequest();
if( $req["page"]["liked"] || $_SESSION['userId'])
*/
include "game.php";
/*else {
echo "<h2 style='text-align:center;top:40%;'>Please Like Our Facebook Page to Start Game</h2>";
echo "<h2 style='text-align:center;top:50%;'>வணக்கம், எங்களது பாசெபூக் பக்கத்தை லைக் செய்த பின்பு விளையாடவும்.</h2>";
}
*/
?>