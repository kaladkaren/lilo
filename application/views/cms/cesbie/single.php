<?php 
function calculate_duration($login, $logout)
{
  $return_str = '';
  $seconds = strtotime($logout) - strtotime($login);

  $days = floor($seconds / 86400);
  $hours = floor(($seconds - ($days * 86400)) / 3600);
  $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);

  $days_str = ($days == 1) ? 'day':'days';
  $hours_str = ($hours == 1) ? 'hour':'hours';
  $mins_str = ($minutes == 1) ? 'min':'mins';
  if($days):
    $return_str = $days ." {$days_str}, ";
  endif;
  if($hours):
    $return_str .= $hours ." {$hours_str}, ";
  endif;
  if($minutes == 0):
    $secs_str = ($seconds == 1) ? 'sec':'secs';
    $return_str .= $seconds ." {$secs_str}, ";
  else:
    $return_str .= $minutes ." {$mins_str}, ";
  endif;

  return rtrim($return_str, ', ');
}
?>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-sm-12">
				<!--breadcrumbs start -->
		        <ul class="breadcrumb">
		          <li><a href="<?php echo base_url('cms/cesbie-staffs') ?>">Cesbie Management</a></li>
		          <li class="active"><?php echo $cesbie[0]->fullname ?></li>
		        </ul>
		        <!--breadcrumbs end -->

		        <div class="row">
		            <div class="col-lg-4">
		              <section class="panel">
		                  <header class="panel-heading">
		                      Cesbie Information
		                  </header>
		                  <div class="panel-body">
		                    <?php
		                      $alert_msg = $this->session->flashdata('alert_msg');
		                      if($alert_msg != ""): ?>
		                      <div class="alert <?php echo $this->session->flashdata('alert_class'); ?> fade in">
		                        <button data-dismiss="alert" class="close close-sm" type="button">
		                          <i class="fa fa-times"></i>
		                        </button>
		                        <?php echo $alert_msg; ?>
		                      </div>
		                    <?php endif; ?>
		                    <div class="form-group">
		                      <label>Full Name</label>
		                      <input type="text" class="form-control" value="<?php echo $cesbie[0]->fullname ?>" disabled="">
		                    </div>
		                    <div class="form-group">
		                      <label>Email</label>
		                      <input type="text" class="form-control" value="<?php echo $cesbie[0]->email_address ?>" disabled="">
		                    </div>
		                    <div class="form-group">
		                      <label>Division</label>
		                      <input type="text" class="form-control" value="<?php echo @$cesbie[0]->division_name ?>" disabled="">
		                    </div>
		                  </div>
		              </section>
		            </div>
		            <div class="col-lg-8">
		                <section class="panel">
		                    <header class="panel-heading" style="text-align: center;">Logs</header>
		                    <header class="panel-heading">
				              <label><?php echo @$page_of ?></label><label style="float: right"><?php echo @$count_of ?></label>
				            </header>
		                      <div class="panel-body">
		                        <div class="table-responsive" style="overflow: hidden; outline: none;" tabindex="1">
					                <table class="table table-bordered table-hover">
					                  <thead>
					                    <tr>
					                      <th style="width: 15px;">#</th>
					                      <th>Login<br> Timestamp</th>
					                      <th>Logout<br> Timestamp</th>
					                      <th style="width: 130px;">Duration</th>
					                      <th style="width: 145px;">Health Condition &<br>Temperature</th>
					                      <th>Place of Origin</th>
					                    </tr>
					                  </thead>
					                  <tbody>
					                    <?php if (count($cesbie) > 0 && $cesbie[0]->login != NULL): ?>

					                      <?php $i = 1; foreach ($cesbie as $key => $value): ?>
					                        <tr>
					                          <th scope="row"><?php echo @$this->uri->segment(6) + $i; $i++;?></th>
					                          <td><?php echo $value->login_timestamp ?></td>
					                          <td><?php echo $value->logout_timestamp ?></td>
					                          <td><?php echo ($value->logout != '0000-00-00 00:00:00') ? calculate_duration($value->login, $value->logout) : '-'?></td>
					                          <?php $value->health_condition = ($value->health_condition)?:'-'; ?>
                        					  <td><?php echo $value->health_condition.'<br>'.$value->temperature.'°C' ?></td>
					                          <td><?php echo $value->place_of_origin ?></td>
					                        </tr>
					                      <?php endforeach; ?>
					                    <?php else: ?>
					                      <tr>
					                        <td colspan="7" style="text-align:center">Empty table data</td>
					                      </tr>
					                    <?php endif; ?>
					                  </tbody>
					                </table>
					                <center>
					                  <ul class="pagination">
					                    <?php echo @$pagination; ?>
					                  </ul>
					                </center>
					              </div>
		                      </div>
		                </section>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</section>