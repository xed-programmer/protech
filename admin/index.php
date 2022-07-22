<?php 
    session_start();
    include "../app/classes/Page.class.php";
    
    if(!isset($_SESSION['user_token'])){
      Page::route('/login.php');
    }

    include_once "../app/classes/Database.class.php";

    $light = 0;
    $gas = 0;
    $temperature = 0;
    $con = Database::connection();
    $stmt = $con->query("SELECT * FROM prtch_sensordata ORDER BY created_at DESC LIMIT 1");
    $stmt->execute();
    $data = $stmt->fetchAll();              
    if(count($data)>0){
      $light = $data[0]['light'];
      $smoke1 = $data[0]['smoke1'];
      $smoke2 = $data[0]['smoke2'];
      $temperature = $data[0]['temperature'];
    }

    include_once "../app/resources/views/start.layout.php";
    include_once "../app/resources/views/components/datatable-link.component.php";
    include_once "../app/resources/views/header.layout.php";
?>
<div class="content-wrapper">    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>                    
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-solid fa-lightbulb"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Light</span>
                        <span class="info-box-number"><?php echo $light?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-solid fa-smog"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Smoke 1</span>
                        <span class="info-box-number"><?php echo $smoke1?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-solid fa-smog"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Smoke 2</span>
                        <span class="info-box-number"><?php echo $smoke2?></span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-thermometer-quarter"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Temperature</span>
                        <span class="info-box-number"><?php echo $temperature;?> &#8451;</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
          <!-- /.col -->
            </div>
            <div class="row">
              <div class="col-12">
              <div class="card">
              <div class="card-header">
                <h3 class="card-title">Logs</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Light</th>
                    <th>Smoke 1</th>
                    <th>Smoke 2</th>
                    <th>Temperature</th>
                    <th>Date</th>
                </tr>
                  </thead>
                  <tfoot>
                  <tr>
                  <th>Name</th>
                    <th>Location</th>
                    <th>Light</th>
                    <th>Smoke 1</th>
                    <th>Smoke 2</th>
                    <th>Temperature</th>
                    <th>Date</th>
                  </tr>
                  </tfoot>
                </table></div>
              </div>
              <!-- /.card-body -->
            </div>
              </div>
            </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "../app/resources/views/footer.layout.php";?>
<?php include_once "../app/resources/views/components/required-script.component.php";?>
<?php include_once "../app/resources/views/components/datatable-script.component.php";?>
<script>
  $(function () {    

    $("#example1").DataTable({
      sorting:false,
      searching:false,      
      responsive: true, "lengthChange": false, "autoWidth": false,
      buttons: ["copy", "csv", "excel", "pdf", "print"],
      processing:true,      
      ajax:{
        method:'POST',
        url:"../app/includes/getData.inc.php",
        data:{
          api_key: "tPmAT5Ab3j7F9"
        },
      },
      rowCallback: function(row, data, index){
        //light sensor
        if(data[2]<100){
          $(row).find('td:eq(2)').css('color','red')
        }else if(data[2]<200){
          $(row).find('td:eq(2)').css('color','orange')
        }else if(data[2]<500){
          $(row).find('td:eq(2)').css('color','black')
        }

        // smoke1 sensor
        if(data[3] > 300){
          $(row).find('td:eq(3)').css('color', 'red')
        }
        // smoke2 sensor
        if(data[4] > 300){
          $(row).find('td:eq(4)').css('color', 'red')
        }

        //temperature sensor
        if(data[5] < 20){
          $(row).find('td:eq(5)').css('color', 'blue')
        }else if(data[5] < 30){
          $(row).find('td:eq(5)').css('color', 'green')
        }else if(data[5] < 50){
          $(row).find('td:eq(5)').css('color', 'orange')
        }else{
          $(row).find('td:eq(5)').css('color', 'red')
        }
      }
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)'); 
    
    setInterval(() => {
      $('#example1').DataTable().ajax.reload();
    }, 5000);
  });
</script>
<?php include_once "../app/resources/views/end.layout.php";?>
