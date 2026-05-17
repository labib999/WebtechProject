</div><!-- end page-body -->
</div><!-- end main-wrap -->


<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Add loading state to all POST form submit buttons
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('form[method="POST"]').forEach(function (form) {
    form.addEventListener('submit', function () {
      const btn = form.querySelector('button[type="submit"]');
      if (btn && !btn.dataset.noload) {
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Please wait...';
        // Re-enable after 5s in case of validation error
        setTimeout(() => {
          btn.disabled = false;
          btn.innerHTML = original;
        }, 5000);
      }
    });
  });
});
</script>
  <!-- Back to top button -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
  style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;z-index:999;
         width:40px;height:40px;border-radius:50%;border:none;
         background:#0F6E56;color:#fff;font-size:1rem;cursor:pointer;
         box-shadow:0 4px 14px rgba(15,110,86,.35);transition:all .2s;"
  title="Back to top">
  <i class="bi bi-arrow-up"></i>
</button>

<script>
window.addEventListener('scroll', function() {
  const btn = document.getElementById('backToTop');
  if (window.scrollY > 300) {
    btn.style.display = 'block';
  } else {
    btn.style.display = 'none';
  }
});
</script>
</body>
</html>