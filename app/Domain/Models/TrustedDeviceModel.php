<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Helpers\DateTimeHelper;

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
        $sql = "INSERT INTO trusted_devices
        (user_id, device_token, device_name, user_agent, ip_address, expires_at, last_used_at, created_at) VALUES (:user_id, :device_token, :device_name, :user_agent, :ip_address, :expires_at, :last_used_at, :created_at)";
        // HINT: Use $this->execute() for INSERT
        // Fields: user_id, device_token, device_name, user_agent, ip_address, expires_at

        $params = [
            "user_id" => $userId,
            "device_token" => $deviceToken,
            "device_name" => $deviceInfo['device_name'] ?? '',
            "user_agent" => $deviceInfo['user_agent'] ?? '',
            "ip_address" => $deviceInfo['ip_address'] ?? '',
            "expires_at" => $deviceInfo['expires_at'] ?? DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M_S),
            "last_used_at" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M_S),
            "created_at" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M_S),

        ];

        $this->execute($sql, $params);
        return (int)($this->lastInsertId() ?? 0); // Replace with your implementation
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
        // $sql =" SELECT * FROM trusted_devices WHERE device_token = ? AND user_id = ? AND expires_at > NOW()";
        $sql = " SELECT * FROM trusted_devices WHERE device_token = :device_token AND user_id = :user_id AND expires_at > NOW()";

        $row = $this->selectOne($sql, [
            'device_token' => $deviceToken,
            'user_id' => $userId
        ]);


        return $row !== false; // Replace with your implementation
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
        $sql = " UPDATE trusted_devices SET last_used_at = NOW() WHERE device_token = :device_token";
        return $this->execute($sql, ['device_token' => $deviceToken]) > 0; // Replace with your implementation
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
        $sql = " SELECT * FROM trusted_devices WHERE user_id = :user_id AND expires_at > NOW() ORDER BY last_used_at DESC";

        // HINT: Use $this->selectAll()

        return $this->selectAll($sql, ['user_id' => $userId]) ?: []; // Replace with your implementation
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
        $sql = " DELETE FROM trusted_devices WHERE id = :id AND user_id = :user_id";
        return $this->execute($sql, ['id' => $deviceId, 'user_id' => $userId]) > 0; // Replace with your implementation
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
        $sql = " DELETE FROM trusted_devices WHERE user_id = :user_id";

        return $this->execute($sql, ['user_id' => $userId]) > 0; // Replace with your implementation
    }
}
