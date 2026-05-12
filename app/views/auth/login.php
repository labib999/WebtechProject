<?php // Variables: $error, $success ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In — Event Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <style>
    :root { --brand:#0F6E56; --brand-dark:#063D30; --brand-light:#E1F5EE; }
    * { box-sizing: border-box; }
    body { margin:0; font-family:'Segoe UI',system-ui,sans-serif; background:#f4f6f9; }

    /* ── Left panel ── */
    .auth-brand {
      min-height:100vh;
      background: linear-gradient(145deg, var(--brand-dark) 0%, #1a7a63 60%, var(--brand) 100%);
      display:flex; flex-direction:column; justify-content:center;
      padding:3.5rem; color:#fff; position:relative; overflow:hidden;
    }
    /* animated background circles */
    .auth-brand::before, .auth-brand::after {
      content:''; position:absolute; border-radius:50%; pointer-events:none;
    }
    .auth-brand::before {
      width:420px; height:420px; top:-140px; right:-140px;
      background:rgba(255,255,255,0.05);
      animation: floatA 7s ease-in-out infinite;
    }
    .auth-brand::after {
      width:280px; height:280px; bottom:-100px; left:-80px;
      background:rgba(255,255,255,0.04);
      animation: floatB 9s ease-in-out infinite;
    }
    @keyframes floatA { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-22px) scale(1.04)} }
    @keyframes floatB { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(18px) scale(1.06)} }

    .logo-box {
      width:54px; height:54px; background:rgba(255,255,255,0.15);
      border-radius:14px; display:flex; align-items:center;
      justify-content:center; font-size:1.5rem; margin-bottom:1.5rem;
      backdrop-filter:blur(4px);
    }
    .auth-brand h1 { font-size:2.1rem; font-weight:700; margin-bottom:0.4rem; }
    .auth-brand .tagline { color:rgba(255,255,255,0.68); margin-bottom:2.8rem; font-size:0.97rem; line-height:1.6; }

    .feat { display:flex; align-items:center; gap:1rem; margin-bottom:1.2rem; color:rgba(255,255,255,0.9); }
    .feat-icon {
      width:40px; height:40px; flex-shrink:0;
      background:rgba(255,255,255,0.12); border-radius:10px;
      display:flex; align-items:center; justify-content:center; font-size:1rem;
      transition:background .2s;
    }
    .feat:hover .feat-icon { background:rgba(255,255,255,0.22); }
    .feat strong { display:block; font-size:0.88rem; }
    .feat span { font-size:0.76rem; opacity:.68; }

    .stats-strip {
      display:flex; gap:2rem; margin-top:3rem;
      padding-top:2rem; border-top:1px solid rgba(255,255,255,0.15);
    }
    .stat-val { font-size:1.4rem; font-weight:700; display:block; }
    .stat-lbl { font-size:0.72rem; opacity:.65; }

    /* ── Right panel ── */
    .auth-right {
      min-height:100vh; display:flex; align-items:center;
      justify-content:center; padding:2.5rem; background:#fff;
    }
    .form-card {
      width:100%; max-width:430px;
      animation: slideUp .5s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes slideUp {
      from { opacity:0; transform:translateY(24px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .form-card h2 { font-size:1.75rem; font-weight:700; color:#111; margin-bottom:.25rem; }
    .form-card .sub { color:#6b7280; margin-bottom:2rem; font-size:.93rem; }

    .form-label { font-weight:600; font-size:.82rem; color:#374151; letter-spacing:.3px; text-transform:uppercase; }

    .input-group-text {
      background:#f9fafb; border-color:#e5e7eb;
      color:#9ca3af; transition:border-color .2s, color .2s;
    }
    .form-control {
      border-color:#e5e7eb; font-size:.95rem;
      padding:.65rem .9rem; transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus { border-color:var(--brand); box-shadow:0 0 0 3.5px rgba(15,110,86,.13); outline:none; }
    .input-group:focus-within .input-group-text { border-color:var(--brand); color:var(--brand); }

    /* Brand button */
    .btn-brand {
      background:var(--brand); color:#fff; border:none;
      padding:.75rem; font-size:.95rem; font-weight:600;
      border-radius:10px; letter-spacing:.2px;
      transition:background .2s, transform .12s, box-shadow .2s;
      box-shadow:0 4px 14px rgba(15,110,86,.3);
    }
    .btn-brand:hover {
      background:var(--brand-dark); color:#fff;
      box-shadow:0 6px 20px rgba(15,110,86,.38);
      transform:translateY(-1px);
    }
    .btn-brand:active { transform:translateY(0); box-shadow:none; }
    .btn-brand:disabled { opacity:.75; transform:none; }

    .divider {
      text-align:center; color:#d1d5db; font-size:.8rem;
      margin:1.6rem 0; position:relative; display:flex;
      align-items:center; gap:.75rem;
    }
    .divider::before, .divider::after {
      content:''; flex:1; height:1px; background:#e5e7eb;
    }
    .footer-link { text-align:center; font-size:.88rem; color:#6b7280; }
    .footer-link a { color:var(--brand); font-weight:600; text-decoration:none; }
    .footer-link a:hover { text-decoration:underline; }

    /* Alert tweaks */
    .alert { font-size:.88rem; border-radius:10px; padding:.75rem 1rem; }
    .alert-danger  { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
    .alert-success { background:var(--brand-light); border:1px solid #6ee7b7; color:#065f46; }
  </style>
</head>
<body>
<div class="container-fluid p-0">
  <div class="row g-0">

    <!-- ── Left: Brand ── -->
    <div class="col-lg-5 d-none d-lg-flex">
      <div class="auth-brand w-100" style="position:relative; z-index:1;">
        <div class="logo-box"><i class="bi bi-calendar-event-fill"></i></div>
        <h1>Event Platform</h1>
        <p class="tagline">Your all-in-one solution for creating events,<br>managing tickets and growing your audience.</p>

        <div class="feat"><div class="feat-icon"><i class="bi bi-qr-code-scan"></i></div>
          <div><strong>Live check-in</strong><span>Scan ticket codes in real time</span></div></div>

        <div class="feat"><div class="feat-icon"><i class="bi bi-bar-chart-fill"></i></div>
          <div><strong>Analytics dashboard</strong><span>Track sales, revenue and attendance</span></div></div>

        <div class="feat"><div class="feat-icon"><i class="bi bi-megaphone-fill"></i></div>
          <div><strong>Announcements</strong><span>Message all ticket holders instantly</span></div></div>

        <div class="feat"><div class="feat-icon"><i class="bi bi-tag-fill"></i></div>
          <div><strong>Promo codes</strong><span>Drive ticket sales with smart discounts</span></div></div>

        <div class="stats-strip">
          <div><span class="stat-val">500+</span><span class="stat-lbl">Events hosted</span></div>
          <div><span class="stat-val">10k+</span><span class="stat-lbl">Tickets sold</span></div>
          <div><span class="stat-val">4</span><span class="stat-lbl">User roles</span></div>
        </div>
      </div>
    </div>

    <!-- ── Right: Form ── -->
    <div class="col-lg-7">
      <div class="auth-right">
        <div class="form-card">

          <!-- Mobile logo -->
          <div class="d-flex d-lg-none align-items-center gap-2 mb-4">
            <div style="width:36px;height:36px;background:var(--brand);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;">
              <i class="bi bi-calendar-event-fill"></i></div>
            <span style="font-weight:700;font-size:1.1rem;">Event Platform</span>
          </div>

          <h2>Welcome back</h2>
          <p class="sub">Sign in to continue to your dashboard</p>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert">
              <i class="bi bi-exclamation-circle-fill fs-6"></i>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
              <i class="bi bi-check-circle-fill fs-6"></i>
              <span><?= htmlspecialchars($success) ?></span>
            </div>
          <?php endif; ?>

          <form method="POST" action="/WebtechProject/public/login-submit" id="loginForm" novalidate>

            <div class="mb-3">
              <label class="form-label" for="email">Email address</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="you@example.com" required autocomplete="email"
                       oninput="validateEmail(this)" />
              </div>
              <div class="invalid-feedback" id="emailErr" style="display:none; font-size:.8rem; color:#dc2626; margin-top:.3rem;">
                Please enter a valid email address.
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="password">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Enter your password" required autocomplete="current-password" />
                <button class="btn btn-outline-secondary" type="button" id="togglePwd" tabindex="-1"
                        title="Show / hide password">
                  <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn btn-brand w-100" id="submitBtn">
              Sign in &nbsp;<i class="bi bi-arrow-right"></i>
            </button>

          </form>

          <div class="divider">or</div>

          <p class="footer-link">
            New organiser? <a href="/WebtechProject/public/register">Create an account</a>
          </p>

        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle password visibility
  document.getElementById('togglePwd').addEventListener('click', function () {
    const p = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    p.type = p.type === 'password' ? 'text' : 'password';
    i.className = p.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  });

  // Real-time email validation
  function validateEmail(input) {
    const err = document.getElementById('emailErr');
    const ok  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    input.style.borderColor = input.value.length > 0 ? (ok ? '#0F6E56' : '#dc2626') : '#e5e7eb';
    err.style.display = (!ok && input.value.length > 0) ? 'block' : 'none';
  }

  // Loading state on submit
  document.getElementById('loginForm').addEventListener('submit', function (e) {
    const email = document.getElementById('email').value.trim();
    const pass  = document.getElementById('password').value.trim();
    if (!email || !pass) { e.preventDefault(); return; }
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Signing in...';
    btn.disabled = true;
  });
</script>
</body>
</html>