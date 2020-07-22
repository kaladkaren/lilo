<section id="main-content">
  <section class="wrapper">
    <!-- page start-->
    <div class="row">
      <div class="col-sm-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
          <li>Visitors</li>
          <li class="active">Cesbie Visitors</li>
        </ul>
        <!--breadcrumbs end -->
        <section class="panel">
          <header class="panel-heading">
            <?php echo @$page_of ?><label style="float: right"><?php echo @$count_of ?></label>
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
              <div class="col-md-12" style="padding-right: 0px;padding-left: 0px;">
                <div class="input-group m-bot15">
                  <input type="text" class="form-control" name="name" placeholder="Search keyword by Staff Name" value="<?php echo @$_GET['name'] ?>">
                  <div class="input-group-btn">
                    <button tabindex="-1" class="btn btn-white" type="submit" id="search_keyword">Search</button>
                    <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sort by <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li class="<?php echo (@$_GET['order_by'] == 'name') ? 'active' : ''?>">
                        <a href="<?php echo @$order_by.'&order_by=name'?>">Name</a>
                      </li>
                      <li class="<?php echo (@$_GET['order_by'] == 'date_reg' || !isset($_GET['order_by'])) ? 'active' : ''?>">
                        <a href="<?php echo @$order_by.'&order_by=date_reg'?>">Date Created</a>
                      </li>
                      <li class="divider"></li>
                      <li class="<?php echo (@$_GET['order'] == 'asc' || !isset($_GET['order'])) ? 'active' : ''?>">
                        <a href="<?php echo (@$_GET['order'] == 'asc') ? '#' : @$order.'&order=asc'?>"> Ascending</a>
                      </li>
                      <li class="<?php echo (@$_GET['order'] == 'desc') ? 'active' : ''?>">
                        <a href="<?php echo (@$_GET['order'] == 'desc') ? '#' : @$order.'&order=desc'?>"> Descending</a>
                      </li>
                    </ul>
                    <button tabindex="-1" class="btn btn-white" type="button">
                      <a href="<?php echo @$x_clear_keyword ?>">X </a>
                    </button>
                  </div>
                </div>
              </div>
            </div>


            <!-- Improved Flashdata End -->
            <div class="adv-table">
              <table class="display table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Staff Name</th>
                    <th>Temperature</th>
                    <th>Place of <br>Origin</th>
                    <th>Pin Code</th>
                    <th>Login<br>Timestamp</th>
                    <th>Logout<br>Timestamp</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($cesbie_visitors): ?>
                    <?php foreach ($cesbie_visitors as $key => $value): ?>
                      
                      <tr>
                        <td><a href="<?php echo base_url('cms/staff/single/').$value->staff_id ?>"><?php echo $value->staff_fullname ?></a></td>
                        <td><?php echo $value->temperature ?></td>
                        <td><?php echo $value->place_of_origin ?></td>
                        <td><?php echo $value->pin_code ?></td>
                        <td><?php echo $value->f_created_at ?></td>
                        <td>
                          -
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" style="text-align: center;">
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
<script type="text/javascript">
    $('button#search_keyword').on('click', function(e){
      window.location.href='<?php echo $x_clear_keyword ?>&name='+$('input[name=name]').val();
    });

    $('select').on('change', function(e){
      window.location.href='<?php echo $x_clear_cat ?>&cat='+$(this).children('option:selected').val();
    });
</script>