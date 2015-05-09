<?php

ini_set('error_log', 'ussd-app-error.log');

require 'libs/MoUssdReceiver.php';
require 'libs/MtUssdSender.php';
require 'class/operationsClass.php';
require 'log.php';
require 'db.php';

$production=false;

	if($production==false){
		$ussdserverurl ='http://localhost:7000/ussd/send';
	}
	else{
		$ussdserverurl= 'https://api.dialog.lk/ussd/send';
	}

$receiver 	= new UssdReceiver();
$sender 	= new UssdSender($ussdserverurl,'APP_000001','password');
$operations = new Operations();
$Routenumber='';
$BusStopNumber='';
$BusDirection='';

$receiverSessionId = $receiver->getSessionId();
$content 			= 	$receiver->getMessage(); // get the message content
$address 			= 	$receiver->getAddress(); // get the sender's address
$requestId 			= 	$receiver->getRequestID(); // get the request ID
$applicationId 		= 	$receiver->getApplicationId(); // get application ID
$encoding 			=	$receiver->getEncoding(); // get the encoding value
$version 			= 	$receiver->getVersion(); // get the version
$sessionId 			= 	$receiver->getSessionId(); // get the session ID;
$ussdOperation 		= 	$receiver->getUssdOperation(); // get the ussd operation

$responseMsg = array(
    "main" =>  
    "Enter Route Number
"
);

if ($ussdOperation  == "mo-init") { 
   
	try {		
		$sessionArrary=array( "sessionid"=>$sessionId,"tel"=>$address,"menu"=>"main","pg"=>"","others"=>"");
  		$operations->setSessions($sessionArrary);
		$sender->ussd($sessionId, $responseMsg["main"],$address );

	} catch (Exception $e) {
			$sender->ussd($sessionId, 'Sorry error occured try again',$address );
	}
	
}else {

	$flag=0;

  	$sessiondetails=  $operations->getSession($sessionId);
  	$cuch_menu=$sessiondetails['menu'];
  	$operations->session_id=$sessiondetails['sessionsid'];

		switch($cuch_menu ){
		
			case "main": 	// Following is the main menu
					switch ($receiver->getMessage()) {
						case "1":
							$Routenumber="1";
							$operations->session_menu="route1";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayDirection("1"),$address );
							break;
						case "4":
							$Routenumber="4";
							$operations->session_menu="route4";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayDirection("4"),$address );
							break;
						case "87":
							$Routenumber="87";
							$operations->session_menu="route87";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayDirection("87"),$address );
							break;
						case "138":
							$Routenumber="138";
							$operations->session_menu="route138";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayDirection("138"),$address );
							break;
						default:
							$operations->session_menu="main";
							$operations->saveSesssion();
							$sender->ussd($sessionId, $responseMsg["main"],$address );
							break;
					}
					break;
					
			case "route87":
				$sender->ussd($sessionId,'Sorry. Still No data for route number 87',$address);
				break;
			case "route4":
				$sender->ussd($sessionId,'Sorry. Still No data for route number 4',$address);
				break;
			case "route1":
				$sender->ussd($sessionId,'Sorry. Still No data for route number 1',$address);
				break;	
			case "route138":
				switch ($receiver->getMessage()) {
						case "1":
							$BusDirection = "Pettah";
							$operations->session_menu="route138";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayBusStops("route138"),$address);
							break;
						case "2":
							$BusDirection = "Maharagama";
							$operations->session_menu="route138";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayBusStops("route138"),$address);
							break;	
							
						case "3":						
							$operations->session_menu="result";
							$operations->saveSesssion();
							$sender->ussd($sessionId,$operations->getArrayBusStops("route138"),$address);
							break;	
							
						default:
							$sender->ussd($sessionId,'Incorrect option',$address );
							break;						
				}		
				break;
			
			
			

			
			default:
				$operations->session_menu="main";
				$operations->saveSesssion();
				$sender->ussd($sessionId,'Incorrect option',$address );
				break;
		}
	
}
