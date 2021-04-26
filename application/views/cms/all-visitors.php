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
    <!-- page start-->
    <div class="row">
      <div class="col-sm-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
          <li class="active">All Visitors</li>
        </ul>
        <!--breadcrumbs end -->
        <section class="panel">
          <header class="panel-heading">
            <label><?php echo @$page_of ?></label>
            <label style="float: right"><?php echo @$count_of ?></label>
          </header>
          <div class="panel-body">
            <!-- Improved Flashdata Start -->
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
              <div class="form-group">
                <div class="col-md-6" style="padding-left: 0px;">
                  <form>
                    <div class="input-group m-bot15">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">Filter by Visitor Type</button>
                      </div>
                      <select class="form-control" name="visitor_type">
                        <option <?php echo ( !isset($_GET['v_type']) || strtolower(@$_GET['v_type']) == 'all') ? 'selected=""':''; ?>value="">All</option>
                        <option <?php echo (strtolower(@$_GET['v_type']) == 'cesbie') ? 'selected=""':''; ?>>Cesbie</option>
                        <option <?php echo (strtolower(@$_GET['v_type']) == 'guest') ? 'selected=""':''; ?>>Guest</option>
                      </select>
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">
                          <a href="<?php echo @$x_clear_cat ?>">X</a>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-md-6" style="padding-right: 0px;padding-left: 0px;">
                  <div class="input-group m-bot15">
                    <input type="text" class="form-control" name="name" placeholder="Search keyword by Visitor Name" value="<?php echo @$_GET['name'] ?>">
                    <div class="input-group-btn">
                      <button tabindex="-1" class="btn btn-white" type="submit" id="search_keyword">Search</button>
                      <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sort by <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li class="<?php echo (@$_GET['order_by'] == 'name') ? 'active' : ''?>">
                          <a href="<?php echo @$order_by.'&order_by=name'?>">Visitor Name</a>
                        </li>
                        <li class="<?php echo (@$_GET['order_by'] == 'date_reg' || !isset($_GET['order_by'])) ? 'active' : ''?>">
                          <a href="<?php echo @$order_by.'&order_by=date_reg'?>">Login Timestamp</a>
                        </li>
                        <li class="<?php echo (@$_GET['order_by'] == 'date_logout') ? 'active' : ''?>">
                          <a href="<?php echo @$order_by.'&order_by=date_logout'?>">Logout Timestamp</a>
                        </li>
                        <li class="divider"></li>
                        <li class="<?php echo (@$_GET['order'] == 'asc') ? 'active' : ''?>">
                          <a href="<?php echo (@$_GET['order'] == 'asc') ? '#' : @$order.'&order=asc'?>"> Ascending</a>
                        </li>
                        <li class="<?php echo (@$_GET['order'] == 'desc' || !isset($_GET['order'])) ? 'active' : ''?>">
                          <a href="<?php echo (@$_GET['order'] == 'desc') ? '#' : @$order.'&order=desc'?>"> Descending</a>
                        </li>
                      </ul>
                      <button tabindex="-1" class="btn btn-white" type="button">
                        <a href="<?php echo @$x_clear_keyword ?>">X </a>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-md-4" style="padding-left: 0px;">
                  <form>
                    <div class="input-group m-bot15">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">Filter by Place of Origin</button>
                      </div>
                      <select class="form-control" name="origin">
                        <option value="">All</option>
                        <?php foreach ($place_of_origin as $key => $value): ?>
                          <option value="<?php echo $value->place_of_origin ?>" <?php echo (isset($_GET['origin']) && $_GET['origin'] == $value->place_of_origin) ? "selected=''":""; ?>><?php echo $value->place_of_origin ?></option>
                        <?php endforeach ?>
                      </select>
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">
                          <a href="<?php echo @$x_clear_origin ?>">X</a>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-md-8" style="padding-left: 0px;padding-right: 0px;">
                  <form>
                    <div class="input-group m-bot15">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">Filter by Date Range</button>
                      </div>
                      <input type="date" name="from" class="form-control" value="<?php echo @$_GET['from'] ?>" max="<?php echo date('Y-m-d') ?>">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">
                          TO
                        </button>
                      </div>
                      <input type="date" name="to" class="form-control" value="<?php echo @$_GET['to'] ?>" max="<?php echo date('Y-m-d') ?>">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button" id="search_daterange"><i class="fa fa-search"></i></button>
                      </div>
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">
                          <a href="<?php echo @$x_clear_date_range ?>">X</a>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
            </div>


            <!-- Improved Flashdata End -->
            <div class="adv-table">
              <table class="display table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Visitor Name</th>
                    <th style="width: 145px;">Health Condition &<br>Temperature</th>
                    <th>Place of Origin</th>
                    <th>Visitor Type</th>
                    <th>Pin Code</th>
                    <th>Login<br>Timestamp</th>
                    <th>Logout<br>Timestamp</th>
                    <th style="width: 80px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($cesbie_visitors): ?>
                    <?php foreach ($cesbie_visitors as $key => $value): ?>

                      <tr>
                        <td>
                          <?php echo @$this->uri->segment(4) + ($key + 1);  ?>
                        </td>
                        <td><?php echo $value->staff_fullname ?></td>
                        <?php $value->health_condition = (@$value->health_condition)?:'-'; ?>
                        <td><?php echo $value->health_condition.'<br>'.$value->temperature.'°C' ?></td>
                        <td><?php echo $value->place_of_origin ?></td>
                        <td><?php echo $value->visitor_type ?></td>
                        <td><?php echo $value->pin_code ?></td>
                        <td><?php echo $value->f_created_at ?></td>
                        <td><?php echo (@$value->logout_timestamp)?:'-' ?></td>
                        <td>
                          <button type="button" class="btn btn-info btn-xs"><a style="color:white;" title="View Details" href="#edit-<?php echo $key ?>" data-toggle="modal"><i class="fa fa-eye"></i></a></button>
                          <button type="button" class="btn btn-<?php echo ($value->visitor_type != 'GUEST' || $value->logout_created_at == '') ? 'disable':'warning'?> btn-xs" <?php echo ($value->visitor_type != 'GUEST' || $value->logout_created_at == '') ? 'style="pointer-events: none;"':''?>><a style="color:white;" title="View Feedback" href="#feedback-<?php echo $key ?>" data-toggle="modal"><i class="fa fa-comment-o"></i></a></button>
                        </td>
                      </tr>
                      <?php #var_dump($value); die(); ?>
                    <?php endforeach ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="9" style="text-align: center;">
                        No result/s found.
                      </td>
                    </tr>
                  <?php endif ?>
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
    <!-- page end-->
  </section>
</section>
<?php foreach ($cesbie_visitors as $key => $value): ?>
  <?php if ($value->visitor_type == 'GUEST'): ?>
      <div class="modal fade " id="edit-<?php echo $key ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Visit Details</h4>
        </div>
        <div class="panel-body">
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-4">
              <div class="form-group">
                <label>Login Timestamp</label>
                <input type="text" class="form-control" value="<?php echo str_replace("<br>", "", $value->f_created_at) ?>" disabled="">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Logout Timestamp</label>
                <input type="text" class="form-control" value="<?php echo str_replace("<br>", "", $value->logout_timestamp) ?>" disabled="">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Duration</label>
                <?php if ($value->logout_created_at): ?>
                <input type="text" class="form-control" value="<?php echo calculate_duration($value->created_at, $value->logout_created_at) ?>" disabled="">
                <?php else: ?>
                <input type="text" class="form-control" value="-" disabled="">
                <?php endif ?>
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <hr>
          </div>
          <div class="col-md-12">
            <div class="col-md-6" style="padding-left: 0px; border: 1px solid #ccc!important; margin-bottom: 15px;">
              <center>
              <a href="<?php echo $value->photo ?>" target="_blank">
                <img src="<?php echo $value->photo ?>" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/no-image.png') ?>';" style="width: auto;height: 150px;">
              </a>
              </center>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label >Visitor Name</label>
                <input type="text" class="form-control" value="<?php echo $value->fullname ?>" disabled="">
              </div>
              <div class="form-group">
                <label >Purpose</label>
                <input type="text" class="form-control" value="<?php echo $value->purpose_name ?>" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-6">
              <div class="form-group">
                <label>Person Visited</label>
                <input type="text" class="form-control" value="<?php echo $value->person_fullname_visited ?>" disabled="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Division</label>
                <input type="text" class="form-control" value="<?php echo $value->division_name_visited ?>" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <hr>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-6">
              <div class="form-group">
                <label>Visitor's Details</label>
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email Address</label>
                <input type="text" class="form-control" value="<?php echo $value->email_address ?>" disabled="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Number</label>
                <input type="text" class="form-control" value="<?php echo $value->mobile_number ?>" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-6">
              <div class="form-group">
                <label>Agency</label>
                <input type="text" class="form-control" value="<?php echo $value->agency_name?: 'Others' ?>" disabled="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Attached Agency</label>
                <input type="text" class="form-control" value="<?php echo $value->att_agency_name?: 'Others' ?>" disabled="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Attached Agency (Others)</label>
                <input type="text" class="form-control" value="<?php echo @$value->attached_agency_others ?>" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <hr>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-6">
              <div class="form-group">
                <label>Health Condition</label>
                <input type="text" class="form-control" value="<?php echo $value->health_condition ?>" disabled="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Temperature</label>
                <input type="text" class="form-control" value="<?php echo $value->temperature ?>°C" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-12">
              <div class="form-group">
                <label>Have you had any contact with any PUI/PUM in the last 14 days?</label>
                <input type="text" class="form-control" value="<?php echo $value->is_recent_contact? 'Yes' :'No' ?>" disabled="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Details,</label>
                <input type="text" class="form-control" value="<?php echo $value->recent_contact_details ?>" disabled="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Have you travelled locally / internationally in the last 14 days??</label>
                <input type="text" class="form-control" value="<?php echo $value->is_travelled_locally? 'Yes' :'No' ?>" disabled="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Details,</label>
                <input type="text" class="form-control" value="<?php echo $value->travelled_locally_details ?>" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="col-md-12">
              <div class="form-group">
                <label>Complete Address</label>
                <input type="text" class="form-control" value="<?php echo $value->home_address ?>" disabled="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Place of Origin</label>
                <input type="text" class="form-control" value="<?php echo $value->place_of_origin ?>" disabled="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade " id="feedback-<?php echo $key ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xs">
        <div class="modal-content">
          <div class="modal-header" style="background: #f1c500;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Feedback Details</h4>
          </div>
          <div class="panel-body">
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-4">
                <img src="<?php echo $value->overall_experience_png ?>" style="width: auto;height: 150px;">
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Overall Experience</label>
                  <input type="text" class="form-control" value="<?php echo $value->overall_experience ?>" disabled="">
                </div>
                <div class="form-group">
                  <label>Feedback</label>
                  <textarea class="form-control" disabled="" rows="6"><?php echo $value->feedback ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- modal -->
  <?php else: ?>
    <div class="modal fade " id="edit-<?php echo $key ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Visit Details</h4>
          </div>
          <div class="panel-body">
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Login Timestamp</label>
                  <input type="text" class="form-control" value="<?php echo str_replace("<br>", "", $value->f_created_at) ?>" disabled="">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Logout Timestamp</label>
                  <input type="text" class="form-control" value="<?php echo str_replace("<br>", "", $value->logout_timestamp) ?>" disabled="">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Duration</label>
                  <?php if ($value->logout_created_at): ?>
                  <input type="text" class="form-control" value="<?php echo calculate_duration($value->created_at, $value->logout_created_at) ?>" disabled="">
                  <?php else: ?>
                  <input type="text" class="form-control" value="-" disabled="">
                  <?php endif ?>
                </div>
              </div>
            </div>
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <hr>
            </div>
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Place of Origin</label>
                  <input type="text" class="form-control" value="<?php echo $value->place_of_origin ?>" disabled="">
                </div>
              </div>
            </div>
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <hr>
            </div>
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Location Prior</label>
                  <input type="text" class="form-control" value="<?php echo $value->location_prior ?>" disabled="">
                </div>
              </div>
            </div>
            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Please specify</label>
                  <input type="text" class="form-control" value="<?php echo $value->location_prior_others ?>" disabled="">
                </div>
              </div>
            </div>

            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Have you had any contact with any PUI/PUM in the last 14 days?</label>
                  <input type="text" class="form-control" value="<?php echo (int)$value->has_contact ? 'Yes':'No' ?>" disabled="">
                </div>
              </div>
            </div>

            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Details</label>
                  <input type="text" class="form-control" value="<?php echo $value->has_contact_others ?:'-' ?>" disabled="">
                </div>
              </div>
            </div>

            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Have you travelled locally/internationally in the last 14 days?</label>
                  <input type="text" class="form-control" value="<?php echo (int)$value->has_travelled ? 'Yes':'No' ?>" disabled="">
                </div>
              </div>
            </div>

            <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Details</label>
                  <input type="text" class="form-control" value="<?php echo $value->has_travelled_others ?:'-' ?>" disabled="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif ?>
  <!-- modal -->
<?php endforeach ?>
<script type="text/javascript">
    $('button#search_keyword').on('click', function(e){
      window.location.href='<?php echo $x_clear_keyword ?>&name='+$('input[name=name]').val();
    });

    $('button#search_daterange').on('click', function(e){
      if($('input[name=from]').val() && $('input[name=to]').val()){
        if (new Date($('input[name=from]').val()) > new Date($('input[name=to]').val())) {
          alert('Please input valid date range');
        }else{
          window.location.href='<?php echo $x_clear_date_range ?>&from='+$('input[name=from]').val()+'&to='+$('input[name=to]').val();
        }
      }else{
        alert('Please input valid date range');
      }
    });

    $('select[name=visitor_type]').on('change', function(e){
      window.location.href='<?php echo $x_clear_cat ?>&v_type='+$(this).children('option:selected').text();
    });

    $('select[name=origin]').on('change', function(e){
      window.location.href='<?php echo $x_clear_origin ?>&origin='+$(this).children('option:selected').val();
    });
</script>
