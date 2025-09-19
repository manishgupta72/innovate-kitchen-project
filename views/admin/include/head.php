<!doctype html>
<html class="left-sidebar-panel sidebar-light">

<head>

    <!-- Basic -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/png" href="<?= FRONTEND_ASSETS ?>images/fav.png" />

    <title>
        <?php
        echo htmlspecialchars($GLOBALS['tab_title'] ?? $GLOBALS['settings']['admin_dashboard_name'] ?? 'RB Tech');
        ?>
    </title>


    <meta name="keywords" content="RB Tech Admin Template" />
    <meta name="description" content="Admin - Responsive RB Tech Template">
    <meta name="author" content="rbtech.in">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="<?= ADMIN_ASSETS ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ADMIN_ASSETS ?>plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="<?= ADMIN_ASSETS ?>plugins/pace/pace.css" rel="stylesheet">
    <link href="<?= ADMIN_ASSETS ?>plugins/highlight/styles/github-gist.css" rel="stylesheet">
    <link href="<?= ADMIN_ASSETS ?>plugins/datatables/datatables.min.css" rel="stylesheet">
    <link href="<?= ADMIN_ASSETS ?>plugins/select2/css/select2.min.css" rel="stylesheet">

    <link href="<?= ADMIN_ASSETS ?>plugins/dropzone/min/dropzone.min.css" rel="stylesheet">

    <link href="<?= ADMIN_ASSETS ?>plugins/summernote/summernote-lite.min.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="<?= ADMIN_ASSETS ?>css/main.min.css" rel="stylesheet">
    <link href="<?= ADMIN_ASSETS ?>css/custom.css" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="<?= ADMIN_ASSETS ?>images/neptune.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= ADMIN_ASSETS ?>images/neptune.png" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body>
    <div class="app align-content-stretch d-flex flex-wrap">