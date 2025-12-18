<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">E-Commerce</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?>!
                </span>
                <div class="actions">
                    <form method="POST" action="<?= '/' . APP_ROOT_DIR_NAME . '/logout' ?>">
                        <button class="btn btn-outline-light btn-sm" href="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>User Dashboard</h1>

        <div class="mb-4">
            <?= App\Helpers\FlashMessage::render() ?>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">
                            <strong>Name:</strong> <?= htmlspecialchars($_SESSION['user_name'] ?? 'N/A') ?><br>
                            <strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email'] ?? 'N/A') ?><br>
                            <strong>Role:</strong> <?= htmlspecialchars($_SESSION['user_role'] ?? 'N/A') ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="<?= '/' . APP_ROOT_DIR_NAME . '/user/products' ?>" class="btn btn-primary">Browse Products</a>
                            <a href="#" class="btn btn-secondary">My Orders</a>
                            <a href="<?= '/' . APP_ROOT_DIR_NAME . '/dashboard' ?>" class="btn btn-info">Update Profile</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>