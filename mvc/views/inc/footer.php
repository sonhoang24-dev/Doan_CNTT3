<!-- Footer -->
<footer id="site-footer" class="bg-light border-top mt-4" role="contentinfo">
  <div class="container py-4">
    <div class="row align-items-center text-center text-md-start">
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="text-muted small">
          &copy; <span data-year-copy></span> 
          <a class="fw-bold text-decoration-none" href="./" target="_blank" rel="noopener noreferrer">DHT Ontest</a>
        </div>
      </div>

  <div class="col-md-4 mb-3 mb-md-0 text-center">
  <div class="fw-semibold text-muted small">
    <i class="fa fa-graduation-cap text-primary fa-sm me-2"></i>
    Website tạo và quản lý bài thi cá nhân
  </div>
</div>

      <!-- Right -->
      <div class="col-md-4 text-md-end">
        <div class="text-muted small">
          Crafted with <i class="fa fa-heart text-danger"></i> by 
          <a class="fw-bold text-decoration-none" href="./" target="_blank" rel="noopener noreferrer">DHT Ontest</a>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- END Footer -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Set current year in footer
    document.querySelector('[data-year-copy]').textContent = new Date().getFullYear();
  });
</script>