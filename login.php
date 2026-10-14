<?php
session_start();
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] == "login") {
    if ($_SESSION['Status'] == 1) {
        header("location:admin/dashboard_admin.php");
    } else if ($_SESSION['Status'] == 2) {
        header("location:kasir/dashboard_kasir.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kynzalia Fashion</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow border-0 mt-5 bg-dark text-white">
                    <div class="card-header bg-dark text-white text-center py-3 border-bottom border-secondary">
                        <h4 class="mb-0 fw-bold">LOGIN SYSTEM</h4>
                        <small class="text-muted">Kin-kin Collection</small>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="proses_login.php">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control bg-secondary text-white border-0" name="Username" placeholder="Masukkan username" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control bg-secondary text-white border-0" name="Password" placeholder="Masukkan password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-light fw-bold">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="asset/js/bootstrap.bundle.min.js"></script>
</body>
</html>