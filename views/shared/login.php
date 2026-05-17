<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMTS — Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0F6E56 0%, #1E293B 100%);
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #0F6E56, #10B981);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1.25rem;
        }

        .login-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1E293B;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: #64748B;
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            border-color: #E2E8F0;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #0F6E56;
            box-shadow: 0 0 0 3px rgba(15,110,86,0.12);
        }

        .btn-login {
            background: linear-gradient(135deg, #0F6E56, #10B981);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem;
            width: 100%;
            margin-top: 0.5rem;
            cursor: pointer;
            transition: opacity 0.15s;
        }

        .btn-login:hover { opacity: 0.9; }

        .input-group-text {
            background: #F8FAFC;
            border-color: #E2E8F0;
            border-radius: 10px 0 0 10px;
            color: #64748B;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .admin-badge {
            display: inline-block;
            background: #F0FDF4;
            color: #0F6E56;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<div class="login-card">

    <!-- LOGO -->
    <div class="login-logo">🛡️</div>

    <!-- TITLE -->
    <h1 class="login-title">Admin</h1>
    <p class="login-subtitle">Event Management & Ticketing System</p>

    <div class="text-center">
        <span class="admin-badge">🔒 Admin Access Only</span>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?> mb-3" style="font-size:0.85rem;">
            <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- LOGIN FORM -->
    <form method="POST" action="../../controllers/AuthController.php">
        <input type="hidden" name="action" value="login">

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="admin@platform.local" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Admin Panel
        </button>
    </form>

</div>

</body>
</html>