<?php
namespace app\models\database;

interface HistoriasDBInterface
{
    public function query_select(string $sql): array;
    public function query_select_prepared(string $sql, array $parameters): array;
    public function query(string $sql): bool;
    public function query_prepared(string $sql, array $parameters): bool;
}
?>