<?php
	session_start();
	
	include('constant.php');

	// PHP 5.6.28
	
	//CONNECTION
	function PDOConnector(){
	  try {
	    $conn = new PDO('mysql:host='.DB_SER.';dbname='.DB_NAME.'', DB_USR, DB_PWD);
		//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	    return $conn;
	  }catch(PDOException $e){ return null;}
	}
	
	// Authentication
	function Authentication($email, $password){
	  $conn = PDOConnector();
	  $comm = 'SELECT * FROM users WHERE email="'.$email.'" AND password="'.$password.'" AND Active=1 LIMIT 1';
	  
	  $query = $conn->prepare($comm); 
	  $query ->execute();
	  
	  if($query->rowCount()>0){
		$authen = $query -> fetch(PDO::FETCH_OBJ);
		
		$_SESSION['AUTHEN']['UID'] = $authen->UserID;
		$_SESSION['AUTHEN']['UNAME'] = $authen->Name;
		$_SESSION['AUTHEN']['UEMAIL'] = $authen->Email;
		$_SESSION['AUTHEN']['ULEVEL'] = $authen->Level;
		
		return true;
	  }else{ return false; }
	}
	
	// CHECK AUTHENTICATION AND AUTHORIZATION
	function CheckAuthenticationAndAuthorization(){
	  if(!isset($_SESSION['AUTHEN']['UID'])){ 
	    header('location: index.php');
	    die();
	  }
	}
	
	// GET ALL USERS
	function GetAllUsers(){
	  $conn = PDOConnector();
	  $comm = 'SELECT * FROM users';
	  
	  $query = $conn->prepare($comm); 
	  $query ->execute();
	  return $query;
	}
	
	// GET USER
	function GetUser($id){
	  $conn = PDOConnector();
	  $comm = 'SELECT * FROM users WHERE UserID="'.$id.'" LIMIT 1';
	  
	  $query = $conn->prepare($comm); 
	  $query ->execute();
	  return $query;
	}
	
	// INSERT USER
	function InsertUser($name, $email, $password, $level, $active){
	  $conn = PDOConnector();
	  $comm = 'INSERT INTO users(Name, Email, Password, Level, Active) VALUES(:Name, :Email, :Password, :Level, :Active)';
	  $query = $conn->prepare($comm);
	  $result = $query->execute(array(
	    'Name' => $name,
	    'Email' => $email,
	    'Password' => $password,
	    'Level' => $level,
	    'Active' => $active
	  ));
	  return $result;
	}
	
	// UPDATE USER
	function UpdateUser($id, $name, $email, $password, $level, $active){
	  $conn = PDOConnector();
	  $comm = 'UPDATE users SET Name=:Name, Email=:Email, Password=:Password, Level=:Level, Active=:Active WHERE UserID=:UserID';
	  $query = $conn->prepare($comm);
	  $result = $query->execute(array(
	    'Name' => $name,
	    'Email' => $email,
	    'Password' => $password,
	    'Level' => $level,
	    'Active' => $active,
	    'UserID' => $id
	  ));
	  return $result;
	}
	
	// GET ALL ROOMS
	function GetAllRooms(){
      $conn = PDOConnector();
	  $comm = 'SELECT * FROM rooms';
		
      $query = $conn->prepare($comm);
	  $query->execute();
	  return $query;
	}
	
	// GET ROOM
	function GetRoom($id){
	  $conn = PDOConnector();
	  $comm = 'SELECT * FROM Rooms WHERE RoomID="'.$id.'" LIMIT 1';
	  
	  $query = $conn->prepare($comm); 
	  $query ->execute();
	  return $query;
	}
	
	// VALIDATE DUPLICATE EVENT #1
	function ValidateEvent2($eventStart, $eventStop){
	  $conn = pdoconnector();
	  $comm = 'SELECT * FROM events WHERE ((StartDate>=:StartDate) AND (StartDate<=:StopDate) 
						  OR (StopDate<=:StopDate) AND (StopDate>=:StartDate))';
	  $query = $conn->prepare($comm); 
      $query->execute(array(
        'StartDate' => $eventStart,
		'StopDate' => $eventStop
      ));
	  return $query;
	}
	
	// VALIDATE DUPLICATE EVENT #2
	function ValidateEvent(){
	  return true;
	}
	
	// INSERT EVENT
	function InsertEvent($eventName, $period, $location, $eventStart, $eventStop, $prStart, $prStop, $roomID, $roomName, $userID, $active){
	  if (ValidateEvent()==true){
		
		$calId = '';
		if($active==1){
		  try{
		    $calId = InsertCALEvent($eventName, $roomName, $eventStart, $eventStop);
		  }catch(Exception $e) {}
		}
		  
	    $conn = PDOConnector();
	    $comm = 'INSERT INTO events(GoogleEventID, EventName, Period, Location, StartDate, StopDate, PRStartDate, PRStopDate, Token, RoomID, UserID, Active) VALUES(:GoogleEventID, :EventName, :Period, :Location, :StartDate, :StopDate, :PRStartDate, :PRStopDate, :Token, :RoomID, :UserID, :Active)';
	    $query = $conn->prepare($comm);
	    $result = $query->execute(array(
		  'GoogleEventID' => $calId,
	      'EventName' => $eventName,
	      'Period' => $period,
	      'Location' => $location,
		  'StartDate' => $eventStart,
		  'StopDate' => $eventStop,
	      'PRStartDate' => $prStart,
		  'PRStopDate' => $prStop,
		  'Token' => uniqid(),
		  'RoomID' => $roomID,
		  'UserID' => $userID,
	      'Active' => $active
	    ));
	    return $result;
	  } else {
		return false;
	  }
	}
	
	// INSERT EVENT ON CAL
	function InsertCALEvent($summary, $location, $start, $end){
	  /************************************************
	  Copyright 2013 Google Inc.
		
	  Licensed under the Apache License, Version 2.0 (the "License");
	  you may not use this file except in compliance with the License.
	  You may obtain a copy of the License at
		
	    http://www.apache.org/licenses/LICENSE-2.0
		
	  Unless required by applicable law or agreed to in writing, software
	  distributed under the License is distributed on an "AS IS" BASIS,
	  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	  See the License for the specific language governing permissions and
	  limitations under the License.
	  ************************************************/
		
	  include_once '../production/vendor/autoload.php';
	  include_once '../production/examples/templates/base.php';
		
	  /************************************************
	    Make an API request authenticated with a service
	    account.
	  ************************************************/

	  $client = new Google_Client();

		/************************************************
		 ATTENTION: Fill in these values, or make sure you
		 have set the GOOGLE_APPLICATION_CREDENTIALS
		 environment variable. You can get these credentials
		 by creating a new Service Account in the
		 API console. Be sure to store the key file
		 somewhere you can get to it - though in real
		 operations you'd want to make sure it wasn't
		 accessible from the webserver!
		 Make sure the Books API is enabled on this
		 account as well, or the call will fail.
		************************************************/
		
		if ($credentials_file = checkServiceAccountCredentialsFile()) {
			// Set the location manually
			$client->setAuthConfig($credentials_file);
		} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
			// Use the application default credentials
			$client->useApplicationDefaultCredentials();
		} else {
			print missingServiceAccountDetailsWarning();
			return;
		}
		
		$client->setApplicationName("connected-api");
		$client->setScopes(['https://www.googleapis.com/auth/calendar']);
		$service = new Google_Service_Calendar($client);
		
		/************************************************
		 We're just going to make the same call as in the
		 simple query as an example.
		************************************************/
		
		$calendarId = 'connected.api@gmail.com';
		
		$eventStart = date('Y-m-d\TH:i:s', strtotime($start));
		$eventStop = date('Y-m-d\TH:i:s', strtotime($end));
		//$eventStart->setTimezone(new DateTimeZone('Asia/Bangkok'));
		
		
		$event = new Google_Service_Calendar_Event(array(
			'summary' => $summary,
			'location' => $location,
			//'description' => 'A chance to hear more about Google\'s developer products.',
			
			'start' => array(
				'dateTime' => $eventStart.'+07:00',
				//'timeZone' => 'America/Los_Angeles',
			),
			
			'end' => array(
				'dateTime' => $eventStop.'+07:00',
				//'timeZone' => 'America/Los_Angeles',
			),			
		));

		$event = $service->events->insert($calendarId, $event);
		return $event->getId();
	}
	
	// UPDATE EVENT
	function UpdateEvent($id, $calId, $eventName, $period, $location, $eventStart, $eventStop, $prStart, $prStop, $roomID, $roomName, $userID, $active){
	  if (ValidateEvent()==true){
		if($calId!=''){
		  // CAL ID
		  if($active==1){
			try{
			  // UPDATE EVENT ON CAL
			  //UpdateCALEvent($calId, $eventName, $roomName, $eventStart, $eventStop);
			  
			  DeleteCALEvent($calId);
			  $calId = InsertCALEvent($eventName, $roomName, $eventStart, $eventStop);
			  
			}catch(Exception $e) {}
		  } else {
			try{
			  // DELETE EVENT ON CAL
			  DeleteCALEvent($calId);
			}catch(Exception $e) {}
		  }
		}else{
		  if($active==1){
			try{
			  // INSERT EVENT ON CAL
			  $calId = InsertCALEvent($eventName, $roomName, $eventStart, $eventStop);
			}catch(Exception $e) {}
		  } else {
			// DO NOT THING 
		  }
		}
		
	    $conn = PDOConnector();
	    $comm = 'UPDATE events SET GoogleEventID=:GoogleEventID, EventName=:EventName, Period=:Period, Location=:Location, StartDate=:StartDate, StopDate=:StopDate, PRStartDate=:PRStartDate, PRStopDate=:PRStopDate, RoomID=:RoomID, UserID=:UserID, Active=:Active WHERE EventID=:EventID';
	    $query = $conn->prepare($comm);
	    $result = $query->execute(array(
	      'GoogleEventID' => $calId,
	      'EventName' => $eventName,
	      'Period' => $period,
	      'Location' => $location,
		  'StartDate' => $eventStart,
		  'StopDate' => $eventStop,
	      'PRStartDate' => $prStart,
		  'PRStopDate' => $prStop,
		  'RoomID' => $roomID,
		  'UserID' => $userID,
	      'Active' => $active,
	      'EventID' => $id
	    ));
	    return $result;
	  } else{
		return false;  
	  }
	}
	
	// UPDATE EVENT ON CAL
	function UpdateCALEvent($calId, $summary, $location, $start, $end){
		
	  /************************************************
		 Copyright 2013 Google Inc.
		
		 Licensed under the Apache License, Version 2.0 (the "License");
		 you may not use this file except in compliance with the License.
		 You may obtain a copy of the License at
		
		     http://www.apache.org/licenses/LICENSE-2.0
		
		 Unless required by applicable law or agreed to in writing, software
		 distributed under the License is distributed on an "AS IS" BASIS,
		 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
		 See the License for the specific language governing permissions and
		 limitations under the License.
		************************************************/
		
		include_once '../production/vendor/autoload.php';
		include_once '../production/examples/templates/base.php';
		
		/************************************************
		 Make an API request authenticated with a service
		 account.
		************************************************/

		$client = new Google_Client();

		/************************************************
		 ATTENTION: Fill in these values, or make sure you
		 have set the GOOGLE_APPLICATION_CREDENTIALS
		 environment variable. You can get these credentials
		 by creating a new Service Account in the
		 API console. Be sure to store the key file
		 somewhere you can get to it - though in real
		 operations you'd want to make sure it wasn't
		 accessible from the webserver!
		 Make sure the Books API is enabled on this
		 account as well, or the call will fail.
		************************************************/
		
		if ($credentials_file = checkServiceAccountCredentialsFile()) {
			// Set the location manually
			$client->setAuthConfig($credentials_file);
		} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
			// Use the application default credentials
			$client->useApplicationDefaultCredentials();
		} else {
			print missingServiceAccountDetailsWarning();
			return;
		}
		
		$client->setApplicationName("connected-api");
		$client->setScopes(['https://www.googleapis.com/auth/calendar']);
		$service = new Google_Service_Calendar($client);
		
		/************************************************
		 We're just going to make the same call as in the
		 simple query as an example.
		************************************************/
		
		$calendarId = 'connected.api@gmail.com';
		$eventStart = date('Y-m-d\TH:i:s', strtotime($start));
		$eventStop = date('Y-m-d\TH:i:s', strtotime($end));
		//$eventStart->setTimezone(new DateTimeZone('Asia/Bangkok'));
		
		
		$eventUpdate = new Google_Service_Calendar_Event(array(
			'summary' => $summary,
			'location' => $location,
			//'description' => 'A chance to hear more about Google\'s developer products.',
			
			'start' => array(
				'dateTime' => $eventStart.'+07:00',
				//'timeZone' => 'America/Los_Angeles',
			),
			
			'end' => array(
				'dateTime' => $eventStop.'+07:00',
				//'timeZone' => 'America/Los_Angeles',
			),
			
			//'recurrence' => array(
			//	'RRULE:FREQ=DAILY;COUNT=2'
			//),
			
			//'attendees' => array(
			//	array('email' => 'lpage@example.com'),
			//	array('email' => 'sbrin@example.com'),
			//),
			
			//'reminders' => array(
			//	'useDefault' => FALSE,
			//	'overrides' => array(
			//		array('method' => 'email', 'minutes' => 24 * 60),
			//		array('method' => 'popup', 'minutes' => 10),
			//	),
			//),
			
		));
		
		$event = $service->events->get($calendarId, $calId);
		//$event->setSummary($summary);
		
		
		$updatedEvent = $service->events->update($calendarId, $event->getId(), $eventUpdate);
	}
	
	// DELETE EVENT ON CAL
	function DeleteCALEvent($calId){
	  /************************************************
		 Copyright 2013 Google Inc.
		
		 Licensed under the Apache License, Version 2.0 (the "License");
		 you may not use this file except in compliance with the License.
		 You may obtain a copy of the License at
		
		     http://www.apache.org/licenses/LICENSE-2.0
		
		 Unless required by applicable law or agreed to in writing, software
		 distributed under the License is distributed on an "AS IS" BASIS,
		 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
		 See the License for the specific language governing permissions and
		 limitations under the License.
		************************************************/
		
		include_once '../production/vendor/autoload.php';
		include_once '../production/examples/templates/base.php';
		
		/************************************************
		 Make an API request authenticated with a service
		 account.
		************************************************/

		$client = new Google_Client();

		/************************************************
		 ATTENTION: Fill in these values, or make sure you
		 have set the GOOGLE_APPLICATION_CREDENTIALS
		 environment variable. You can get these credentials
		 by creating a new Service Account in the
		 API console. Be sure to store the key file
		 somewhere you can get to it - though in real
		 operations you'd want to make sure it wasn't
		 accessible from the webserver!
		 Make sure the Books API is enabled on this
		 account as well, or the call will fail.
		************************************************/
		
		if ($credentials_file = checkServiceAccountCredentialsFile()) {
			// Set the location manually
			$client->setAuthConfig($credentials_file);
		} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
			// Use the application default credentials
			$client->useApplicationDefaultCredentials();
		} else {
			print missingServiceAccountDetailsWarning();
			return;
		}
		
		$client->setApplicationName("connected-api");
		$client->setScopes(['https://www.googleapis.com/auth/calendar']);
		$service = new Google_Service_Calendar($client);
		
		/************************************************
		 We're just going to make the same call as in the
		 simple query as an example.
		************************************************/
		
		$calendarId = 'connected.api@gmail.com';
		//$eventId = 'u3c40c58fh3c05i21e9i35op7s_20161228T160000Z';
		
		$service->events->delete($calendarId, $calId);
	}
	
	// INSERT ROOM
	function InsertRoom($roomName, $active){
	  $conn = PDOConnector();
	  $comm = 'INSERT INTO rooms(RoomName, Active) VALUES (:RoomName, :Active)';
      $query = $conn->prepare($comm);
	  $result = $query->execute(array(
	    'RoomName' => $roomName,
	    'Active' => $active
	  ));
	  return $result;
	}
	
	// UPDATE ROOM
	function UpdateRoom($id, $roomName, $active){
	  $conn = PDOConnector();
	  $comm = 'UPDATE rooms SET RoomName=:RoomName, Active=:Active WHERE RoomID=:RoomID';
	  $query = $conn->prepare($comm);
	  $result = $query->execute(array(
	    'RoomName' => $roomName,
	    'Active' => $active,
	    'RoomID' => $id
	  ));
	  return $result;
	}
	
	// GET ALL EVENTS
	function GetAllEvents(){
      $conn = PDOConnector();
	  $comm = 'SELECT * FROM events';
		
      $query = $conn->prepare($comm);
	  $query->execute();
	  return $query;
	}
	
	// GET ALL EVENTS BY USERID
	function GetAllEventsByUserID($id){
      $conn = PDOConnector();
	  $comm = 'SELECT events.*, rooms.*, users.*, events.Active AS EventActive FROM events INNER JOIN rooms ON events.RoomID=rooms.RoomID INNER JOIN users ON events.UserID=users.UserID WHERE users.UserID='.$id;
	
	  //return $comm;
	  
      $query = $conn->prepare($comm);
	  $query->execute();
	  return $query;
	}
	
	// GET EVENT
	function GetEvent($id){
	  $conn = PDOConnector();
	  $comm = 'SELECT * FROM events WHERE EventID='.$id;
	  
	  $query = $conn->prepare($comm); 
	  $query ->execute();
	  return $query;
	}
	
	// GET EVENT BY TOKEN
	function GetEventByToken($token){
	  $conn = PDOConnector();
	  $comm = 'SELECT events.*, rooms.*, events.Active AS EventActive FROM events INNER JOIN rooms ON events.RoomID=rooms.RoomID WHERE events.Token="'.$token.'" LIMIT 1';
	  
	  $query = $conn->prepare($comm); 
	  $query ->execute();
	  return $query;
	}
	
	// REGISTRATION
	function Registration($firstname, $lastname, $jobPosition, $organization, $phone, $email, $token){
	  $conn = PDOConnector();
	  $comm = 'INSERT INTO registrations(Firstname, Lastname, JobPosition, Organization, Phone, Email, Token) VALUES(:Firstname, :Lastname, :JobPosition, :Organization, :Phone, :Email, :Token)';
	  
	  $query = $conn->prepare($comm); 
	  $result = $query->execute(array(
	    'Firstname' => $firstname,
		'Lastname' => $lastname,
		'JobPosition' => $jobPosition,
		'Organization' => $organization,
		'Phone' => $phone,
		'Email' => $email,
	    'Token' => $token
	  ));
	  return $result;
	}
	
	// GET ALL REGISTRATION BY TOKEN
	function GetAllRegistrationByToken($token){
      $conn = PDOConnector();
	  $comm = 'SELECT * FROM registrations WHERE Token="'.$token.'"';
		
      $query = $conn->prepare($comm);
	  $query->execute();
	  return $query;
	}
	
	// GET REGISTRATION BY REGISID
	function GetRegistrationByRegisID($id){
      $conn = PDOConnector();
	  $comm = 'SELECT * FROM registrations WHERE RegisID="'.$id.'"';
		
      $query = $conn->prepare($comm);
	  $query->execute();
	  return $query;
	}
	
	// UPDATE SIGNATURE
	function UpdateSignature($id, $signature){
	  $conn = PDOConnector();
	  $comm = 'UPDATE registrations SET Signature=:Signature WHERE RegisID=:RegisID';
	  
	  $query = $conn->prepare($comm); 
	  $result = $query->execute(array(
	    'Signature' => $signature,
	    'RegisID' => $id
	  ));
	  return $result;
	}
?>