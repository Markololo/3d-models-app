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

        $password = $data['password'];

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        //? Insert a new user into the users table
        $sql = "INSERT INTO users(first_name, last_name, username, email, password_hash, role) VALUES (:first_name, :last_name, :username, :email, :password_hash, :role)";

        $this->execute($sql, [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $hashedPassword,
            'role' => $data['role']
        ]);

        return (int)$this->lastInsertId();
    }

    /**
     * Find a user by email address.
     *
     * @param string $email The email address to search for
     * @return array|null User data array or null if not found
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * from users where email = :email LIMIT 1";

        $user = $this->selectOne($sql, ['email' => $email]);

        return $user;
    }

    /**
     * Find a user by username.
     *
     * @param string $username The username to search for
     * @return array|null User data array or null if not found
     */
    public function findByUsername(string $username)
    {
        $sql = "SELECT * from users where username = :username LIMIT 1";

        $user = $this->selectOne($sql, ['username' => $username]);

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

        $result = $this->selectOne($sql, ['email' => $email]);

        if (isset($result['count']) && (int)$result['count'] > 0) {
            return true;
        } else {
            return false;
        }
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

        $result = $this->selectOne($sql, ['username' => $username]);

        if (isset($result['count']) && (int)$result['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify user credentials by email/username and password.
     *
     * @param string $identifier Email or username
     * @param string $password Plain-text password to verify
     * @return array|null User data if credentials are valid, null otherwise
     */
    public function verifyCredentials(string $identifier, string $password): ?array
    {
        //? Try to find user by email first
              $user = $this->findByEmail($identifier);

        //? If user not found by email, try finding by username
              if (!$user || $user == null) {
                  $user = $this->findByUsername($identifier);
              }

        //? If user still not found, return null (invalid credentials)
              if(!$user || $user == null) {
                return null;
              }

        //? Verify the password using password_verify($password, $user['password_hash'])
        if (password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }
}
