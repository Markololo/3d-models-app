<?php

declare(strict_types=1);

namespace App\Domain\Models;

/**
 * Model for managing trusted devices.
 */
class TrustedDeviceModel extends BaseModel
{
    /**
     * Create a new trusted device record.
     *
     * @param int $userId
     * @param string $deviceToken
     * @param array $deviceInfo ['device_name', 'user_agent', 'ip_address', 'expires_at']
     * @return int
     */
    public function create(int $userId, string $deviceToken, array $deviceInfo): int
    {
        // TODO: Insert a new record into trusted_devices table
        // HINT: Use $this->execute() for INSERT
        // Fields: user_id, device_token, device_name, user_agent, ip_address, expires_at

        return 0; // Replace with your implementation
    }

    /**
     * Check if a device token is valid for a user.
     *
     * @param string $deviceToken
     * @param int $userId
     * @return bool True if token is valid and not expired
     */
    public function isValid(string $deviceToken, int $userId): bool
    {
        // TODO: Query to check if token exists, belongs to user, and hasn't expired
        // HINT: Use $this->selectOne()
        // SQL: SELECT * FROM trusted_devices WHERE device_token = ? AND user_id = ? AND expires_at > NOW()

        return false; // Replace with your implementation
    }

    /**
     * Update the last_used_at timestamp for a device.
     *
     * @param string $deviceToken
     * @return bool
     */
    public function updateLastUsed(string $deviceToken): bool
    {
        // TODO: Update last_used_at = NOW() for the device

        return false; // Replace with your implementation
    }

    /**
     * Get all trusted devices for a user.
     *
     * @param int $userId
     * @return array
     */
    public function getAllByUserId(int $userId): array
    {
        // TODO: Select all non-expired devices for the user
        // HINT: Use $this->selectAll()

        return []; // Replace with your implementation
    }

    /**
     * Revoke (delete) a specific device.
     *
     * @param int $deviceId
     * @param int $userId
     * @return bool
     */
    public function revoke(int $deviceId, int $userId): bool
    {
        // TODO: Delete the device record
        // IMPORTANT: Include user_id in WHERE clause for security

        return false; // Replace with your implementation
    }

    /**
     * Revoke all devices for a user.
     *
     * @param int $userId
     * @return bool
     */
    public function revokeAll(int $userId): bool
    {
        // TODO: Delete all trusted devices for the user

        return false; // Replace with your implementation
    }
}
