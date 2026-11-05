<?php
namespace app\models\database;

interface HistoriasDBInterface
{
    public function query_select(string $sql): array;
    public function query(string $sql): bool;
}
?>