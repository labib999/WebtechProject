<?php
$pageTitle  = 'Ticket Tiers';
$activePage = 'events';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .form-section { background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); margin-bottom:1.25rem; }
  .form-section-title { font-size:.82rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; color:#9ca3af; margin-bottom:1rem;
    display:flex; align-items:center; gap:.5rem; }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; letter-spacing:.3px; margin-bottom:.4rem; }
  .form-control,.form-select { border-color:#e5e7eb; font-size:.9rem;
    transition:border-color .2s,box-shadow .2s; }
  .form-control:focus,.form-select:focus {
    border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12); }

  .tier-card { background:#fff; border:1px solid #e5e7eb; border-radius:13px;
    padding:1.1rem 1.25rem; transition:box-shadow .15s; }
  .tier-card:hover { box-shadow:0 4px 18px rgba(0,0,0,0.08); }
  .tier-name  { font-size:.95rem; font-weight:700; color:#111; margin-bottom:.15rem; }
  .tier-price { font-size:1.3rem; font-weight:800; color:#0F6E56; }
  .tier-seats { font-size:.78rem; color:#9ca3af; margin-top:.5rem; }
  .seat-bar   { height:5px; background:#f3f4f6; border-radius:3px; margin-top:.3rem; overflow:hidden; }
  .seat-fill  { height:100%; background:#0F6E56; border-radius:3px;
    transition:width .5s ease; }
  .seat-fill.full { background:#ef4444; }
  .tier-meta  { font-size:.75rem; color:#9ca3af; margin-top:.5rem; }

  .btn-del { padding:.28rem .7rem; border-radius:7px; border:1.5px solid #fecaca;
    background:none; color:#991b1b; font-size:.75rem; font-weight:600;
    cursor:pointer; transition:all .15s; }
  .btn-del:hover { background:#FEF2F2; }
  .btn-del:disabled { opacity:.4; cursor:not-allowed; }

  .btn-add { padding:.65rem 1.25rem; border-radius:10px; border:none;
    background:#0F6E56; color:#fff; font-size:.88rem; font-weight:600;
    cursor:pointer; box-shadow:0 4px 14px rgba(15,110,86,.25);
    transition:all .15s; }
  .btn-add:hover { background:#063D30; }

  .sold-out { background:#FEF2F2; border-color:#fecaca; }
  .sold-out .tier-price { color:#991b1b; }

  [data-bs-theme="dark"] .form-section,
  [data-bs-theme="dark"] .tier-card   { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select { background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .form-label  { color:#d1d5db; }
  [data-bs-theme="dark"] .form-section-title { color:#6b7280; }
  [data-bs-theme="dark"] .tier-name   { color:#f3f4f6; }
  [data-bs-theme="dark"] .seat-bar    { background:#374151; }
</style>

<!-- Breadcrumb -->
<nav style="font-size:.8rem;color:#9ca3af;margin-bottom:1.25rem;">
  <a href="/WebtechProject/public/organiser/events" style="color:#0F6E56;text-decoration:none;">My Events</a>
  <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i>
  <span class="text-truncate"><?= htmlspecialchars($event['title']) ?></span>
  <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i>
  <span>Ticket Tiers</span>
</nav>

<!-- Event header -->
<div class="form-section mb-4" style="padding:1rem 1.25rem;">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h5 class="fw-bold mb-1" style="font-size:1rem;"><?= htmlspecialchars($event['title']) ?></h5>
      <span style="font-size:.78rem;color:#9ca3af;">
        <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($event['event_datetime'])) ?>
      </span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="st-badge st-<?= $event['status'] ?>" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;">
        <?= ucfirst($event['status']) ?>
      </span>
      <a href="/WebtechProject/public/organiser/events/edit?id=<?= $event['id'] ?>"
         style="font-size:.8rem;color:#0F6E56;text-decoration:none;">
        <i class="bi bi-pencil me-1"></i>Edit event
      </a>
    </div>
  </div>
</div>

<!-- Flash messages -->
<?php if (!empty($success)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#ECFDF5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($success) ?></span>
  </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span>
  </div>
<?php endif; ?>

<?php if ($event['status'] === 'draft' && empty($tiers)): ?>
  <div class="d-flex align-items-start gap-2 mb-4 p-3"
       style="background:#FFFBEB;border:1px solid #fcd34d;color:#92400e;border-radius:10px;font-size:.87rem;">
    <i class="bi bi-exclamation-triangle-fill mt-1" style="flex-shrink:0;"></i>
    <span>This event is a draft. Add at least one ticket tier, then go back to <strong>My Events</strong> and click <strong>Publish</strong>.</span>
  </div>
<?php endif; ?>

<!-- Existing tiers -->
<?php if (!empty($tiers)): ?>
  <h6 class="fw-semibold mb-3" style="font-size:.88rem;color:#374151;">
    <?= count($tiers) ?> tier<?= count($tiers) != 1 ? 's' : '' ?> — click × to remove
  </h6>
  <div class="row g-3 mb-4">
    <?php foreach ($tiers as $t):
      $soldPct  = $t['total_seats'] > 0 ? round(($t['sold_count'] / $t['total_seats']) * 100) : 0;
      $soldOut  = $t['sold_count'] >= $t['total_seats'];
      $canDel   = $t['bookings_count'] == 0;
    ?>
    <div class="col-md-4">
      <div class="tier-card <?= $soldOut ? 'sold-out' : '' ?> h-100">
        <div class="d-flex justify-content-between align-items-start">
          <div class="tier-name"><?= htmlspecialchars($t['name']) ?></div>
          <form method="POST" action="/WebtechProject/public/organiser/tiers/delete" style="margin:0;"
                onsubmit="return confirm('Delete this tier?')">
            <input type="hidden" name="tier_id"  value="<?= $t['id'] ?>"/>
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>"/>
            <button type="submit" class="btn-del" <?= !$canDel ? 'disabled title="Has active bookings"' : '' ?>>
              <i class="bi bi-x-lg"></i>
            </button>
          </form>
        </div>

        <div class="tier-price">
          <?= $t['price'] == 0 ? 'Free' : '$' . number_format($t['price'], 2) ?>
        </div>

        <?php if (!empty($t['description'])): ?>
          <p style="font-size:.78rem;color:#9ca3af;margin:.35rem 0 0;"><?= htmlspecialchars($t['description']) ?></p>
        <?php endif; ?>

        <div class="tier-seats">
          <?php if ($soldOut): ?>
            <span style="color:#ef4444;font-weight:700;">Sold out</span>
          <?php else: ?>
            <?= $t['sold_count'] ?> / <?= $t['total_seats'] ?> seats sold
          <?php endif; ?>
        </div>
        <div class="seat-bar">
          <div class="seat-fill <?= $soldOut ? 'full' : '' ?>" style="width:<?= $soldPct ?>%;"></div>
        </div>

        <?php if (!empty($t['sales_start']) || !empty($t['sales_end'])): ?>
          <div class="tier-meta">
            <?php if (!empty($t['sales_start'])): ?>
              <i class="bi bi-play-fill me-1"></i><?= date('d M Y', strtotime($t['sales_start'])) ?>
            <?php endif; ?>
            <?php if (!empty($t['sales_end'])): ?>
              &rarr; <?= date('d M Y', strtotime($t['sales_end'])) ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if (!$canDel): ?>
          <div class="tier-meta" style="color:#0F6E56;margin-top:.4rem;">
            <i class="bi bi-lock-fill me-1"></i><?= $t['bookings_count'] ?> booking<?= $t['bookings_count']!=1?'s':'' ?> — cannot delete
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- Add New Tier form -->
<div class="form-section">
  <div class="form-section-title"><i class="bi bi-plus-circle"></i> Add New Tier</div>

  <form method="POST" action="/WebtechProject/public/organiser/tiers/store">
    <input type="hidden" name="event_id" value="<?= $event['id'] ?>"/>

    <div class="row g-3 mb-3">
      <div class="col-md-5">
        <label class="form-label">Tier Name <span style="color:#ef4444;">*</span></label>
        <input type="text" name="name" class="form-control"
               placeholder="e.g. General, VIP, Early Bird" required/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Price ($) <span style="color:#ef4444;">*</span></label>
        <input type="number" name="price" class="form-control" min="0" step="0.01"
               placeholder="0.00 for free" required/>
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Seats <span style="color:#ef4444;">*</span></label>
        <input type="number" name="total_seats" class="form-control" min="1"
               placeholder="e.g. 100" required/>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Description <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
      <input type="text" name="description" class="form-control"
             placeholder="e.g. Includes lunch and front-row seating"/>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label">Sales Open <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
        <input type="datetime-local" name="sales_start" class="form-control"/>
      </div>
      <div class="col-md-6">
        <label class="form-label">Sales Close <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
        <input type="datetime-local" name="sales_end" class="form-control"/>
      </div>
    </div>

    <button type="submit" class="btn-add">
      <i class="bi bi-plus-circle-fill me-1"></i> Add Tier
    </button>
  </form>
</div>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>