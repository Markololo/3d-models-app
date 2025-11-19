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
        // TODO: Write a SELECT SQL query to find a user by email
        //       Select all columns from the users table where email matches
        //       Use named parameter :email and LIMIT 1

        // TODO: Execute the query and return the result
        return [];
    }

    /**
     * Find a user by username.
     *
     * @param string $username The username to search for
     * @return array|null User data array or null if not found
     */
    public function findByUsername(string $username): ?array
    {
        // TODO: Write a SELECT SQL query to find a user by username
        //       Select all columns from the users table where username matches
        //       Use named parameter :username and LIMIT 1

        // TODO: Execute the query and return the result
        return [];
    }

    /**
     * Check if an email address already exists in the database.
     *
     * @param string $email The email address to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $email): bool
    {
        // TODO: Write a SELECT COUNT(*) query to count users with the given email
        //       Alias the count as 'count'
        //       Use named parameter :email

        // TODO: Execute the query and return true if count > 0, false otherwise
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
        // TODO: Write a SELECT COUNT(*) query to count users with the given username
        //       Alias the count as 'count'
        //       Use named parameter :username

        // TODO: Execute the query and return true if count > 0, false otherwise
        return false;
    }
}
