<?php
require "../models/database/historias_db_interface.php";
require "../models/database/historias_db.php";
require "../models/entities/Sprint.php";
require "../models/entities/Historia.php";
require "../models/mappers/sprint_mappers.php";
require "../models/repository/sprints_repository/sprints_repository_interface.php";
require "../models/repository/sprints_repository/sprints_repository.php";
require "../models/config/models_config.php";

require "../controllers/mappers/sprint_mappers_controller.php";
require "../controllers/sprints/sprints_controller_interface.php";
require "../controllers/sprints/sprints_controller.php";
require "../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;
use app\models\entities\Historia;

$controller = ControllersConfig::get_sprints_controller();
$sprints = $controller->get_sprints_all();

$idSprintActual = (empty($_GET["id"])) ? $sprints[0]->getId() : $_GET["id"];
$sprint = "";
//$historiasDelSprint = $controller->get_historias_by_sprint();
$historiasDelSprint = [
    new Historia(
        1,
        'Registro de usuarios con Email',
        'Como usuario quiero registrarme en la plataforma usando correo y contraseña para acceder a mis tableros.',
        'Carlos Mendoza',
        'finalizada',
        5,
        new DateTime('2026-10-01'),
        new DateTime('2026-10-05'),
        1,
        new DateTime('2026-10-01 08:00:00'),
        new DateTime('2026-10-05 17:30:00')
    ),
    new Historia(
        2,
        'Inicio de sesión y autenticación JWT',
        'Como usuario registrado quiero autenticarme de forma segura para proteger mis datos.',
        'Ana Gómez',
        'finalizada',
        3,
        new DateTime('2026-10-01'),
        new DateTime('2026-10-07'),
        1,
        new DateTime('2026-10-01 09:15:00'),
        new DateTime('2026-10-07 16:45:00')
    ),
    new Historia(
        3,
        'Recuperación de contraseña vía correo',
        'Como usuario me gustaría restablecer mi contraseña olvidada mediante un enlace enviado a mi email.',
        'David Barón',
        'finalizada',
        5,
        new DateTime('2026-10-02'),
        new DateTime('2026-10-12'),
        1,
        new DateTime('2026-10-02 10:30:00'),
        new DateTime('2026-10-12 14:20:00')
    ),
    new Historia(
        4,
        'Edición de perfil de usuario',
        'Como usuario quiero cambiar mi avatar y mi nombre visible para personalizar mi cuenta.',
        'Nicol Ruiz',
        'impedimento',
        2,
        new DateTime('2026-10-03'),
        null,
        1,
        new DateTime('2026-10-03 11:00:00'),
        new DateTime('2026-10-03 11:00:00')
    )
];

foreach ($sprints as $spr) {
    if ($idSprintActual == $spr->getId()) {
        $sprint = $spr;
        break;
    }
}
if (empty($sprint)) {
    header("Location: sprint_form.php?sinReg=1");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Gestor de historias de usuario</h1>
    <?php
    if (!empty($_GET['error'])) {
        echo '<p>Error al guardar los datos del usuario!!!</p>';
    }
    ?>
    <header>
        <ul>
            <li><a href="inicio.php">Sprints</a></li>
            <li><a href="informes.php">Informes</a></li>
        </ul>
    </header>
    <nav>
        <ul>
            <?php
            foreach ($sprints as $spr) {
                echo '<li><a href="inicio.php?id=' . $spr->getId() . '">' . $spr->getId() . ". " . $spr->getNombre() . '</a></li>';
            }
            ?>
            <a href="sprint_form.php">Nuevo Sprint</a>
        </ul>
    </nav>
    <div>
        <h2><?php
        echo $sprint->getNombre();
        ?>
        </h2>
        <a href="sprint_form.php?id=<?php echo $idSprintActual ?>">Modificar sprint</a>
        <a href="operaciones/sprint/eliminar_sprint.php?id=<?php echo $idSprintActual ?>">Eliminar sprint</a>
    </div>
    <div>
        <a href="historia_form.php?idSprint=<?php echo $idSprintActual ?>">Crear nueva historia</a>
        <?php
        foreach ($historiasDelSprint as $historia) {
            echo "<br>";
            echo $historia->getId();
            echo "<br>";
            echo $historia->getTitulo();
            echo '<a href="historia_form.php?idSprint=' . $idSprintActual . '&id=' . $historia->getId() . '">Modificar historia</a>';
            echo "<br>";
            echo $historia->getDescripcion();
            echo "<br>";
            echo $historia->getEstado();
            echo "<br>";
            echo $historia->getPuntos();
            echo "<br>";
            echo $historia->getFechaCreacion();
            echo "<br>";
            echo $historia->getFechaFinalizacion();
            echo "<br>";
            
        }
        ?>


    </div>


</body>

</html>