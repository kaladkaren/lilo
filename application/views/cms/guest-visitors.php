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
  if($days):
    $return_str = $days ." {$days_str}, ";
  endif;
  if($hours):
    $return_str .= $hours ." {$hours_str}, ";
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
          <li><a href="<?php echo base_url('cms/visitors') ?>">Visitors</a></li>
          <li class="active">Guest Visitors</li>
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
                        <button tabindex="-1" class="btn btn-white" type="button">Select Division Visited</button>
                      </div>
                      <select class="form-control" name="division">
                        <option value="">All</option>
                        <?php foreach ($divisions as $key => $value): ?>
                          <option value="<?php echo $value->id ?>" <?php echo (isset($_GET['cat']) && $_GET['cat'] == $value->id) ? "selected=''":""; ?>><?php echo $value->name ?></option>
                        <?php endforeach ?>
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
                  <input type="text" class="form-control" name="name" placeholder="Search keyword by Guest Name" value="<?php echo @$_GET['name'] ?>">
                  <div class="input-group-btn">
                    <button tabindex="-1" class="btn btn-white" type="submit" id="search_keyword">Search</button>
                    <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sort by <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li class="<?php echo (@$_GET['order_by'] == 'name') ? 'active' : ''?>">
                        <a href="<?php echo @$order_by.'&order_by=name'?>">Name</a>
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
              <div class="col-md-6" style="padding-left: 0px;">
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
                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                  <form>
                    <div class="input-group m-bot15">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">Filter by Date Range</button>
                      </div>
                      <input type="date" name="from" class="form-control" value="<?php echo @$_GET['from'] ?>">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">
                          TO
                        </button>
                      </div>
                      <input type="date" name="to" class="form-control" value="<?php echo @$_GET['to'] ?>">
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
                    <th>Health Condition &<br>Temperature</th>
                    <th>Place of <br>Origin</th>
                    <th>Person &<br>Division Visited</th>
                    <th>Pin Code</th>
                    <th>Login<br>Timestamp</th>
                    <th>Logout<br>Timestamp</th>
                    <th style="width: 50px;"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($cesbie_visitors): ?>
                    <?php foreach ($cesbie_visitors as $key => $value): ?>
                      
                      <tr>
                        <td>
                          <?php echo @$this->uri->segment(5) + ($key + 1);  ?>
                        </td>
                        <td><?php echo $value->fullname   ?></td>
                        <td><?php echo $value->health_condition.'<br>'.$value->temperature ?></td>
                        <td><?php echo $value->place_of_origin ?></td>
                        <td><?php echo $value->person_fullname_visited.'<br>/'.$value->division_name_visited ?></td>
                        <td><?php echo $value->pin_code ?></td>
                        <td><?php echo $value->f_created_at ?></td>
                        <td>
                          <?php echo $value->logout_timestamp ?>
                        </td>
                        <td>
                          <button type="button" class="btn btn-info btn-xs"><a style="color:white;" title="View Details" href="#edit-<?php echo $key ?>" data-toggle="modal"><i class="fa fa-eye"></i></a></button>
                        </td>
                      </tr>
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
  <div class="modal fade " id="edit-<?php echo $key ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
          <div class="col-md-12">
            <div class="form-group">
              <label >Visitor Name</label>
              <input type="text" class="form-control" value="<?php echo $value->fullname ?>" disabled="">
            </div>
            <div class="form-group">
              <label >Purpose</label>
              <input type="text" class="form-control" value="<?php echo $value->purpose_name ?>" disabled="">
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
                <input type="text" class="form-control" value="<?php echo $value->agency_name ?>" disabled="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Attached Agency</label>
                <input type="text" class="form-control" value="<?php echo $value->att_agency_name ?>" disabled="">
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
                <input type="text" class="form-control" value="<?php echo $value->temperature ?>" disabled="">
              </div>
            </div>
          </div>
          <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
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
  <!-- modal -->
<?php endforeach ?>
<script type="text/javascript">
    $('button#search_keyword').on('click', function(e){
      window.location.href='<?php echo $x_clear_keyword ?>&name='+$('input[name=name]').val();
    });

    $('button#search_daterange').on('click', function(e){
      window.location.href='<?php echo $x_clear_date_range ?>&from='+$('input[name=from]').val()+'&to='+$('input[name=to]').val();
    });

    $('select[name=division]').on('change', function(e){
      window.location.href='<?php echo $x_clear_cat ?>&cat='+$(this).children('option:selected').val();
    });

    $('select[name=origin]').on('change', function(e){
      window.location.href='<?php echo $x_clear_origin ?>&origin='+$(this).children('option:selected').val();
    });
</script>