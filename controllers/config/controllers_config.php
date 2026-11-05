<?php
namespace app\controllers\config;
use app\models\config\ModelsConfig;
use app\controllers\historias\HistoriasControllerInterface;
use app\controllers\historias\HistoriasController;
use app\controllers\sprints\SprintsControllerInterface;
use app\controllers\sprints\SprintsController;

class ControllersConfig
{
    public static function get_historias_controller(): HistoriasControllerInterface
    {
        return new HistoriasController(ModelsConfig::get_historias_repository());
    }

    public static function get_sprints_controller(): SprintsControllerInterface
    {
        return new SprintsController(ModelsConfig::get_sprints_repository());
    }
}
?>