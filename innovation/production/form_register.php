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

  <main id="main">
	<div class="container">
	  <div class="row topspace">
		<div class="col-sm-8 col-sm-offset-2">
		  <header class="entry-header">
			<h1 class="entry-title">registration</h1>
		  </header>
		  <div class="entry-content">
			<form id="frm1" name="frm1" action="" method="post" class="form-horizontal" >
			  <input id="token" name="token" type="hidden" value="<?php echo $token; ?>">
			  <div class="form-group">
			    	<label class="col-sm-2 control-label" style="font-weight:400">Firstname</label>
						<div class="col-sm-10">
				  			<input id="firstname" name="firstname" type="text" class="form-control has-error has-feedback"  placeholder="Firstname">
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
						<div class="col-sm-10 ">
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
					<div class="col-sm-10 ">
				  		<input id="email" name="email" type="email" class="form-control" placeholder="Email">
					</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button id="submit" name="submit" type="submit" class="btn btn-primary" >Register</button>
				</div>
			  </div>
			</form>
		  </div>
		</div>
	  </div>

 <!-- Modal Warnning -->
  <div id="warningModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
			<!-- Modal content-->
    	<div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal">&times;</button>
        	<h4 class="modal-title">Notification</h4>
      	</div>
      	<div class="modal-body">
					<p id="notifMsg" name="notifMsg"></p>
      	</div>
      	<div class="modal-footer">
        	<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      	</div>
    	</div>
  	</div>
	</div><!-- // Modal -->
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
