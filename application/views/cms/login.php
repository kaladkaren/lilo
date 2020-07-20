<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Mosaddek">
  <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <!-- <link rel="shortcut icon" href="img/favicon.png"> -->

  <title>CMS Login</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url('public/admin/') ?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url('public/admin/') ?>css/bootstrap-reset.css" rel="stylesheet">
  <!--external css-->
  <link href="<?php echo base_url('public/admin/') ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="<?php echo base_url('public/admin/') ?>css/style.css" rel="stylesheet">
  <link href="<?php echo base_url('public/admin/') ?>css/style-responsive.css" rel="stylesheet" />

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
  <!--[if lt IE 9]>
  <script src="<?php echo base_url('public/admin/') ?>js/html5shiv.js"></script>
  <script src="<?php echo base_url('public/admin/') ?>js/respond.min.js"></script>
  <![endif]-->
  <style media="screen">
  html {
    height: 100%;
  }
  body {
    margin: 0;
    padding: 0;
    height: 100%;
    background-color: #434343;
    background-image:linear-gradient(#434343, #282828);
  }
  #content{
    background-color: transparent;
    background-image:       linear-gradient(0deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent);
    height:100%;
    background-size:50px 50px;
  }

  .follow-me {
    position:absolute;
    bottom:10px;
    right:10px;
    text-decoration: none;
    color: #FFFFFF;
  }
  </style>
  <script type="text/javascript">
  const base_url = '<?php echo base_url(); ?>';
  </script>
</head>

<body class="login-body">
  <div class="container">
    <form class="form-signin" method="post" action="<?php echo base_url('cms/login/attempt') ?>">
      <h2 class="form-signin-heading" style="background: dimgray;"> CMS LOGIN
        <!-- <img src="" alt=""
        style="max-height:80px;"> -->
      </h2>
      <div class="login-wrap">
        <input type="text" name="email" class="form-control" placeholder="Email" autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <?php if ($login_msg = $this->session->login_msg): ?>
          <p style="color: <?php echo $login_msg['color'] ?>"><?php echo $login_msg['message'] ?></p>
        <?php endif; ?>
        <button style="background: dimgray; box-shadow: 0px 4px #908f8f"
        class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
      </div>
    </form>
  </div>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="<?php echo base_url('public/admin/') ?>js/jquery.js"></script>
  <script src="<?php echo base_url('public/admin/') ?>js/bootstrap.min.js"></script>
  <!-- <script src="<?php echo base_url('public/admin/js/custom/') ?>login.js"></script> -->

</body>
</html>
