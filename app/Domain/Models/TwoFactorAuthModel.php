<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;

/**
 * Model for managing Two-Factor Authentication data.
 */
class TwoFactorAuthModel extends BaseModel
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
        $sql = "SELECT * FROM two_factor_auth WHERE user_id = :userId";
        $user = $this->selectOne($sql, ["userId" => $userId]);
        return $user ?? null;
        // HINT: Use $this->selectOne() method from BaseModel
        // SQL: SELECT * FROM two_factor_auth WHERE user_id = ?
        // Return the result, or null if false
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
        $sql = "INSERT INTO two_factor_auth (user_id, secret, enabled) VALUES (:userId, :secret, 0)";
        $this->execute($sql, ["userId" => $userId, "secret" => $secret]);
        return (int)$this->lastInsertId() ?? 0;
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
        $sql = "UPDATE two_factor_auth SET enabled = 1, enabled_at = NOW() WHERE user_id = :userId";
        $update = $this->execute($sql, ["userId" => $userId]);

        return $update > 0;
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
        $sql = "UPDATE two_factor_auth SET enabled = 0 WHERE user_id = :userId";
        $update = $this->execute($sql, ["userId" => $userId]);

        return $update > 0;
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
        $sql = "SELECT enabled FROM two_factor_auth WHERE user_id = :userId";
        $row = $this->selectOne($sql, ["userId" => $userId]);

        if ($row <= 0)
            return false;

        return $row['enabled'] == 1;
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
        $user = $this->findByUserId($userId);

        return $user['secret'] ?? null;
    }




}
