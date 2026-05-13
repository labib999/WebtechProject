<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>404 — Page Not Found</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Inter',system-ui,sans-serif; background:#f8fffe;
      display:flex; align-items:center; justify-content:center;
      min-height:100vh; padding:2rem; }
    .wrap { text-align:center; max-width:420px; }
    .code { font-size:7rem; font-weight:900; color:#E1F5EE; line-height:1;
      text-shadow:0 2px 0 #c6ece0; letter-spacing:-4px; }
    .icon { font-size:3rem; color:#0F6E56; margin:-1rem 0 1rem; display:block; }
    h1 { font-size:1.4rem; font-weight:800; color:#111; margin-bottom:.5rem; }
    p  { font-size:.9rem; color:#9ca3af; margin-bottom:2rem; line-height:1.6; }
    .btn-home { display:inline-flex; align-items:center; gap:.5rem;
      padding:.7rem 1.5rem; border-radius:10px; background:#0F6E56;
      color:#fff; text-decoration:none; font-weight:600; font-size:.9rem;
      box-shadow:0 4px 14px rgba(15,110,86,.25); transition:all .15s; }
    .btn-home:hover { background:#063D30; color:#fff; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="code">404</div>
    <i class="bi bi-compass icon"></i>
    <h1>Page Not Found</h1>
    <p>The page you are looking for does not exist or has been moved.</p>
    <a href="/WebtechProject/public/organiser/dashboard" class="btn-home">
      <i class="bi bi-house-fill"></i> Back to Dashboard
    </a>
  </div>
</body>
</html>