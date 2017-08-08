<?php
	include('function.php');
	
	$id = '';
	$token = '';
	$eventID = '';
	$eventName = '';
	$period = '';
	$location = '';
	$roomName = '';
	
	$firstname = '';
	
	if(isset($_GET['id']) && isset($_GET['token'])){
		$id = $_GET['id'];
		$token = $_GET['token'];
		
		$result = GetEventByToken($token);
		if($result->rowCount()>0){
		  $row = $result->fetch();
		  
		  $eventID = $row['EventID'];
		  $eventName = $row['EventName'];
		  $period = $row['Period'];
		  $location = $row['Location'];
		  $roomName = $row['RoomName'];
		  
		  $result = GetRegistrationByRegisID($id);
		  if($result->rowCount()>0){
			$row = $result->fetch();
			
			$firstname = $row['Firstname'];
			$lastname = $row['Lastname'];
			$position = $row['JobPosition'];
			$organization = $row['Organization'];
			$phone = $row['Phone'];
			$email = $row['Email'];
		  }else{
			// REDIRECT TO REGISTRATION NOT FOUND
		  }
		}else{
		  // REDIRECT TO EVENT NOT FOUND
		}
	}else{
		// REDIRECT TO INVALID TOKEN
		header('location: error-3.php');
		die();
	}
	
	if(isset($_POST['save'])){
	  //echo 'ID : '.$_POST['rgistrationID'].'<br/> TOKEN : '.$_POST['token'].'<br/> SIGNATURE : '.$_POST['hdn1'];
	  $result = UpdateSignature($_POST['rgistrationID'], $_POST['hdn1']);
	  if($result){
	    //echo 'TRUE';
		header('location: event_registration.php?token='.$_POST['token']);
		die();
	  }else{
		//echo 'FALSE';
      }
	  
	}
 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/szimek/signature-pad.css">
  
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	
	<style>
	  .login-title {
	    margin-top: 60px;
        color: #FFF;
        font-size: 60px;
        font-weight: 400;
        display: block;
      }
	  .text-center {
        text-align: center;
      }
	</style>

    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-39365077-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
  </head>
  <body onselectstart="return false">
    <h1 class="text-center login-title">Signature</h1>
    <h2 class="text-center">Welcome, <?php echo $firstname.'  '.$lastname?> to Join <?php echo $eventName; ?>.</h2>
    <div id="signature-pad" class="m-signature-pad">
	  <form id="frm1" name="frm1" action="" method="post" class="form-horizontal" >
	    <input id="hdn1" name="hdn1" type="hidden" value="">
		<input id="rgistrationID" name="rgistrationID" type="hidden" value="<?php echo $id; ?>">
		<input id="token" name="token" type="hidden" value="<?php echo $token; ?>">
        <div class="m-signature-pad--body">
          <canvas></canvas>
        </div>
        <div class="m-signature-pad--footer">
          <div class="left">
	        <button id="clear" name="clear" type="button" class="btn btn-primary btn-block" data-action="clear">Clear</button>
          </div>
          <div class="right">
		    <!--<button id="save" name="save" type="button" class="btn btn-primary btn-block" data-action="save-svg">Save</button>-->
	        
            <button type="button" class="btn btn-primary btn-block"  data-action="save-png">Save as PNG</button>
            <button id="save" name="save" type="submit" class="btn btn-primary btn-block" data-action="save-svg">Save</button>
		    
          </div>
        </div>
	  </form>
    </div>
	
    <script src="js/szimek/signature_pad.js"></script>
    <script src="js/szimek/app.js"></script>
  </body>
</html>
