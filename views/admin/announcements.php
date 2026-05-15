<?php $activePage = 'announcements'; ?>
<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
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
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="e.g. Scheduled Maintenance">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" placeholder="Write your announcement here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Audience</label>
                            <select class="form-select">
                                <option>All Users</option>
                                <option>Attendees Only</option>
                                <option>Organisers Only</option>
                                <option>Venue Managers Only</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-send me-1"></i> Post Announcement
                        </button>
                    </div>
                </div>
            </div>

            <!-- PAST ANNOUNCEMENTS -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-clock-history me-2"></i>Past Announcements</span>
                        <span class="badge bg-secondary">5 Total</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Audience</th>
                                    <th>Posted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">Scheduled Maintenance</td>
                                    <td class="text-muted" style="max-width:200px;">
                                        The platform will be down for maintenance on 20 May from 2am to 4am.
                                    </td>
                                    <td><span class="badge bg-secondary">All Users</span></td>
                                    <td>14 May 2026</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">New Feature: Reviews</td>
                                    <td class="text-muted" style="max-width:200px;">
                                        Attendees can now leave reviews after attending events.
                                    </td>
                                    <td><span class="badge bg-primary">Attendees</span></td>
                                    <td>10 May 2026</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Commission Rate Update</td>
                                    <td class="text-muted" style="max-width:200px;">
                                        Platform commission has been updated to 10% effective June 2026.
                                    </td>
                                    <td><span class="badge bg-warning text-dark">Organisers</span></td>
                                    <td>08 May 2026</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../../views/shared/footer.php'; ?>