<?php
	include('function.php');
	
	$events = array();
	
	$result = GetAllEvents();
	
	if($result->rowCount()>0){
	  $rows = $result->fetchALL();
	  for($i=0; $i<$result->rowCount(); $i++) {
	    
		if($rows[$i]['Active']==1){
		  $event = array();
          $event['id'] = $rows[$i]['EventID'];
          $event['resourceId'] = $rows[$i]['RoomID'];
		  $eventStart = date('Y-m-d\TH:i:s', strtotime($rows[$i]['StartDate']));
		  $eventStop = date('Y-m-d\TH:i:s', strtotime($rows[$i]['StopDate']));
		  $event['start'] = $eventStart;
		  $event['end'] = $eventStop;
		  $event['title'] = $rows[$i]['EventName'];

          array_push($events, $event);	
		  
		}
	  }
	}
	echo json_encode($events);
    exit();
	
	/*
	
	OUTPUT
	[
	  { "id": "1", "resourceId": "b", "start": "2016-09-07T02:00:00", "end": "2016-09-07T07:00:00", "title": "event 1" },
			{ "id": "2", "resourceId": "c", "start": "2016-09-07T05:00:00", "end": "2016-09-07T22:00:00", "title": "event 2" }
	]
	
	*/
?>