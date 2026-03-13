<?php

session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

include 'header.php';
?>
    <div class="card-body">
        <div>   
            <strong>Validación de Trabajador</strong>
        </div>
        <?php if ($error): ?>
        <div class="alert alert-danger"><?= ($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="models/ingresar.php" autocomplete="off">
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