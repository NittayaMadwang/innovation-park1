<?php
	include('function.php');

	CheckAuthenticationAndAuthorization();

	$roomName = '';
	$active = '';

	if (!isset($_GET['id'])){
	  $command = 'INSERT';
	  //echo $command;
	}else{
	  $command = 'UPDATE';
	  // echo $command;
	}
    if(isset($_POST['submit'])){
		if($command=='INSERT'){
		  $result = InsertRoom($_POST['roomName'], isset($_POST['active']) ? 1 : 0);
		  if($result){
		    //echo 'TRUE';
		    header('location: rooms.php');
		    die();
		  }else{
		    //echo 'FALSE';
		  }
		}else{
		  $result = UpdateRoom($_GET['id'], $_POST['roomName'], isset($_POST['active']) ? 1 : 0);
		  if($result){
		    //echo 'TRUE';
		    header('location: rooms.php');
		    die();
		  }else{
		    //echo 'FALSE';
		  }
		}
	}else{
      if($command == 'UPDATE'){
		$result = GetRoom($_GET['id']);
		if($result->rowCount()>0){
		  $row = $result->fetch();
		  $roomName = $row['RoomName'];
		  $active = $row['Active'];
		}
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

    <title>Innovation Park | </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

	<!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

	<!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <?php include('menu.php'); ?>
          </div>
        </div>

        <?php include('nav.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
		  <div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
			  <div class="x_panel">
                <div class="x_title">
                  <h2>Rooms <small>  View | Active | Deactive  </small></h2>
				  <div class="pull-right">
                    <button class="btn btn-success" data-toggle="modal" title="Add Room" data-target="#roomModal">+</button>
              	  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
				    <?php
				      $result = GetAllRooms();
					  if($result->rowCount()>0){
				    ?>
					<table id="roomsDataTable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                      <thead>
                        <tr>
					      <th class="text-center" width="20px">Status</th>
						  <th class="text-center" width="20px">Edit</th>
                          <th class="text-center" width="20px">No.</th>
                          <th class="text-center">Room Name</th>
                        </tr>
                      </thead>
					  <tbody>
						<?php
						  $rows = $result->fetchALL();
						  for($i=0; $i<$result->rowCount(); $i++) {
						?>
						<tr>
						  <td class="text-center"><?php if($rows[$i]['Active']==1) echo '<i class="fa fa-check" style="color:#16a085;"></i>'; else echo '<i class="fa fa-close" style="color:#d9534f;"></i>'; ?></td>
						  <td class="text-center"><a href="rooms.php?id=<?php echo $rows[$i]['RoomID']; ?>"><i class="fa fa-pencil" title="Edit Room" style="color:#337ab7;" ></i></a></td>
						  <td><?php echo $i + 1; ?></td>
						  <td><?php echo $rows[$i]['RoomName']; ?></td>
						</tr>
						<?php
						  }
						?>
					  </tbody>
                    </table>
					<?php
					  }
					?>
                  </div>
                </div>
              </div>
			</div>
		  </div>

		  <!-- Modal From -->
		  <div class="modal fade" id="roomModal" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
			  <div class="modal-content">
			    <div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title" id="myModalLabel">Room Form <small> Create | Modify </small></h4>
				</div>
				<form id="frm1" name="frm1" action=" " method="post" class="form-horizontal form-label-left" >
				  <input id="hdn1" name="hdn1" type="hidden" value="<?php echo $command; ?>">
				  <div class="modal-body">
					<div class="form-group">
					  <label>Room Name :</label>
					  <input id="roomName" name="roomName" type="text" class="form-control" title="Input name of room" value="<?php echo $roomName; ?>">
					</div>
				  </div>
				  <div class="modal-footer">
				    <div class="form-group">
					  <label>
					    <input id="active" name="active" type="checkbox" class="js-switch" <?php if($command=='UPDATE'){if($active==1) echo 'checked'; else '';} else { echo 'checked';} ?>  /> Deactive | Active
					  </label>
				    </div>
				    <button id="submit" name="submit" type="submit" class="btn btn-primary">Submit</button>
				  </div>
				</form>
		      </div>
			</div>
		  </div>
		  <!-- // Modal -->

        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <?php include ('footer.php'); ?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
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
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="../vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <script src="../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="../vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="../vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

	<!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

	<!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

	<script type="text/javascript">
	  $(document).ready(function() {
        var table = $('#roomsDataTable').DataTable( {
		  responsive: 	  true,
		  searching:	  false,
          scrollX:        true,

          //scrollCollapse: true,
          paging:         true
          //fixedColumns:   {
          //  leftColumns: 2
          //}
        });

		if($('#hdn1').val()=='UPDATE'){
		  $('#roomModal').modal('show');
		}

      });
	</script>
  </body>
</html>
