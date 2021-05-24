<style type="text/css">
  div.none{
    display: none;
  }
</style>
<section id="main-content">
  <section class="wrapper">
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
          <li class="active">Services</li>
        </ul>
        <!--breadcrumbs end -->
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
        <!-- Improved Flashdata End -->
        <div class="col-lg-6" style="padding-left: 0px;padding-right: 0px;">
          <section class="panel">
            <header class="panel-heading">
              <label><?php echo @$page_of ?></label><label style="float: right"><?php echo @$count_of ?></label>
            </header>
            <div class="panel-body">
              <!-- Improved Flashdata Start -->
                <?php
                  $alert_msg = $this->session->flashdata('alert_msg_edit');
                  if($alert_msg != ""): ?>
                  <div class="alert <?php echo $this->session->flashdata('alert_class_edit'); ?> fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                      <i class="fa fa-times"></i>
                    </button>
                    <?php echo $alert_msg; ?>
                  </div>
                <?php endif; ?>
              <!-- Improved Flashdata End -->
              <div class="form-group" style="margin-bottom: 50px;">
                <div class="col-md-12" style="padding-right: 0px;padding-left: 0px;">
                  <div class="input-group m-bot15">
                    <input type="text" class="form-control" name="keyword" placeholder="Search keyword by Service Name" value="<?php echo @$_GET['name'] ?>">
                    <div class="input-group-btn">
                      <button tabindex="-1" class="btn btn-white" type="submit" id="search_keyword">Search</button>
                      <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sort by <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li class="<?php echo (@$_GET['order_by'] == 'name') ? 'active' : ''?>">
                          <a href="<?php echo @$order_by.'&order_by=name'?>">Service Name</a>
                        </li>
                        <li class="<?php echo (@$_GET['order_by'] == 'date_reg' || !isset($_GET['order_by'])) ? 'active' : ''?>">
                          <a href="<?php echo @$order_by.'&order_by=date_reg'?>">Date Created</a>
                        </li>
                        <li class="divider"></li>
                        <li class="<?php echo (@$_GET['order'] == 'asc') ? 'active' : ''?>">
                          <a href="<?php echo (@$_GET['order'] == 'asc') ? '#' : @$order.'&order=asc'?>"> Ascending</a>
                        </li>
                        <li class="<?php echo (@$_GET['order'] == 'desc'  || !isset($_GET['order'])) ? 'active' : ''?>">
                          <a href="<?php echo (@$_GET['order'] == 'desc') ? '#' : @$order.'&order=desc'?>"> Descending</a>
                        </li>
                      </ul>
                      <button tabindex="-1" class="btn btn-white" type="button">
                        <a href="<?php echo @$x_clear_keyword ?>">X </a>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
                  <form>
                    <div class="input-group m-bot15">
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">Filter by Division</button>
                      </div>
                      <select class="form-control" name="cat_division">
                        <option value="">All</option>
                        <?php foreach ($all_divison as $key => $value): ?>
                          <option value="<?php echo $value->id ?>" <?php echo (isset($_GET['cat_division']) && $_GET['cat_division'] == $value->id) ? "selected=''":""; ?>><?php echo $value->name ?></option>
                        <?php endforeach ?>
                      </select>
                      <div class="input-group-btn">
                        <button tabindex="-1" class="btn btn-white" type="button">
                          <a href="<?php echo @$x_clear_cat_division ?>">X</a>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>

              <div class="alert alert-info fade in" style="margin-top: 100px;">
                <p><strong style="margin-right: 10px;">Legend: </strong>
                  <span class="label label-danger" style="background-color: #fff;color: black;">ACTIVE</span>
                  <span class="label label-warning" style="background-color: #8a8a8a;">INACTIVE</span>
                </p>
              </div>
              <div class="table-responsive" style="overflow: hidden; outline: none;" tabindex="1">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="width: 15px;">#</th>
                      <th>Service Name</th>
                      <th style="width: 130px;">Date Created</th>
                      <th style="width: 30px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($service) > 0 ): ?>

                      <?php $i = 1; foreach ($service as $key => $value): ?>
                        <tr style="<?php echo ($value->is_active == 0) ? 'background-color: #8a8a8a;color: white;':''; ?>">
                          <th scope="row" style="<?php echo ($value->is_active == 0) ? 'background-color: #8a8a8a;color: white;':''; ?>"><?php echo @$this->uri->segment(4) + ($key + 1);  ?></th>
                          <td><?php echo $value->name ?></td>
                          <td><?php echo $value->f_created_at ?></td>
                          <td>
                            <button type="button" class="btn btn-info btn-xs">
                              <a id="<?php echo $value->id?>" href="#edit-<?php echo $key ?>" title="Edit Category" class="edit" style="color:white;" data-id="<?php echo $value->id ?>" data-toggle="modal">
                                <i class="fa fa-pencil"></i>
                              </a>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" style="text-align:center">Empty table data</td>
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
        <div class="col-lg-6" style="padding-right: 0px;">
          <section class="panel">
            <header class="panel-heading">
              Add New Service
            </header>
            <div class="panel-body">
              <form role="form" method="post" action="<?php echo base_url('cms/services/add_service/') ?>">
                <div class="form-group">
                  <label >Division</label>
                  <select name="division_id" class="form-control" required="">
                    <option value="" disabled="" selected="">Select Division</option>
                    <?php foreach ($all_divison as $key => $value): ?>
                      <option <?php echo ($value->is_active) ? '':'disabled="disabled" style="background-color:gray;color:white;"'; ?> value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                    <?php endforeach ?>
                  </select>

                </div>
                <div class="form-group">
                  <label >Service Name</label>
                  <input type="text" class="form-control" name="name" required="">
                </div>
                <label>Active</label>
                <div class="row m-bot15">
                  <div class="col-sm-12">
                    <div class="switch switch-square"
                         data-on-label="<i class=' fa fa-check'></i>"
                         data-off-label="<i class='fa fa-times'></i>">
                        <input type="checkbox" checked="" name="is_active"/>
                    </div>
                  </div>
                </div>
                <input type="submit" class="form-control btn-success" value="Add Service" style="color: white;font-size: 13px;color: #ffffff!important;width: 105px;float: right;">
              </form>
            </div>
          </section>
        </div>
      </div>
    </div>
    <!-- page end-->
  </section>
</section>
<!-- Modal -->
<?php foreach ($service as $key => $value): ?>
  <div class="modal fade " id="edit-<?php echo $key ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Edit Service</h4>
        </div>
        <div class="panel-body">
          <form role="form" method="post" action="<?php echo base_url('cms/services/update_service/'.$value->id) ?>" enctype="multipart/form-data">
            
            <div class="form-group">
              <label >Division</label>
              <select name="division_id" class="form-control" required="">
                <option value="" disabled="" selected="">Select Division</option>
                <?php foreach ($all_divison as $division): ?>
                  <option <?php echo ($division->id == $value->division_id) ? 'selected=""':''; ?> value="<?php echo $division->id ?>"><?php echo $division->name ?></option>
                <?php endforeach ?>
              </select>

            </div>
            <div class="form-group">
              <label >Service Name</label>
              <input type="text" class="form-control" name="name" required="" value="<?php echo $value->name ?>">
            </div>
            <label>Active</label>
            <div class="row m-bot15">
              <div class="col-sm-12">
                <div class="switch switch-square"
                     data-on-label="<i class=' fa fa-check'></i>"
                     data-off-label="<i class='fa fa-times'></i>">
                    <input type="checkbox" name="is_active" <?php echo ($value->is_active) ? 'checked=""':'';  ?>/>
                </div>
              </div>
            </div>
            <input type="submit" class="form-control btn-success" value="Update Service" style="color: white;font-size: 13px;color: #ffffff!important;width: 128px;float: right;">
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- modal -->
<?php endforeach ?>
<script type="text/javascript">
  $( document ).ready(function() {
    if (window.location.hash) {
      var s2 = window.location.hash.substr(1);
      $('a#'+s2).trigger('click');
    }
    $('button#search_keyword').on('click', function(e){
      window.location.href='<?php echo $x_clear_keyword ?>&name='+$('input[name=keyword]').val();
    });
    $('select[name=cat_division]').on('change', function(e){
      window.location.href='<?php echo $x_clear_cat_division ?>&cat_division='+$(this).children('option:selected').val();
    });
  });
</script>
<script src="<?php echo base_url('public/admin/') ?>js/jquery.js"></script>
<!--custom switch-->
<script type="text/javascript" src="<?php echo base_url('public/admin/') ?>assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script src="<?php echo base_url('public/admin/') ?>js/bootstrap-switch.js"></script>

