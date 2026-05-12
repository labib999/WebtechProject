<?php $isRegister = ($panel ?? 'login') === 'register'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $isRegister ? 'Create Account' : 'Sign In' ?> — Event Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <style>
    :root { --brand:#0F6E56; --brand-dark:#063D30; --brand-mid:#1a8a6e; }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }

body {
      min-height:100vh; display:flex; align-items:center; justify-content:center;
      font-family:'Segoe UI',system-ui,sans-serif; padding:1rem;
      background: linear-gradient(135deg, #d4ede6 0%, #cfe4f0 50%, #dcd8f0 100%);
      position:relative; overflow:hidden;
    }
    body::before {
      content:''; position:fixed;
      width:700px; height:700px; border-radius:50%;
      background: radial-gradient(circle, rgba(15,110,86,0.18) 0%, transparent 65%);
      top:-250px; left:-200px;
      animation:bgFloat 13s ease-in-out infinite;
      pointer-events:none; z-index:0;
    }
    body::after {
      content:''; position:fixed;
      width:550px; height:550px; border-radius:50%;
      background: radial-gradient(circle, rgba(6,61,48,0.13) 0%, transparent 65%);
      bottom:-160px; right:-160px;
      animation:bgFloat 17s ease-in-out infinite reverse;
      pointer-events:none; z-index:0;
    }
    @keyframes bgFloat {
      0%,100%{ transform:translate(0,0) scale(1); }
      33%    { transform:translate(28px,-18px) scale(1.04); }
      66%    { transform:translate(-18px,14px) scale(0.97); }
    }
    .auth-container { position:relative; z-index:1; }

    /* ── Main card ── */
    .auth-container {
      background:#fff; border-radius:24px;
      box-shadow:0 32px 80px rgba(0,0,0,0.13),0 8px 24px rgba(0,0,0,0.07);
      position:relative; overflow:hidden;
      width:100%; max-width:920px; min-height:570px;
    }

    /* ── Form panels ── */
    .form-container {
      position:absolute; top:0; height:100%;
      transition:all 0.65s cubic-bezier(0.4,0,0.2,1);
      display:flex; flex-direction:column; justify-content:center;
      padding:2.8rem 3rem; overflow-y:auto;
    }
    .sign-in-container { left:0; width:50%; z-index:2; }
    .sign-up-container { left:0; width:50%; opacity:0; z-index:1; }

    .auth-container.active .sign-in-container { transform:translateX(100%); opacity:0; }
    .auth-container.active .sign-up-container { transform:translateX(100%); opacity:1; z-index:5; }

    /* ── Sliding overlay ── */
    .overlay-container {
      position:absolute; top:0; left:50%;
      width:50%; height:100%; overflow:hidden;
      transition:transform 0.65s cubic-bezier(0.4,0,0.2,1);
      z-index:100;
    }
    .auth-container.active .overlay-container { transform:translateX(-100%); }

    .overlay {
      background:linear-gradient(145deg,var(--brand-dark) 0%,var(--brand-mid) 55%,var(--brand) 100%);
      color:#fff; position:relative; left:-100%;
      height:100%; width:200%;
      transition:transform 0.65s cubic-bezier(0.4,0,0.2,1);
      overflow:hidden;
    }
    .auth-container.active .overlay { transform:translateX(50%); }

    /* Floating circles on overlay */
    .overlay::before,.overlay::after {
      content:''; position:absolute; border-radius:50%; pointer-events:none;
    }
    .overlay::before {
      width:460px; height:460px; top:-160px; right:-120px;
      background:rgba(255,255,255,0.06);
      animation:floatUp 8s ease-in-out infinite;
    }
    .overlay::after {
      width:300px; height:300px; bottom:-90px; left:5%;
      background:rgba(255,255,255,0.04);
      animation:floatUp 11s ease-in-out infinite reverse;
    }
    @keyframes floatUp {
      0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-22px) scale(1.05)}
    }

    .overlay-panel {
      position:absolute; top:0; height:100%; width:50%;
      display:flex; flex-direction:column; align-items:center;
      justify-content:center; padding:2.5rem; text-align:center;
      transition:transform 0.65s cubic-bezier(0.4,0,0.2,1); z-index:1;
    }
    .overlay-left  { left:0;  transform:translateX(-20%); }
    .overlay-right { right:0; }
    .auth-container.active .overlay-left  { transform:translateX(0); }
    .auth-container.active .overlay-right { transform:translateX(20%); }

    .logo-icon {
      width:56px; height:56px; background:rgba(255,255,255,0.18);
      border-radius:16px; display:flex; align-items:center;
      justify-content:center; font-size:1.5rem; margin-bottom:1.5rem;
    }
    .overlay-panel h2 { font-size:1.7rem; font-weight:700; margin-bottom:.75rem; }
    .overlay-panel p  { font-size:.9rem; opacity:.8; line-height:1.7; margin-bottom:2rem; max-width:220px; }

    .btn-ghost {
      border:2px solid rgba(255,255,255,0.8); background:transparent;
      color:#fff; padding:.6rem 2.5rem; border-radius:30px;
      font-weight:600; font-size:.9rem; cursor:pointer; letter-spacing:.4px;
      transition:background .2s, transform .15s;
    }
    .btn-ghost:hover { background:rgba(255,255,255,0.15); transform:scale(1.04); }

    /* ── Form styles ── */
    .form-container h2 { font-size:1.6rem; font-weight:700; color:#111; margin-bottom:.2rem; }
    .form-container .sub { color:#6b7280; font-size:.88rem; margin-bottom:1.6rem; }

    .form-label {
      font-size:.76rem; font-weight:700; color:#374151;
      letter-spacing:.5px; text-transform:uppercase; display:block; margin-bottom:.35rem;
    }
    .input-group-text {
      background:#f8fafc; border-color:#e5e7eb;
      color:#9ca3af; transition:all .2s;
    }
    .form-control {
      border-color:#e5e7eb; font-size:.9rem;
      transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus {
      border-color:var(--brand);
      box-shadow:0 0 0 3px rgba(15,110,86,.13);
      outline:none;
    }
    .input-group:focus-within .input-group-text { border-color:var(--brand); color:var(--brand); }

    .btn-brand {
      background:var(--brand); color:#fff; border:none;
      padding:.7rem; font-size:.93rem; font-weight:600;
      border-radius:12px; width:100%; cursor:pointer; letter-spacing:.2px;
      box-shadow:0 4px 15px rgba(15,110,86,.3);
      transition:background .2s, transform .12s, box-shadow .2s;
    }
    .btn-brand:hover { background:var(--brand-dark); box-shadow:0 6px 22px rgba(15,110,86,.4); transform:translateY(-1px); }
    .btn-brand:active { transform:translateY(0); }
    .btn-brand:disabled { opacity:.75; transform:none; cursor:default; }

    .alert { font-size:.84rem; border-radius:10px; padding:.65rem 1rem; margin-bottom:1rem; }
    .alert-danger  { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
    .alert-success { background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46; }
    .field-err { display:none; font-size:.76rem; color:#dc2626; margin-top:.25rem; }
  </style>
</head>
<body>

<div class="auth-container <?= $isRegister ? 'active' : '' ?>" id="authContainer">

  <!-- ══ Sign In Form ══ -->
  <div class="form-container sign-in-container">
    <h2>Sign In</h2>
    <p class="sub">Continue to your dashboard</p>

    <?php if (!$isRegister && !empty($error)): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        <span><?= htmlspecialchars($success) ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="/WebtechProject/public/login-submit" id="loginForm" novalidate>
      <div class="mb-3">
        <label class="form-label">Email address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control"
                 placeholder="you@example.com" required autocomplete="email"
                 oninput="validateEmail(this,'loginEmailErr')"/>
        </div>
        <div class="field-err" id="loginEmailErr">Please enter a valid email address.</div>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" id="loginPwd" name="password" class="form-control"
                 placeholder="Enter your password" required/>
          <button class="btn btn-outline-secondary" type="button"
                  onclick="togglePwd('loginPwd','loginEye')" tabindex="-1">
            <i id="loginEye" class="bi bi-eye"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn-brand" id="loginBtn">
        Sign In &nbsp;<i class="bi bi-arrow-right-circle"></i>
      </button>
    </form>
  </div>

  <!-- ══ Sign Up Form ══ -->
  <div class="form-container sign-up-container">
    <h2>Create Account</h2>
    <p class="sub">Register your organiser account</p>

    <?php if ($isRegister && !empty($error)): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="/WebtechProject/public/register-submit" id="registerForm" novalidate>
      <div class="row g-2 mb-2">
        <div class="col-6">
          <label class="form-label">Full name</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="name" class="form-control" placeholder="Your name" required/>
          </div>
        </div>
        <div class="col-6">
          <label class="form-label">Phone</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" name="phone" class="form-control" placeholder="+880..."/>
          </div>
        </div>
      </div>

      <div class="mb-2">
        <label class="form-label">Email address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control"
                 placeholder="you@example.com" required
                 oninput="validateEmail(this,'regEmailErr')"/>
        </div>
        <div class="field-err" id="regEmailErr">Please enter a valid email address.</div>
      </div>

      <div class="mb-2">
        <label class="form-label">Organisation name</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-building"></i></span>
          <input type="text" name="org_name" class="form-control"
                 placeholder="e.g. TechEvents BD" required/>
        </div>
      </div>

      <div class="row g-2 mb-3">
        <div class="col-6">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" id="regPwd" name="password" class="form-control"
                   placeholder="Min 8 chars" required/>
            <button class="btn btn-outline-secondary" type="button"
                    onclick="togglePwd('regPwd','regEye')" tabindex="-1">
              <i id="regEye" class="bi bi-eye"></i>
            </button>
          </div>
        </div>
        <div class="col-6">
          <label class="form-label">Confirm</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" id="regConfirm" name="confirm_password" class="form-control"
                   placeholder="Repeat" required oninput="checkMatch()"/>
          </div>
          <div class="field-err" id="matchErr">Passwords do not match.</div>
        </div>
      </div>

      <button type="submit" class="btn-brand" id="registerBtn">
        Create Account &nbsp;<i class="bi bi-person-plus"></i>
      </button>
    </form>
  </div>

  <!-- ══ Sliding Overlay ══ -->
  <div class="overlay-container">
    <div class="overlay">

      <!-- Left panel — shown in register mode: go back to login -->
      <div class="overlay-panel overlay-left">
        <div class="logo-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <h2>Welcome Back!</h2>
        <p>Already have an account? Sign in and continue where you left off.</p>
        <button class="btn-ghost" id="signInBtn">Sign In</button>
      </div>

      <!-- Right panel — shown in login mode: go to register -->
      <div class="overlay-panel overlay-right">
        <div class="logo-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <h2>New Organiser?</h2>
        <p>Create an account to start managing events, selling tickets and growing your audience.</p>
        <button class="btn-ghost" id="signUpBtn">Create Account</button>
      </div>

    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const container = document.getElementById('authContainer');

  document.getElementById('signUpBtn').addEventListener('click', () =>
    container.classList.add('active'));

  document.getElementById('signInBtn').addEventListener('click', () =>
    container.classList.remove('active'));

  function togglePwd(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    f.type = f.type === 'password' ? 'text' : 'password';
    i.className = f.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }

  function validateEmail(input, errId) {
    const ok  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    const err = document.getElementById(errId);
    input.style.borderColor = input.value ? (ok ? '#0F6E56' : '#dc2626') : '#e5e7eb';
    err.style.display = (!ok && input.value) ? 'block' : 'none';
  }

  function checkMatch() {
    const p1 = document.getElementById('regPwd').value;
    const p2 = document.getElementById('regConfirm');
    const ok = p1 === p2.value;
    p2.style.borderColor = p2.value ? (ok ? '#0F6E56' : '#dc2626') : '#e5e7eb';
    document.getElementById('matchErr').style.display = (!ok && p2.value) ? 'block' : 'none';
  }

  document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
    btn.disabled = true;
  });

  document.getElementById('registerForm').addEventListener('submit', function() {
    const btn = document.getElementById('registerBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';
    btn.disabled = true;
  });
</script>
</body>
</html>