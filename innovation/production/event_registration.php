<?php
	include('function.php');

	CheckAuthenticationAndAuthorization();

	$token = '';
	$eventID = '';
	$eventName = '';
	$period = '';
	$location = '';
	$roomName = '';
	$prStartDate = '';
	$prStopDate = '';
	$active = 0;

	if(isset($_GET['token'])){
		$token = $_GET['token'];

		$result = GetEventByToken($token);
		if($result->rowCount()>0){
		  $row = $result->fetch();
		  $prStartDate = $row['PRStartDate'];
		  $prStopDate = $row['PRStopDate'];
		  $active = $row['EventActive'];

		  if($active==1){
			if(date('Y-m-d')>=$prStartDate && date('Y-m-d')<=$prStopDate){
			  $eventID = $row['EventID'];
			  $eventName = $row['EventName'];
		      $period = $row['Period'];
		      $location = $row['Location'];
			  $roomName = $row['RoomName'];
			}else{
			  // REDIRECT TO EVENT EXPIRED
			  header('location: error-1.php');
			  die();
			}
		  }else{
		    // REDIRECT TO EVENT DEACTIVED
			header('location: error-2.php');
			die();
		  }
		}
	}else{
		// REDIRECT TO INVALID TOKEN
		header('location: error-3.php');
		die();
	}

	if(isset($_POST['submit'])){
	  $result = Registration($_POST['firstname'], $_POST['lastname'], $_POST['jobPosition'], $_POST['organizaton'], $_POST['phone'], $_POST['email'], $_POST['token']);
	  if($result){
	    //echo 'TRUE';
		//header('location: users.php');
		//die();
	  }else{
		//echo 'FALSE';
      }
	}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport"    content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author"      content="Sergey Pozhilov (GetTemplate.com)">

  <title>Registration</title>

  <!--<link rel="shortcut icon" href="assets/images/gt_favicon.png">-->

  <!-- Bootstrap -->
  <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.no-icons.min.css" rel="stylesheet">
  <!-- Icon font -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
  <!-- Fonts -->
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alice|Open+Sans:400,300,700">


  <!-- Custom styles -->
  <link rel="stylesheet" href="css/initio/styles.css">

  <!--[if lt IE 9]> <script src="assets/js/html5shiv.js"></script> <![endif]-->
</head>
<body>
  <header id="header">
    <div id="head" class="parallax" parallax-speed="1">
      <h1 id="logo" class="text-center">
	    <img class="img-img-rounded" src="images/depaLogo.png" alt=""><br>
	    <span class="title" ><?php echo $eventName; ?></span>
	    <span class="tagline"><?php echo $period.', '.$location; ?></span>
	  </h1>
	</div>
	<!--
	<nav class="navbar navbar-default navbar-sticky">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
	    </div>
	  </div>
	</nav>
	-->
  </header>
  <main id="main">
	<div class="container">
	  <div class="row topspace">
		<div class="col-sm-12">
		  <header class="entry-header">
			<h1 class="entry-title">registration</h1>
		  </header>
		  <div class="entry-content">


		  <?php
						if($token!=''){
						  $result = GetAllRegistrationByToken($token);
						  if($result->rowCount()>0){
					   ?>
					    <table id="registrationsDataTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
							  <th class="text-center" width="20px">Signature</th>
                              <th class="text-center" width="20px">No.</th>
                              <th class="text-center" >Firstname</th>
							  <th class="text-center">Lastname</th>
							  <th class="text-center">Position</th>
							  <th class="text-center">Organization</th>
							  <th class="text-center">Phone</th>
							  <th class="text-center">Email</th>
							  <th class="text-center">Signature</th>
                            </tr>
                          </thead>
						  <tbody>
						<?php
							  $rows = $result->fetchALL();
							  for($i=0; $i<$result->rowCount(); $i++) {
						?>
							<tr>
							  <td class="text-center"><a href="form_signature.php?id=<?php echo $rows[$i]['RegisID']; ?>&token=<?php echo $rows[$i]['Token'];?>"><i class="fa fa-pencil" style="color:#337ab7;"></i></a></td>
							  <td class="text-center"><?php echo $i + 1; ?></td>
							  <td><?php echo $rows[$i]['Firstname']; ?></td>
							  <td><?php echo $rows[$i]['Lastname']; ?></td>
							  <td><?php echo $rows[$i]['JobPosition']; ?></td>
							  <td><?php echo $rows[$i]['Organization']; ?></td>
							  <td><?php echo $rows[$i]['Phone']; ?></td>
							  <td><?php echo $rows[$i]['Email']; ?></td>
							  <td><img src='<?php echo $rows[$i]['Signature']; ?>'></td>
							</tr>
						<?php
						      }
						?>
						  </tbody>
                        </table>
						<?php
						    }
						  }
						?>

			<!--

			<form id="frm1" name="frm1" action="" method="post" class="form-horizontal" >
			  <input id="token" name="token" type="hidden" value="<?php //echo $token; ?>">
			  <div class="form-group">
			    <label class="col-sm-2 control-label" style="font-weight:400">Firstname</label>
				<div class="col-sm-10">
				  <input id="firstname" name="firstname" type="text" class="form-control"  placeholder="Firstname">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" style="font-weight:400">Lastname</label>
				<div class="col-sm-10">
				  <input id="lastname" name="lastname" type="text" class="form-control" placeholder="Lastname">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" style="font-weight:400">Job position</label>
				<div class="col-sm-10">
				  <input id="jobPosition" name="jobPosition" type="text" class="form-control" placeholder="Job Position">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" style="font-weight:400">Organizaton</label>
				<div class="col-sm-10">
				  <input id="organizaton" name="organizaton" type="text" class="form-control" placeholder="Organizaton">
				</div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" style="font-weight:400">Phone</label>
				<div class="col-sm-10">
				  <input id="phone" name="phone" type="text" class="form-control" placeholder="Phone">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" style="font-weight:400">Email</label>
				<div class="col-sm-10">
				  <input id="email" name="email" type="email" class="form-control" placeholder="Email">
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button id="submit" name="submit" type="submit" class="btn btn-primary" >Register</button>
				</div>
			  </div>
			</form>
			-->
		  </div>
		</div>
	  </div>
	</div>	<!-- /container -->
  </main><!-- End Main -->
  <!-- footer -->
  <footer id="footer" class="topspace">
	<div class="container">
	  <div class="row">
	    <div class="col-md-3 widget">
		  <h3 class="widget-title">Contact</h3>
		  <div class="widget-body">
		    <p>Tel:+66(0) 7637 9111<br>
			  Fax: +66(0) 7637 9000<br>
			  <a href="mailto:#">Email : phuket@sipa.or.th</a><br>
			  <br>
			  1st floor Academic Services Prince Of Songkla University,<br>
			  Sapan Hin,Phuket Road,Muang,<br>
			  Phuket 83000 Thailand
		    </p>
		  </div>
		</div>
        <!-- Follow me Icon -->
		<div class="col-md-3 widget">
		  <h3 class="widget-title">Follow me</h3>
		  <div class="widget-body">
		    <p class="follow-me-icons">
			  <a href=""><i class="fa fa-twitter fa-2"></i></a>
			  <a href=""><i class="fa fa-dribbble fa-2"></i></a>
			  <a href=""><i class="fa fa-github fa-2"></i></a>
			  <a href=""><i class="fa fa-facebook fa-2"></i></a>
			</p>
		  </div>
		</div>
	  </div> <!-- /row of widgets -->
	</div>
  </footer>

  <footer id="underfooter">
    <div class="container">
	  <div class="row">
	    <div class="col-md-12 widget">
		  <div class="widget-body">
		    <p class="text-right">
			  Copyright &copy; 2017, Digital Economy Promotion Agency<br>
			</p>
		  </div>
		</div>
	  </div> <!-- /row of widgets -->
	</div>
  </footer>
  <!-- End footer -->


  <!-- JavaScript libs are placed at the end of the document so the pages load faster -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <script src="js/initio/template.js"></script>
</body>
</html>
