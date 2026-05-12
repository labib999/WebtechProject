<?php
$pageTitle  = 'Discount Codes';
$activePage = 'discounts';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .form-section { background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 14px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); margin-bottom:1.25rem; }
  .form-section-title { font-size:.82rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; color:#9ca3af; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; margin-bottom:.35rem; }
  .form-control,.form-select { border-color:#e5e7eb; font-size:.9rem; }
  .form-control:focus,.form-select:focus { border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12); }

  .code-card { background:#fff; border:1px solid #e5e7eb; border-radius:13px;
    padding:1.1rem 1.25rem; transition:box-shadow .15s; }
  .code-card:hover { box-shadow:0 4px 18px rgba(0,0,0,.08); }
  .code-text { font-family:monospace; font-size:1.1rem; font-weight:800; color:#111;
    letter-spacing:2px; margin-bottom:.25rem; }
  .code-pct  { font-size:1.4rem; font-weight:800; color:#0F6E56; }
  .code-meta { font-size:.75rem; color:#9ca3af; margin-top:.4rem; }
  .usage-bar { height:4px; background:#f3f4f6; border-radius:2px; margin-top:.4rem; overflow:hidden; }
  .usage-fill { height:100%; background:#0F6E56; border-radius:2px; }
  .inactive .code-text { color:#9ca3af; }
  .inactive .code-pct  { color:#d1d5db; }
  .btn-add { padding:.65rem 1.25rem; border-radius:10px; border:none; background:#0F6E56;
    color:#fff; font-size:.88rem; font-weight:600; cursor:pointer;
    box-shadow:0 4px 14px rgba(15,110,86,.25); transition:all .15s; }
  .btn-add:hover { background:#063D30; }
  .btn-tog { padding:.28rem .75rem; border-radius:7px; font-size:.75rem; font-weight:600;
    border:1.5px solid; cursor:pointer; background:none; transition:all .15s; }
  .btn-tog.on  { color:#991b1b; border-color:#fecaca; }
  .btn-tog.on:hover  { background:#FEF2F2; }
  .btn-tog.off { color:#065f46; border-color:#6ee7b7; }
  .btn-tog.off:hover { background:#ECFDF5; }
  [data-bs-theme="dark"] .form-section,
  [data-bs-theme="dark"] .code-card  { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select{ background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .code-text  { color:#f3f4f6; }
  [data-bs-theme="dark"] .usage-bar  { background:#374151; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Discount Codes</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;"><?= count($codes) ?> code<?= count($codes)!=1?'s':'' ?> total</p>
  </div>
</div>

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

<!-- Existing codes -->
<?php if (!empty($codes)): ?>
<div class="row g-3 mb-4">
  <?php foreach ($codes as $c):
    $usedPct = $c['max_uses'] ? min(100, round($c['uses_count']/$c['max_uses']*100)) : 0;
    $isActive = (bool)$c['is_active'];
    $expired  = !empty($c['valid_until']) && strtotime($c['valid_until']) < time();
  ?>
  <div class="col-md-4">
    <div class="code-card <?= !$isActive ? 'inactive' : '' ?>">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="code-text"><?= htmlspecialchars($c['code']) ?></div>
          <div class="code-pct"><?= $c['discount_pct'] ?>% off</div>
        </div>
        <div class="d-flex flex-column align-items-end gap-1">
          <?php if ($expired): ?>
            <span style="background:#FEF2F2;color:#991b1b;font-size:.68rem;padding:2px 7px;border-radius:10px;font-weight:600;">Expired</span>
          <?php elseif ($isActive): ?>
            <span style="background:#ECFDF5;color:#065f46;font-size:.68rem;padding:2px 7px;border-radius:10px;font-weight:600;">Active</span>
          <?php else: ?>
            <span style="background:#F3F4F6;color:#6b7280;font-size:.68rem;padding:2px 7px;border-radius:10px;font-weight:600;">Inactive</span>
          <?php endif; ?>
          <form method="POST" action="/WebtechProject/public/organiser/discounts/toggle" style="margin:0;">
            <input type="hidden" name="code_id" value="<?= $c['id'] ?>"/>
            <button type="submit" class="btn-tog <?= $isActive?'on':'off' ?>">
              <?= $isActive ? 'Deactivate' : 'Activate' ?>
            </button>
          </form>
        </div>
      </div>

      <div style="font-size:.78rem;color:#9ca3af;margin-top:.5rem;">
        <?= htmlspecialchars(mb_strimwidth($c['event_title'],0,35,'…')) ?>
      </div>

      <div class="code-meta d-flex gap-3">
        <span><i class="bi bi-arrow-repeat me-1"></i>
          <?= $c['uses_count'] ?> used<?= $c['max_uses'] ? ' / '.$c['max_uses'].' max' : '' ?>
        </span>
        <?php if (!empty($c['valid_until'])): ?>
          <span><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($c['valid_until'])) ?></span>
        <?php endif; ?>
      </div>

      <?php if ($c['max_uses']): ?>
        <div class="usage-bar">
          <div class="usage-fill" style="width:<?= $usedPct ?>%;
            <?= $usedPct >= 100 ? 'background:#ef4444;' : '' ?>"></div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Add new code -->
<div class="form-section">
  <div class="form-section-title"><i class="bi bi-plus-circle"></i> Create New Discount Code</div>
  <form method="POST" action="/WebtechProject/public/organiser/discounts/store">
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <label class="form-label">Code <span style="color:#ef4444;">*</span></label>
        <input type="text" name="code" class="form-control" placeholder="e.g. SAVE20"
               style="font-family:monospace;text-transform:uppercase;" required
               oninput="this.value=this.value.toUpperCase()"/>
      </div>
      <div class="col-md-2">
        <label class="form-label">Discount % <span style="color:#ef4444;">*</span></label>
        <input type="number" name="discount_pct" class="form-control"
               min="1" max="100" step="0.01" placeholder="e.g. 20" required/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Event <span style="color:#ef4444;">*</span></label>
        <select name="event_id" class="form-select" required>
          <option value="">— Select event —</option>
          <?php foreach ($myEvents as $ev): ?>
            <option value="<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Max uses <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
        <input type="number" name="max_uses" class="form-control" min="1" placeholder="Unlimited"/>
      </div>
      <div class="col-md-2">
        <label class="form-label">Expires <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
        <input type="datetime-local" name="valid_until" class="form-control"/>
      </div>
    </div>
    <button type="submit" class="btn-add">
      <i class="bi bi-plus-circle-fill me-1"></i> Create Code
    </button>
  </form>
</div>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>