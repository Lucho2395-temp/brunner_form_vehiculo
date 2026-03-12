<?php
require_once __DIR__ . '/../core/globals.php';
require_once __DIR__ . '/../core/Database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['mov_trabajador'])) {
    header("Location: "._SERVER_."index.php");
    exit;
}

$db = Database::getConnection();
$trab = $_SESSION['mov_trabajador'];

$tipo_vehiculo = trim($_POST['tipo_vehiculo'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$placa = trim($_POST['placa'] ?? '');
$propietario = trim($_POST['propietario'] ?? '');
$licencia_vigente = trim($_POST['licencia_vigente'] ?? '');
$soat_vigente = trim($_POST['soat_vigente'] ?? '');
$soat_fecha_nacimiento = trim($_POST['soat_fecha_nacimiento'] ?? '');

if ($tipo_vehiculo === '' || $placa === '' || $propietario === '' || $soat_vigente === '' || $licencia_vigente === '') {
    die("Faltan campos obligatorios.");
}

$archivoRuta = null;

//Manejo de archivo
/* if (isset($_FILES['archivo_soat']) && $_FILES['archivo_soat']['error'] === UPLOAD_ERR_OK) {
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
} */

//Insert en BD
$stmt = $db->prepare("
    INSERT INTO hse_movilidad
    (id_person , hse_movilidad_tipo_vehiculo, hse_movilidad_marca , hse_movilidad_modelo, 
    hse_movilidad_placa , hse_movilidad_propietario, hse_movilidad_licencia_vigente, 
    hse_movilidad_soat_vigencia, hse_movilidad_soat_fecha, hse_movilidad_datetime,
    ip_registro, dispositivo_registro)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$id_person = $trab['id'];
$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

// soat_vencimiento puede ser null
$soatVenc = $soat_fecha_nacimiento !== '' ? $soat_fecha_nacimiento : null;
$date = date('Y-m-d H:i:s');
$stmt->execute([
    $id_person,
    $tipo_vehiculo,
    $marca,
    $modelo,
    $placa,
    $propietario,
    $licencia_vigente,
    $soat_vigente,
    $soatVenc,
    $date,
    $ip,
    $ua
]);


// limpiar sesión para que no reusen
unset($_SESSION['mov_trabajador']);

header("Location: "._SERVER_."success.php");
exit;