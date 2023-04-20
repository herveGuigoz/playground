<?php

namespace App\Repository;

use PDO;

/**
 * This class make a PDO object instanciation.
 */
class Connection
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO(DATABASE_URL);

        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
