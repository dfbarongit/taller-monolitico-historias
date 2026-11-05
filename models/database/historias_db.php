<?php
namespace app\models\database;
use mysqli;

class HistoriasDB implements HistoriasDBInterface{
    private string $host_db = "localhost";
    private string $user_db = "root";
    private string $pwd_db = "";
    private string $name_db = "gestor_historias_db";
    private int $port_db = 3306;
    private $conex = null;

    public function __construct()
    {
        $this->conex = new mysqli(
            $this->host_db,
            $this->user_db,
            $this->pwd_db,
            $this->name_db,
            $this->port_db
        );
    }

    public function query_select(string $sql): array
    {
        $result = $this->conex->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row);
        }
        $this->conex->close();
        return $rows;
    }

    public function query(string $sql): bool
    {
        $result = $this->conex->query($sql);
        $this->conex->close();
        return $result;
    }
}
?>