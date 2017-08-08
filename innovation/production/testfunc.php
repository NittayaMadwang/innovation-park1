<html>
  <head>
  </head>
  <body>
	BEGIN FUNC. <br/>
    <?php
	  include('function.php');
	  
	  try{
		$calId = InsertGoogleEvent('I/O 2017', 'DEPA PHUHET', '2017-02-17T17:00:00+07:00', '2017-02-17T18:00:00+07:00');
	    echo 'CAL ID : '.$calId.'<br/>';
	  }
	  //catch exception
      catch(Exception $e) {
        echo 'EXCEPTION : ' .$e->getMessage();
      }
	?>
	END FUNC. <br/>
  </body>
</html>