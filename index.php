<?php
include 'header.php';

$error = null;
$date = date('Y-m-d');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni   = trim($_POST['dni'] ?? '');
    $nac   = trim($_POST['fecha_nacimiento'] ?? '');

    if (!preg_match('/^\d{8}$/', $dni)) {
        $error = "DNI inválido (8 dígitos).";
    } elseif (!$nac) {
        $error = "Ingrese su fecha de nacimiento.";
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
            $error = "No se encontró un trabajador activo con esos datos.";
        } else {
            //identifico si este personal ya está registrado
            if(!empty($trab->id_hse_movilidad)){
                $error = "Ya existe un registro de " . $trab->person_name . " " . $trab->person_surname . " " . $trab->person_surname2;
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


        <div class="card-body">
            <div>   
                <strong>Validación de Trabajador</strong>
            </div>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST" autocomplete="off">
            <div class="form-group">
              <label>DNI</label>
              <input type="text" name="dni" class="form-control" maxlength="8" required>
            </div>
            <div class="form-group">
              <label>Fecha de nacimiento</label>
              <input type="date" name="fecha_nacimiento" class="form-control" required>
            </div>
            <button class="btn btn-primary btn-block">Continuar</button>
          </form>

        </div>
<?php
include 'footer.php';
?>