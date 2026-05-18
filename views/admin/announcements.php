<?php $activePage = 'announcements'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <div class="mb-4">
            <h1 class="page-title">Announcements</h1>
            <p class="text-muted">Post platform-wide announcements displayed to all users on login.</p>
        </div>

        <div class="row g-4">

            <!-- POST ANNOUNCEMENT FORM -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-megaphone me-2"></i>New Announcement
                    </div>
                    <div class="card-body">
                        <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=post_announcement">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Scheduled Maintenance" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea name="body" class="form-control" rows="5" placeholder="Write your announcement here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-1"></i> Post Announcement
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PAST ANNOUNCEMENTS -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-clock-history me-2"></i>Past Announcements</span>
                        <span class="badge bg-secondary"><?= count($announcements) ?> Total</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($announcements)): ?>
                            <div class="p-4 text-muted">No announcements posted yet.</div>
                        <?php else: ?>
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Posted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($announcements as $announcement): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($announcement['title']) ?></td>
                                    <td class="text-muted" style="max-width:300px;">
                                        <?= htmlspecialchars($announcement['body']) ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($announcement['sent_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>