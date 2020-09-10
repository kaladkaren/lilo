<style type="text/css">
  a{
    color:white;
  }
</style>
<section id="main-content">
  <section class="wrapper">
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
          <li class="active">Administrators</li>
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
        <section class="panel">
          <header class="panel-heading">
            <label><?php echo @$page_of ?></label>
            <label style="float: right"><?php echo @$count_of ?></label>
          </header>
          <div class="panel-body">
            <p>
              <button type="button" class="add-btn btn btn-success btn-sm"><a href="#add" data-toggle="modal">Add new Administrator</a></button>
            </p>
            <div class="table-responsive" style="overflow: hidden; outline: none;" tabindex="1">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($res) > 0 ): ?>

                    <?php $i = ($this->uri->segment(3) !== NULL) ? $this->uri->segment(3) + 1 : 1; foreach ($res as $key => $value): ?>
                      <tr>
                        <th scope="row"><?php echo $i++ ?></th>
                        <td><?php echo $value->name ?></td>
                        <td><?php echo $value->email ?></td>
                        <td>
                          <button type="button"
                          data-payload='<?php echo json_encode(['id' => $value->id, 'name' => $value->name, 'email' => $value->email])?>'
                          class="edit-row btn btn-info btn-xs"><a href="#edit" title="Edit Administrator" data-toggle="modal" class="edit" data-payload='<?php echo json_encode(['id' => $value->id, 'name' => $value->name, 'email' => $value->email, 'super_admin' => $value->super_admin])?>'><i class="fa fa-pencil"></i></a></button>
                          <button type="button" data-id='<?php echo $value->id; ?>' class="btn btn-delete btn-danger btn-xs"><a href="#delete" data-toggle="modal" class="delete" data-payload='<?php echo json_encode(['id' => $value->id, 'name' => $value->name, 'email' => $value->email])?>' title="Delete Administrator"><i class="fa fa-times"></i></a></button>
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
      </div>
      <!-- page end-->
    </section>
  </section>

  <!-- Modal -->
  <div class="modal fade " id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Add Administrator</h4>
        </div>
        <div class="modal-body">

          <form role="form" method="post" action="<?php echo base_url('cms/dashboard') ?>" id="add-user">
            <div class="form-group">
              <label >Name</label>
              <input type="text" class="form-control" name="name" required="">
            </div>
            <div class="form-group">
              <label >Email address</label>
              <input type="email" class="form-control" name="email" required="">
            </div>

            <div class="form-group">
              <label>Access Level</label>
              <select class="form-control" name="super_admin">
                <option value="1">Super Admin</option>
                <option value="0" selected="">Admin</option>
              </select>
            </div>
            
            <div class="form-group">
              <label >Password</label>
              <input type="password" class="form-control" name="password-add" required="">
            </div>
            <div class="form-group">
              <label >Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="c_password-add" required="">
            </div>

          </div>
          <div class="modal-footer">
            <input class="btn btn-info" type="submit" value="Add" id="submit-add">
            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- modal -->

  <!-- Modal -->
  <div class="modal fade " id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Are you sure you want to delete this Administrator?</h4>
        </div>
        <div class="modal-body">

          <form role="form" method="post" action="<?php echo base_url('cms/dashboard/delete') ?>">
            <div class="form-group">
              <label >Name</label>
              <input type="text" class="form-control" name="name-delete" disabled="">
            </div>
            <div class="form-group">
              <label >Email address</label>
              <input type="email" class="form-control" name="email-delete" disabled="">
              <input type="hidden" class="form-control" name="id-delete">
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn btn-danger" type="submit" value="Yes">
            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- modal -->

  <!-- Modal -->
  <div class="modal fade " id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Edit Administrator</h4>
        </div>
        <div class="modal-body">

          <form role="form" method="post" action="<?php echo base_url('cms/dashboard/update') ?>" id="edit-user">
            <div class="form-group">
              <label >Name</label>
              <input type="text" class="form-control" name="name-edit">
            </div>
            <div class="form-group">
              <label >Email address</label>
              <input type="email" class="form-control" name="email-edit">
              <input type="hidden" class="form-control" name="id-edit">
            </div>

            <div class="form-group">
              <label>Access Level</label>
              <select class="form-control" name="super_admin-edit">
                <option value="1">Super Admin</option>
                <option value="0">Admin</option>
              </select>
            </div>

            <div class="form-group">
              <label >Password</label>
              <input type="password" class="form-control" name="password-edit">
              <label style="float: right; font-size: 10px; color:red;"><b>Leave blank to leave unchanged</b></label>
            </div>
            <div class="form-group">
              <label >Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="c_password-edit">
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn btn-success" type="submit" value="Save Changes" id="submit-edit">
            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- modal -->

  <script type="text/javascript">
    $('a.delete').on('click', function(){
      var payload = $(this).data('payload');
      $('input[name=name-delete]').val(payload.name);
      $('input[name=email-delete]').val(payload.email);
      $('input[name=id-delete]').val(payload.id);
    });
    $('a.edit').on('click', function(){
      var payload = $(this).data('payload');
      $('input[name=name-edit]').val(payload.name);
      $('input[name=email-edit]').val(payload.email);
      $('input[name=id-edit]').val(payload.id);
      $('select[name=super_admin-edit]').val(payload.super_admin);
      console.log(payload.super_admin);
    });

    $('input[name=password-add]').on('change', function(){
      $('input[name=c_password-add]').removeAttr('style');
      $(this).removeAttr('style');
      $( "span#response-pass" ).remove();
      $('input#submit-add').removeClass('cannot');
      $('form#add-user').unbind('submit');

      if ($(this).val()) {
        if ($(this).val() != $('input[name=c_password-add]').val()) {
          $(this).attr('style', 'border: 1px solid red;');
          $('input[name=c_password-add]').attr('style', 'border: 1px solid red;');
          $( "<span id='response-pass' style='color:red; float:left;'>Password should match.</span>" ).insertAfter( "input[name=c_password-add]" );
          $('input#submit-add').addClass('cannot');
          $('form#add-user').bind('submit',function(e){e.preventDefault();});
        }else{
          $('input[name=c_password-add]').removeAttr('style');
          $(this).removeAttr('style');
          $( "span#response-pass" ).remove();
          $('form#add-user').unbind('submit');
        }
      }
    });

    $('input[name=c_password-add]').on('change', function(){
      $('input[name=c_password-add]').removeAttr('style');
      $('input[name=password-add]').removeAttr('style');
      $( "span#response-pass" ).remove();
      $('input#submit-add').removeClass('cannot');
      $('form#add-user').unbind('submit');

      if ($('input[name=password-add]').val()) {
        if ($('input[name=password-add]').val() != $('input[name=c_password-add]').val()) {
          $('input[name=password-add]').attr('style', 'border: 1px solid red;');
          $('input[name=c_password-add]').attr('style', 'border: 1px solid red;');
          $( "<span id='response-pass' style='color:red; float:left;'>Password should match.</span>" ).insertAfter( "input[name=c_password-add]" );
          $('input#submit-add').addClass('cannot');
          $('form#add-user').bind('submit',function(e){e.preventDefault();});
        }else{
          $('input[name=c_password-add]').removeAttr('style');
          $('input[name=password-add]').removeAttr('style');
          $( "span#response-pass" ).remove();
          $('form#add-user').unbind('submit');
        }
      }
    });

    $('input[name=password-edit]').on('change', function(){
      $('input[name=c_password-edit]').removeAttr('style');
      $(this).removeAttr('style');
      $( "span#response-pass" ).remove();
      $('input#submit-edit').removeClass('cannot');
      $('form#edit-user').unbind('submit');

      if ($(this).val()) {
        if ($(this).val() != $('input[name=c_password-edit]').val()) {
          $(this).attr('style', 'border: 1px solid red;');
          $('input[name=c_password-edit]').attr('style', 'border: 1px solid red;');
          $( "<span id='response-pass' style='color:red; float:left;'>Password should match.</span>" ).insertAfter( "input[name=c_password-edit]" );
          $('input#submit-edit').addClass('cannot');
          $('form#edit-user').bind('submit',function(e){e.preventDefault();});
        }else{
          $('input[name=c_password-edit]').removeAttr('style');
          $(this).removeAttr('style');
          $( "span#response-pass" ).remove();
          $('form#edit-user').unbind('submit');
        }
      }
    });

    $('input[name=c_password-edit]').on('change', function(){
      $('input[name=c_password-edit]').removeAttr('style');
      $('input[name=password-edit]').removeAttr('style');
      $( "span#response-pass" ).remove();
      $('input#submit-edit').removeClass('cannot');
      $('form#edit-user').unbind('submit');

      if ($('input[name=password-edit]').val()) {
        if ($('input[name=password-edit]').val() != $('input[name=c_password-edit]').val()) {
          $('input[name=password-edit]').attr('style', 'border: 1px solid red;');
          $('input[name=c_password-edit]').attr('style', 'border: 1px solid red;');
          $( "<span id='response-pass' style='color:red; float:left;'>Password should match.</span>" ).insertAfter( "input[name=c_password-edit]" );
          $('input#submit-edit').addClass('cannot');
          $('form#edit-user').bind('submit',function(e){e.preventDefault();});
        }else{
          $('input[name=c_password-edit]').removeAttr('style');
          $('input[name=password-edit]').removeAttr('style');
          $( "span#response-pass" ).remove();
          $('form#edit-user').unbind('submit');
        }
      }
    });

  </script>

  <script src="<?php echo base_url('public/admin/js/custom/') ?>admin_management.js"></script>
  <script src="<?php echo base_url('public/admin/js/custom/') ?>generic.js"></script>
