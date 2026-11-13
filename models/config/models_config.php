<?php

namespace app\models\config;

use app\models\database\HistoriasDBInterface;
use app\models\database\HistoriasDB;
use app\models\repository\historias_repository\HistoriasRepositoryInterface;
use app\models\repository\historias_repository\HistoriasRepository;
use app\models\repository\sprints_repository\SprintsRepositoryInterface;
use app\models\repository\sprints_repository\SprintsRepository;
use app\models\repository\informes_repository\InformesRepositoryInterface;
use app\models\repository\informes_repository\InformesRepository;
class ModelsConfig
{
    public static function get_historias_db(): HistoriasDBInterface
    {
        return new HistoriasDB();
    }

    public static function get_historias_repository(): HistoriasRepositoryInterface
    {
        return new HistoriasRepository(ModelsConfig::get_historias_db());
    }
    public static function get_sprints_repository(): SprintsRepositoryInterface
    {
        return new SprintsRepository(ModelsConfig::get_historias_db());
    }
    public static function get_informes_repository(): InformesRepositoryInterface
    {
        return new InformesRepository(ModelsConfig::get_historias_db());
    }
}
?>