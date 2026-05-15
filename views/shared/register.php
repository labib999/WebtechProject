<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');
    $error    = '';

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'This email is already registered.';
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, role, is_active) VALUES (?, ?, ?, ?, 'venue_manager', 0)");
            $stmt->bind_param('ssss', $name, $email, $phone, $hash);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $success = 'Registration submitted. Please wait for admin approval before logging in.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background:#f8fafc;">
  <div class="w-100" style="max-width:480px; padding:24px;">

    <div class="text-center mb-4">
      <h4 class="fw-bold mb-1">EM<span style="color:#3b82f6">TS</span></h4>
      <h5 class="fw-semibold">Create Venue Manager Account</h5>
      <p class="text-muted" style="font-size:14px;">Register and wait for admin approval</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card p-4">
      <form method="POST">

        <div class="mb-3">
          <label class="form-label fw-500">Full Name</label>
          <input type="text" name="name" class="form-control"
                 placeholder="Enter your full name"
                 value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control"
                 placeholder="Enter your email"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control"
                 placeholder="e.g. 01711000000"
                 value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control"
                 placeholder="Minimum 6 characters">
        </div>

        <div class="mb-4">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm" class="form-control"
                 placeholder="Re-enter your password">
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-person-plus me-2"></i> Register
        </button>

      </form>
    </div>

    <p class="text-center text-muted mt-3" style="font-size:14px;">
      Already have an account?
      <a href="login.php" style="color:#3b82f6;">Sign in here</a>
    </p>

  </div>
</div>

<?php
$skipNavbar = true;
include 'footer.php';
?>