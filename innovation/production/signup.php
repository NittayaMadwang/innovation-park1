<?php
	include('function.php');

	if(isset($_POST['signup'])){
		$result = InsertUser($_POST['username'],$_POST['email'], $_POST['password'],2,0);
	  if($result){
		  header('location: signup.php?result=1');
		die();
	  }
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Innoivation Park | </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">

  </head>

  <body class="login">

    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
		<div class="form login_form">	  
			<section class="login_content">
			 <form id="frm2" name="frm2" action="" method="post">
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" name="username" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control"  name="email" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required="" />
              </div>
              <div>
                <!--<button class="btn btn-success submit btn-block" href="index.php">Save</button>-->
				<button class="btn btn-success submit btn-block" type="submit"  id="signup" name="signup">Save</button>
			  </div>
              <div class="clearfix"></div>
              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="index.php" class="to_register"> Sign in </a>
                </p>
                <div class="clearfix"></div>
                <br />
              </div>
             </form>
			</div>
			</section>
		</div>
	 </div>
    <!-- Modal -->
    <div id="warning" name="warning" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
              <h4 id="title" name="title" class="modal-title" ></h4>
            </div>
            <div id="msg" name="msg" class="modal-body"></div>
        <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="goindex" id ="goindex "data-dismiss="modal" onClick="location.href='index.php'">Close</button>
            </div>
          </div>
        </div>
      </div>
    <!-- jQuery -->
      <script src="../vendors/jquery/dist/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
      <!-- FastClick -->
      <script src="../vendors/fastclick/lib/fastclick.js"></script>
      <!-- NProgress -->
      <script src="../vendors/nprogress/nprogress.js"></script>
      <!-- bootstrap-progressbar -->
      <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
      <!-- iCheck -->
      <script src="../vendors/iCheck/icheck.min.js"></script>
      <!-- PNotify -->
  	<!--
      <script src="../vendors/pnotify/dist/pnotify.js"></script>
      <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
      <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
  	-->

      <!-- Custom Theme Scripts -->
      <script src="../build/js/custom.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        var result = getParameterByName("result");
      if (result != null){
        if (result == 1){
			 $("#title").html('Succress');
			 $("#msg").html('<p>Succressfull for create account.</p>');
             $('#warning').modal('show'); 
		  }
      }
        });

      function getParameterByName(name, url) {
        if (!url) {
        url = window.location.href;
      }
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
      }

      function validate(){

      }
    </script>
  </body>
</html>
