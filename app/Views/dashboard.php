<?php require __DIR__ . '/common/header.php'; ?>

<a href="<?= '/' . APP_ROOT_DIR_NAME . '/user' . '/' ?>" class="btn btn-primary">Back to Dashboard</a>
<div class="container" style="max-width: 800px; margin: 50px auto;">

    <br>

    <h1>Dashboard</h1>

    <div class="welcome-section">
        <h2>Welcome, <?= htmlspecialchars($user['first_name'] ?? 'User') ?>!</h2>
        <p>Email: <?= htmlspecialchars($user['email'] ?? '') ?></p>
    </div>

    <hr>

    <div class="security-section">
        <h3>Security Settings</h3>

        <div class="security-card">
            <h4>Two-Factor Authentication (2FA)</h4>

            <?php if ($has2FA): ?>
                <p class="status status-enabled">✓ 2FA is <strong>enabled</strong></p>
                <p>Your account is protected with two-factor authentication.</p>

                <form method="POST" action="<?= '/' . APP_ROOT_DIR_NAME . '/2fa/disable' ?>">
                    <button type="submit" class="btn btn-danger">Disable 2FA</button>
                </form>
            <?php else: ?>
                <p class="status status-disabled">⚠ 2FA is <strong>disabled</strong></p>
                <p>Enable 2FA to add an extra layer of security to your account.</p>

                <a href="<?= '/' . APP_ROOT_DIR_NAME . '/2fa/setup' ?>" class="btn btn-primary">Enable 2FA</a>
            <?php endif; ?>
        </div>
    </div>

    <hr>

    <div class="actions">
        <form method="POST" action="<?= '/' . APP_ROOT_DIR_NAME . '/logout' ?>">
            <button type="submit" class="btn btn-secondary">Logout</button>
        </form>
    </div>
</div>

<style>
    .welcome-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .security-section {
        margin: 20px 0;
    }

    .security-card {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
    }

    .status {
        padding: 10px;
        border-radius: 4px;
        margin: 10px 0;
    }

    .status-enabled {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-disabled {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .actions {
        margin-top: 30px;
    }

    hr {
        margin: 30px 0;
        border: none;
        border-top: 1px solid #ddd;
    }
</style>

<?php require __DIR__ . '/common/footer.php'; ?>