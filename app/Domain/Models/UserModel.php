<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class UserModel extends BaseModel
{
    public function __construct(PDOService $pdoService)
    {
        parent::__construct($pdoService);
    }

    /**
     * Create a new user account.
     *
     * @param array $data User data (first_name, last_name, username, email, password, role)
     * @return int The ID of the newly created user
     */
    public function createUser(array $data): int
    {
        // TODO: Hash the password using password_hash() with PASSWORD_BCRYPT
        //       Store the result in $hashedPassword variable

        // TODO: Write an INSERT SQL query to insert a new user into the users table
        //       Insert: first_name, last_name, username, email, password_hash, role
        //       Use named parameters (e.g., :first_name, :last_name, etc.)

        // TODO: Execute the query with appropriate parameters
        //       Use $hashedPassword for the password_hash field

        // TODO: Return the last inserted ID
        return -1;
    }

    /**
     * Find a user by email address.
     *
     * @param string $email The email address to search for
     * @return array|null User data array or null if not found
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * from users where email = :email LIMIT 1";

        $user = $this->selectOne($sql, ['email'=>$email]);

        return $user;
    }

    /**
     * Find a user by username.
     *
     * @param string $username The username to search for
     * @return array|null User data array or null if not found
     */
    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * from users where username = :username LIMIT 1";

        $user = $this->selectOne($sql, ['username'=>$username]);

        return $user;
    }

    /**
     * Check if an email address already exists in the database.
     *
     * @param string $email The email address to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) as count from users where email = :email";

        $count = $this->selectOne($sql, ['email'=>$email]);

        if($count > 0)
            return true;
        else
            return false;
    }

    /**
     * Check if a username already exists in the database.
     *
     * @param string $username The username to check
     * @return bool True if username exists, false otherwise
     */
    public function usernameExists(string $username): bool
    {
        $sql = "SELECT COUNT(*) as count from users where username = :username";

        $count = $this->selectOne($sql, ['username'=>$username]);

        if($count > 0)
            return true;
        else
            return false;
    }
}
