<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name='robots' content='noindex,nofollow' />
  <!-- <link rel="shortcut icon" href="img/favicon.png"> -->

  <title>LiLoApp CRUD</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url('public/admin/'); ?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url('public/admin/'); ?>css/bootstrap-reset.css" rel="stylesheet">
  <!--external css-->
  <link href="<?php echo base_url('public/admin/'); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

  <!--right slidebar-->
  <link href="<?php echo base_url('public/admin/'); ?>css/slidebars.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="<?php echo base_url('public/admin/'); ?>css/style.css" rel="stylesheet">
  <link href="<?php echo base_url('public/admin/'); ?>css/style-responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo base_url('public/admin/'); ?>assets/data-tables/DT_bootstrap.css" />

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
  <!--[if lt IE 9]>
  <script src="<?php echo base_url('public/admin/'); ?>js/html5shiv.js"></script>
  <script src="<?php echo base_url('public/admin/'); ?>js/respond.min.js"></script>
  <![endif]-->
  <script src="<?php echo base_url('public/admin/'); ?>js/jquery.js"></script>
  <script type="text/javascript">
    const base_url = '<?php echo base_url(); ?>';
  </script>
  <style type="text/css">
    .has-switch span.switch-left {
        /*border-radius: 30px 0 0 30px;*/
        background-color: #ffffff;
        color: #000000;
        border: 1px solid #afafaf;
        padding-right: 10px;
    }
    .has-switch > div.switch-on label {
        background-color: #a9ccc9;
        border-color: #a9ccc9;
    }

    .switch-square label {
        border-radius: 0 6px 6px 0;
        border-color: #c2c2c2;
        border: 2px solid #c2c2c2;
    }
    .breadcrumb>li {
        display: inline-block;
        font-weight: 600;
        font-size: 17px;
    }
    input[type="date"].form-control, input[type="time"].form-control, input[type="datetime-local"].form-control, input[type="month"].form-control {
        line-height: 15px;
    }
  </style>
</head>

<body>
