<?php
require_once __DIR__ . '../core/globals.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['mov_trabajador'])) {
    header("Location: index.php");
    exit;
}

$trab = $_SESSION['mov_trabajador'];

$tipo_vehiculo = trim($_POST['tipo_vehiculo'] ?? '');
$placa = trim($_POST['placa'] ?? '');
$propietario = trim($_POST['propietario'] ?? '');
$soat_vigente = trim($_POST['soat_vigente'] ?? '');
$soat_vencimiento = trim($_POST['soat_vencimiento'] ?? '');

if ($tipo_vehiculo === '' || $placa === '' || $propietario === '' || $soat_vigente === '') {
    die("Faltan campos obligatorios.");
}

$archivoRuta = null;

// ✅ Manejo de archivo
if (isset($_FILES['archivo_soat']) && $_FILES['archivo_soat']['error'] === UPLOAD_ERR_OK) {
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($_FILES['archivo_soat']['size'] > $maxSize) {
        die("El archivo supera 5MB.");
    }

    $tmp = $_FILES['archivo_soat']['tmp_name'];
    $name = $_FILES['archivo_soat']['name'];

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $permitidos = ['pdf', 'jpg', 'jpeg', 'png'];

    if (!in_array($ext, $permitidos)) {
        die("Formato no permitido. Solo PDF/JPG/PNG.");
    }

    $carpeta = __DIR__ . '/uploads/soat/';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0775, true);
    }

    $nuevoNombre = 'SOAT_' . $trab['dni'] . '_' . date('Ymd_His') . '.' . $ext;
    $destino = $carpeta . $nuevoNombre;

    if (!move_uploaded_file($tmp, $destino)) {
        die("No se pudo guardar el archivo.");
    }

    // ruta guardada en BD (relativa)
    $archivoRuta = 'uploads/soat/' . $nuevoNombre;
}

// ✅ Insert en BD (mysqli)
$stmt = $conexion->prepare("
    INSERT INTO hse_movilidad_registro
    (id_trabajador, dni, nombres, tipo_vehiculo, placa, propietario, soat_vigente, soat_vencimiento,
     archivo_soat, ip, user_agent)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$idTrab = $trab['id'];
$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

// soat_vencimiento puede ser null
$soatVenc = $soat_vencimiento !== '' ? $soat_vencimiento : null;

$stmt->bind_param(
    "issssssssss",
    $idTrab,
    $trab['dni'],
    $trab['nombres'],
    $tipo_vehiculo,
    $placa,
    $propietario,
    $soat_vigente,
    $soatVenc,
    $archivoRuta,
    $ip,
    $ua
);

$stmt->execute();

// limpiar sesión para que no reusen
unset($_SESSION['mov_trabajador']);

header("Location: success.php");
exit;