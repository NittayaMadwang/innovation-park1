<?php
	include('function.php');
	
	$rooms = array();
	
	$result = GetAllRooms();
	
	if($result->rowCount()>0){
	  $rows = $result->fetchALL();
	  for($i=0; $i<$result->rowCount(); $i++) {
	    
		if($rows[$i]['Active']==1){
		  $room = array();
          $room['id'] = $rows[$i]['RoomID'];
          $room['title'] = $rows[$i]['RoomName'];

          array_push($rooms, $room);	
		  
		}
	  }
	}
	echo json_encode($rooms);
    exit();
	
	/*
	
	OUTPUT
	[
	  {"id":"1","title":"IoT Labs"},
	  {"id":"2","title":"Meeting2"}
	]
	
	*/

?>