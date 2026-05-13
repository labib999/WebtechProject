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
</body>
</html>