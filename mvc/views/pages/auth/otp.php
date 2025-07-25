<!-- Page Content -->
<div class="bg-image position-relative" style="background-image: url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg'); background-size: cover; background-position: center; min-height: 100vh;">
  <div style="position: absolute; inset: 0; background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); z-index: 0;"></div>

  <div class="row g-0 justify-content-center align-items-center min-vh-100 position-relative" style="z-index: 1;">
    <div class="hero-static col-sm-10 col-md-8 col-xl-4 d-flex align-items-center p-2 px-sm-0">
      <div class="block bg-white shadow-lg rounded-4 w-100 mb-0 overflow-hidden p-4 p-lg-5">
        <!-- Logo & tiêu đề -->
        <div class="text-center mb-4">
          <pclass= class="fw-bold fs-2 text-decoration-none">
            <span class="bg-dark text-white px-3 py-1 rounded-3 shadow-sm">DHT</span>
            <span class="text-primary fs-2">OnTest</span>
          </pclass=>
          <p class="text-uppercase fw-bold fs-sm text-dark mt-3 mb-1">Xác minh OTP</p>
          <p class="fw-semibold fs-sm text-muted">Chúng tôi đã gửi mã OTP đến email của bạn</p>
        </div>

        <!-- Form nhập OTP -->
        <form id="formOpt">
          <div class="mb-4">
            <div class="input-group input-group-lg">
              <input type="text" class="form-control form-control-lg rounded-3 border border-secondary" id="txtOpt" name="txtOpt" placeholder="Nhập mã OTP" required>
            </div>
          </div>

          <!-- Nút xác minh -->
          <div class="text-center mb-2">
            <button type="submit" id="opt" class="btn btn-lg w-100 text-white fw-bold rounded-3 shadow" style="background: linear-gradient(to right, #00c6ff, #0072ff);">
              <i class="fa fa-key me-2"></i> Xác minh
            </button>
          </div>
        </form>

         <input type="hidden" id="storedEmail" name="storedEmail">
      </div>
    </div>
  </div>
</div>
