
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMTS — Event Management & Ticketing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/webtechproject/WebtechProject/public/css/main.css">
</head>
<body>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?> m-3 d-flex align-items-center gap-2"
         style="border-radius:10px; font-size:14px;" id="flashMessage">
        <i class="bi bi-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill' ?>"></i>
        <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
    <script>
        setTimeout(function() {
            const flash = document.getElementById('flashMessage');
            if (flash) {
                flash.style.transition = 'opacity 0.5s';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 3000);
    </script>
<?php endif; ?>