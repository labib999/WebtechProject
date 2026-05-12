<?php
$pageTitle  = 'Reviews';
$activePage = 'reviews';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .filter-tab { padding:.4rem 1rem; border-radius:20px; font-size:.82rem; font-weight:600;
    text-decoration:none; border:1.5px solid #e5e7eb; color:#6b7280; transition:all .15s; }
  .filter-tab:hover { border-color:#0F6E56; color:#0F6E56; }
  .filter-tab.active { background:#0F6E56; color:#fff; border-color:#0F6E56; }

  .rev-card { background:#fff; border-radius:14px; padding:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
    border:1px solid rgba(0,0,0,.05); margin-bottom:.75rem; }
  .rev-name  { font-size:.9rem; font-weight:700; color:#111; }
  .rev-meta  { font-size:.75rem; color:#9ca3af; margin-top:.15rem; }
  .rev-text  { font-size:.88rem; color:#374151; margin-top:.6rem; line-height:1.6; }
  .rev-reply { margin-top:.75rem; padding:.75rem .9rem; background:#f0faf5;
    border-radius:9px; border-left:3px solid #0F6E56; font-size:.85rem; color:#374151; }
  .reply-form { margin-top:.75rem; display:none; }
  .reply-form.open { display:block; }
  .btn-reply { padding:.3rem .8rem; border-radius:8px; font-size:.78rem; font-weight:600;
    border:1.5px solid #0F6E56; color:#0F6E56; background:none; cursor:pointer; transition:all .15s; }
  .btn-reply:hover { background:#E1F5EE; }
  .btn-submit-reply { padding:.45rem 1rem; border-radius:8px; border:none;
    background:#0F6E56; color:#fff; font-size:.82rem; font-weight:600; cursor:pointer; }
  .stars-display { color:#fbbf24; font-size:.9rem; letter-spacing:1px; }

  [data-bs-theme="dark"] .rev-card  { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .rev-name  { color:#f3f4f6; }
  [data-bs-theme="dark"] .rev-text  { color:#d1d5db; }
  [data-bs-theme="dark"] .rev-reply { background:#1a3a2e; border-color:#0F6E56; color:#d1d5db; }
  [data-bs-theme="dark"] .reply-form textarea { background:#253245; border-color:#374151; color:#f3f4f6; }
</style>

<!-- Header + stats -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Reviews</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;"><?= $totalReviews ?> review<?= $totalReviews!=1?'s':'' ?></p>
  </div>
  <?php if ($avgRating): ?>
    <div style="background:#fff;border-radius:12px;padding:.65rem 1.1rem;border:1px solid rgba(0,0,0,.05);
                box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:.75rem;">
      <span style="font-size:1.6rem;font-weight:800;color:#111;"><?= $avgRating ?></span>
      <div>
        <div class="stars-display">
          <?php for ($i=1;$i<=5;$i++): ?>
            <i class="bi bi-star<?= $i<=$avgRating?'-fill':($i-$avgRating<1?'-half':'')?>"
               style="color:<?= $i<=$avgRating?'#fbbf24':'#e5e7eb';?>"></i>
          <?php endfor; ?>
        </div>
        <div style="font-size:.72rem;color:#9ca3af;"><?= $totalReviews ?> reviews</div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php if (!empty($success)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#ECFDF5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($success) ?></span>
  </div>
<?php endif; ?>

<!-- Rating filter -->
<div class="d-flex flex-wrap gap-2 mb-4">
  <a href="/WebtechProject/public/organiser/reviews" class="filter-tab <?= $filter==='all'?'active':'' ?>">All</a>
  <?php foreach ([5,4,3,2,1] as $star): ?>
    <a href="/WebtechProject/public/organiser/reviews?rating=<?= $star ?>"
       class="filter-tab <?= $filter==$star?'active':'' ?>">
      <i class="bi bi-star-fill" style="color:#fbbf24;font-size:.75rem;"></i> <?= $star ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- Reviews -->
<?php if (empty($reviews)): ?>
  <div style="text-align:center;padding:3rem;color:#9ca3af;">
    <i class="bi bi-star" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db;"></i>
    <p style="font-size:.88rem;">No reviews found.</p>
  </div>
<?php else: foreach ($reviews as $r): ?>
  <div class="rev-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <div>
        <div class="rev-name"><?= htmlspecialchars($r['attendee_name']) ?></div>
        <div class="rev-meta">
          <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($r['created_at'])) ?>
          &middot; <?= htmlspecialchars($r['event_title']) ?>
        </div>
      </div>
      <div class="stars-display">
        <?php for ($i=1;$i<=5;$i++): ?>
          <i class="bi bi-star<?= $i<=(int)$r['rating']?'-fill':'' ?>"
             style="color:<?= $i<=(int)$r['rating']?'#fbbf24':'#e5e7eb'; ?>"></i>
        <?php endfor; ?>
      </div>
    </div>

    <?php if (!empty($r['review_text'])): ?>
      <div class="rev-text">"<?= htmlspecialchars($r['review_text']) ?>"</div>
    <?php endif; ?>

    <?php if (!empty($r['organiser_reply'])): ?>
      <div class="rev-reply">
        <div style="font-size:.72rem;font-weight:700;color:#0F6E56;margin-bottom:.25rem;">
          <i class="bi bi-reply-fill me-1"></i>Your reply
        </div>
        <?= htmlspecialchars($r['organiser_reply']) ?>
      </div>
    <?php else: ?>
      <div style="margin-top:.6rem;">
        <button class="btn-reply" onclick="toggleReply(<?= $r['id'] ?>)">
          <i class="bi bi-reply me-1"></i>Reply to this review
        </button>
        <div class="reply-form" id="replyForm<?= $r['id'] ?>">
          <form method="POST" action="/WebtechProject/public/organiser/reviews/reply">
            <input type="hidden" name="review_id" value="<?= $r['id'] ?>"/>
            <textarea name="reply" class="form-control form-control-sm mb-2" rows="2"
                      placeholder="Write your public reply..." required></textarea>
            <button type="submit" class="btn-submit-reply">
              <i class="bi bi-send me-1"></i>Post Reply
            </button>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>
<?php endforeach; endif; ?>

<script>
function toggleReply(id) {
  document.getElementById('replyForm'+id).classList.toggle('open');
}
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>