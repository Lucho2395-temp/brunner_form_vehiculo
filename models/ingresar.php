<?php
require_once __DIR__ . '/../core/globals.php';
require_once __DIR__ . '/../core/Database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$db = Database::getConnection();

$error = null;
$date = date('Y-m-d');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni   = trim($_POST['dni'] ?? '');
    $nac   = trim($_POST['fecha_nacimiento'] ?? '');

    if (!preg_match('/^\d{8}$/', $dni)) {
        $_SESSION['error'] = "DNI inválido (8 dígitos).";
        echo $_SESSION['error'];
    header("Location: "._SERVER_."index.php");
                exit;
    } elseif (!$nac) {
        $_SESSION['error'] = "Ingrese su fecha de nacimiento.";
        echo $_SESSION['error'];
    header("Location: "._SERVER_."index.php");
                exit;
    } else {

        $sql = "select p.*, hs.id_hse_movilidad
                    from periodolaboral pe 
                    inner join person p on pe.id_person = p.id_person
                    left join hse_movilidad hs on p.id_person = hs.id_person
                    where ? between pe.periodo_fechainicio and pe.periodo_fechafin and pe.periodo_estado = 1
                    and p.person_dni = ? and p.person_birth = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $date,
            $dni,
            $nac
        ]);

        $trab = $stmt->fetch(); // retorna OBJ por tu setAttribute FETCH_OBJ

        if (!$trab) {
            $_SESSION['error'] = "No se encontró un trabajador activo con esos datos.";
        echo $_SESSION['error'];
    header("Location: "._SERVER_."index.php");
                //exit;
        } else {
            //identifico si este personal ya está registrado
            if(!empty($trab->id_hse_movilidad)){
                $_SESSION['error'] = "Ya existe un registro de " . $trab->person_name . " " . $trab->person_surname . " " . $trab->person_surname2;
        echo $_SESSION['error'];
    header("Location: "._SERVER_."index.php");
                exit;
            }else{
                // guardar en sesión (como array simple)
                $_SESSION['mov_trabajador'] = [
                    'id' => $trab->id_person,
                    'dni' => $trab->person_dni,
                    'nombres' => $trab->person_name . " " . $trab->person_surname . " " . $trab->person_surname2,
                    'fecha_nacimiento' => $trab->person_birth,
                ];

                header("Location: "._SERVER_."form_vehiculo.php");
                exit;
            }
        }
    }
}

?>