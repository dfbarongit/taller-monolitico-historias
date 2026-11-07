<?php
require "../models/database/historias_db_interface.php";
require "../models/database/historias_db.php";
require "../models/entities/Sprint.php";
require "../models/mappers/sprint_mappers.php";
require "../models/repository/sprints_repository/sprints_repository_interface.php";
require "../models/repository/sprints_repository/sprints_repository.php";
require "../models/config/models_config.php";

require "../controllers/mappers/sprint_mappers_controller.php";
require "../controllers/sprints/sprints_controller_interface.php";
require "../controllers/sprints/sprints_controller.php";
require "../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_sprints_controller();
$sprints = $controller->get_sprints_all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="sprint_form.php">Nuevo sprint</a>
    <?php
        if(!empty($_GET['error'])){
            echo '<p>Error al guardar los datos del usuario!!!</p>';
        }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($sprints as $sprint) {
                echo '<tr>';
                echo '  <td>' . $sprint->getId() . '</td>';
                echo '  <td>' . $sprint->getNombre() . '</td>';
                echo '  <td>' . $sprint->getFechaInicio() . '</td>';
                echo '  <td>' . $sprint->getFechaFin() . '</td>';
                echo '  <td>';
                echo '      <a href="sprint_form.php?id=' . $sprint->getId() . '">Modificar</a>';
                echo '      <a href="operaciones/eliminar_sprint.php?id=' . $sprint->getId() . '">Borrar</a>';
                echo '  </td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>