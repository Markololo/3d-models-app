<?php

declare(strict_types=1);

namespace App\Domain\Models;

/**
 * Model for managing Two-Factor Authentication data.
 */
class TwoFactorAuth extends BaseModel
{
    /**
     * Find 2FA record by user ID.
     *
     * @param int $userId The user's ID
     * @return array|null 2FA data or null if not found
     */
    public function findByUserId(int $userId): ?array
    {
        // TODO: Query the two_factor_auth table to find record by user_id
        // HINT: Use $this->selectOne() method from BaseModel
        // SQL: SELECT * FROM two_factor_auth WHERE user_id = ?
        // Return the result, or null if false

        return null; // Replace with your implementation
    }

    /**
     * Create a new 2FA record for a user.
     *
     * @param int $userId The user's ID
     * @param string $secret The TOTP secret key
     * @return int The ID of the created record
     */
    public function create(int $userId, string $secret): int
    {
        // TODO: Insert a new record into two_factor_auth table
        // HINT: Use $this->execute() for INSERT
        // Fields: user_id, secret, enabled (default false)
        // Return $this->lastInsertId()

        return 0; // Replace with your implementation
    }

    /**
     * Enable 2FA for a user.
     *
     * @param int $userId The user's ID
     * @return bool True if successful
     */
    public function enable(int $userId): bool
    {
        // TODO: Update the record to set enabled = true and enabled_at = NOW()
        // HINT: Use $this->execute() and check rowCount() > 0

        return false; // Replace with your implementation
    }

    /**
     * Disable 2FA for a user.
     *
     * @param int $userId The user's ID
     * @return bool True if successful
     */
    public function disable(int $userId): bool
    {
        // TODO: Update the record to set enabled = false
        // HINT: Use $this->execute() and check rowCount() > 0

        return false; // Replace with your implementation
    }

    /**
     * Check if user has 2FA enabled.
     *
     * @param int $userId The user's ID
     * @return bool True if 2FA is enabled
     */
    public function isEnabled(int $userId): bool
    {
        // TODO: Query to check if user has enabled = true
        // HINT: Use $this->selectOne() and check the 'enabled' field

        return false; // Replace with your implementation
    }

    /**
     * Get the secret for a user.
     *
     * @param int $userId The user's ID
     * @return string|null The secret or null if not found
     */
    public function getSecret(int $userId): ?string
    {
        // TODO: Get the secret field for the user
        // HINT: Use findByUserId() and return the 'secret' field

        return null; // Replace with your implementation
    }
}
