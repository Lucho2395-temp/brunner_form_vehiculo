<?php
require_once __DIR__ . '/core/globals.php';
require_once __DIR__ . '/core/Database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$db = Database::getConnection();


$styles = _SERVER_ . _STYLES_ADMIN_;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registre su Movilidad</title>

  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css"> -->
    <link rel="icon" href="<?= _SERVER_ . _STYLES_ADMIN_;?>img/logo_intrag.jpg" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="<?= _SERVER_ . _STYLES_ADMIN_;?>assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Open+Sans:300,400,600,700"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['<?= _SERVER_ . _STYLES_ADMIN_;?>assets/css/fonts.css']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
<!-- CSS -->
  <link rel="stylesheet" href="<?= $styles ?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $styles ?>assets/css/azzara.min.css">
  <link rel="stylesheet" href="<?= $styles ?>assets/css/select2.min.css">
  <link rel="stylesheet" href="<?= $styles ?>assets/css/egg_styles.css">
  <link rel="stylesheet" href="<?= $styles ?>datatable/dataTables.bootstrap4.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <a href="<?= _SERVER_;?>" class="logo">
                        <img src="<?= _SERVER_ . _STYLES_ADMIN_;?>img/logo_intranet.png" width="165px" alt="navbar brand" class="navbar-brand">
                    </a>
                </div>