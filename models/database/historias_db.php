<?php
namespace app\models\database;
use mysqli;
use RuntimeException;

class HistoriasDB implements HistoriasDBInterface{
    private string $host_db = "localhost";
    private string $user_db = "root";
    private string $pwd_db = "";
    private string $name_db = "gestor_historias_db";
    private int $port_db = 3306;
    private mysqli $conex;

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

    public function __destruct()
    {
        $this->conex->close();
    }

    public function query_select(string $sql): array
    {
        $result = $this->conex->query($sql);
        if ($result === false) {
            throw new RuntimeException($this->conex->error);
        }
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row);
        }
        return $rows;
    }

    public function query_select_prepared(string $sql, array $parameters): array
    {
        $statement = $this->prepare_statement($sql, $parameters);
        try {
            if (!$statement->execute()) {
                throw new RuntimeException($statement->error);
            }
            $result = $statement->get_result();
            if ($result === false) {
                throw new RuntimeException($statement->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } finally {
            $statement->close();
        }
    }

    public function query(string $sql): bool
    {
        return $this->conex->query($sql);
    }

    public function query_prepared(string $sql, array $parameters): bool
    {
        $statement = $this->prepare_statement($sql, $parameters);
        try {
            if (!$statement->execute()) {
                throw new RuntimeException($statement->error);
            }
            return true;
        } finally {
            $statement->close();
        }
    }

    private function prepare_statement(string $sql, array $parameters): \mysqli_stmt
    {
        $statement = $this->conex->prepare($sql);
        if ($statement === false) {
            throw new RuntimeException($this->conex->error);
        }

        if (!empty($parameters)) {
            $values = array_values($parameters);
            $types = "";
            foreach ($values as $value) {
                $types .= is_int($value) ? "i" : (is_float($value) ? "d" : "s");
            }

            $boundParameters = [$types];
            foreach ($values as $key => &$value) {
                $boundParameters[] = &$value;
            }
            unset($value);

            if (!call_user_func_array([$statement, "bind_param"], $boundParameters)) {
                $error = $statement->error;
                $statement->close();
                throw new RuntimeException($error);
            }
        }

        return $statement;
    }
}
?>