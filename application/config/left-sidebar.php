
<section id="container" class="">
  <!--header start-->
  <header class="header white-bg">
    <div class="sidebar-toggle-box">
      <i class="fa fa-bars"></i>
    </div>
    <!--logo start-->
    <a href="index.html" class="logo" >LiLoApp<span>CRUD</span></a>
    <!--logo end-->
    <div class="top-nav ">
      <ul class="nav pull-right top-menu">
        <li class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <!-- <img alt="" src="img/avatar1_small.jpg"> -->
            <span class="username">Welcome back, <?php echo $this->session->userdata('name'); ?></span>
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu extended logout">
            <li><a href="<?php echo base_url('cms/login/logout') ?>"><i class="fa fa-key"></i> Log Out</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </header>
  <!--header end-->
  <!--sidebar start-->
  <aside>
    <div id="sidebar"  class="nav-collapse ">
      <!-- sidebar menu start-->
      <ul class="sidebar-menu" id="nav-accordion">
        <?php if ($_SESSION['is_super_admin'] == 1): ?>
          <li>
            <a href="<?php echo base_url('cms') ?>"
              <?php echo $this->uri->segment(1) === 'cms' && ($this->uri->segment(2) === null || $this->uri->segment(2) === 'index') ? 'class="active" style="color:#ff6c60"': ''; ?>>
              <span>Admin Management</span>
            </a>
          </li>
        <?php endif ?>
        <li>
          <a href="<?php echo base_url('cms/divisions') ?>"
            <?php echo (in_array($this->uri->segment(2), ['divisions', 'division']))  ? 'class="active" style="color:#ff6c60"': ''; ?>>
            <span>Division Management</span>
          </a>
        </li>
        <li>
          <a href="<?php echo base_url('cms/agency') ?>"
            <?php echo (in_array($this->uri->segment(2), ['agency']))  ? 'class="active" style="color:#ff6c60"': ''; ?>>
            <span>Agency Management</span>
          </a>
        </li>
        <li>
          <a href="<?php echo base_url('cms/attached-agency') ?>"
            <?php echo (in_array($this->uri->segment(2), ['attached-agency']))  ? 'class="active" style="color:#ff6c60"': ''; ?>>
            <span>Attached Agency Management</span>
          </a>
        </li>
        <li>
          <a href="<?php echo base_url('cms/services') ?>"
            <?php echo (in_array($this->uri->segment(2), ['services']))  ? 'class="active" style="color:#ff6c60"': ''; ?>>
            <span>Services Management</span>
          </a>
        </li>
        <li class="sub-menu">

          <a href="javascript:;" class="<?php echo (in_array($this->uri->segment(3), ['guest-visitors', 'cesbie-visitors']) || in_array($this->uri->segment(2), ['visitors']))  ? 'active': ''; ?>">
            <span>Visitors</span>
          </a>
          <ul class="sub" >
            <li><a <?php echo ($this->uri->segment(2) === 'visitors' && ($this->uri->segment(3)  === null || $this->uri->segment(3)  === 'index')) ? 'style="color:#ff6c60"': ''; ?> href="<?php echo base_url('cms/visitors') ?>">All Visitors</a></li>
            <li><a <?php echo ($this->uri->segment(2) === 'visitors' && $this->uri->segment(3) === 'cesbie-visitors') ? 'style="color:#ff6c60"': ''; ?> href="<?php echo base_url('cms/visitors/cesbie-visitors') ?>">Cesbie Visitors</a></li>
            <li><a <?php echo $this->uri->segment(2) === 'visitors' && $this->uri->segment(3) === 'guest-visitors' ? 'style="color:#ff6c60"': ''; ?> href="<?php echo base_url('cms/visitors/guest-visitors') ?>">Guest Visitors</a></li>
          </ul>
        </li>
        <li>
          <a href="<?php echo base_url('cms/cesbie-staffs') ?>"
            <?php echo (in_array($this->uri->segment(2), ['cesbie-staffs']))  ? 'class="active" style="color:#ff6c60"': ''; ?>>
            <span>Cesbie Management</span>
          </a>
        </li>
      </ul>
      <!-- sidebar menu end-->
    </div>
  </aside>
  <!--sidebar end-->
