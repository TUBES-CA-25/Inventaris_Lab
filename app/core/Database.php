<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    private $dbh;
    private $stmt;

    public function __construct()
    {
        // data source name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
        } catch (PDOException $e) {
            // Set error session untuk validasi
            $_SESSION['has_error'] = true;
            $_SESSION['error_type'] = 'database';
            $_SESSION['error_message'] = $e->getMessage();
            $_SESSION['error_code'] = $e->getCode();

            // Redirect ke error page database
            header("Location: " . BASEURL . "ErrorPage/databaseError");
            exit;
        }
    }

    public function query($query)
    {
        try {
            $this->stmt = $this->dbh->prepare($query);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function bind($param, $value, $type = null)
    {

        if (is_null($type)) {

            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        try {
            $this->stmt->execute();
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }


    public function resultSet()
    {
        try {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
            return [];
        }
    }

    public function single()
    {
        try {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
            return false;
        }
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
    //     public function rowCount() {
    //     return $this->stmt->execute() ? $this->stmt->rowCount() : 0;
    // }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function error()
    {
        return $this->stmt->errorInfo();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function commit()
    {
        return $this->dbh->commit();
    }

    public function rollBack()
    {
        return $this->dbh->rollBack();
    }

    /**
     * Handle database errors and redirect to error page
     */
    private function handleDatabaseError($e)
    {
        // Set error session
        $_SESSION['has_error'] = true;
        $_SESSION['error_type'] = 'database';
        $_SESSION['error_message'] = $e->getMessage();
        $_SESSION['error_code'] = $e->getCode();
        $_SESSION['error_file'] = $e->getFile();
        $_SESSION['error_line'] = $e->getLine();

        // Redirect ke error page
        header("Location: " . BASEURL . "ErrorPage/databaseError");
        exit;
    }
}
