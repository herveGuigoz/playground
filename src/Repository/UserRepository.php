<?php

namespace App\Repository;

use App\Entity\User;
use PDO;

class UserRepository extends AbstractRepository
{
    public const TABLE = 'users';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Get one user by email
     */
    public function findOneByEmail(string $email): User|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE email=:email");
        $statement->bindValue('email', $email);
        $statement->execute();

        $user = $statement->fetch();
        if ($user) {
            $user = new User();
            $user->setId((int) $user['id']);
            $user->setEmail($user['email']);
        }

        return $user;
    }

    /**
     * Insert new user in database
     */
    public function insert(User $user): User
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (email, google_id) VALUES (:email, :google_id)",
        );
        $statement->bindValue('email', $user->getEmail(), PDO::PARAM_STR);
        $statement->bindValue('google_id', $user->getGoogleId(), PDO::PARAM_STR);

        $statement->execute();

        $user->setId((int)$this->pdo->lastInsertId());

        return $user;
    }

    /**
     * Update user in database
     */
    public function update(User $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET google_id = :google_id WHERE id=:id");
        $statement->bindValue('google_id', $user->getGoogleId(), PDO::PARAM_STR);

        return $statement->execute();
    }
}
