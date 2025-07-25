Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation"),
        jQuery(".js-validation-reminder").validate({
          rules: { "reminder-credential": { required: !0, emailWithDot: !0 } },
          messages: {
            "reminder-credential": {
              required: "Vui lòng nhập địa chỉ email !",
              emailWithDot: "Địa chỉ email phải đúng định dạng!",
            },
          },
        });
      jQuery("#formOpt").validate({
        rules: {
          txtOpt: {
            required: true,
            digits: true,
            minlength: 6,
            maxlength: 6,
          },
        },
        messages: {
          txtOpt: {
            required: "Vui lòng nhập mã OPT!",
            digits: "Mã OTP chỉ bao gồm chữ số!",
            minlength: "Mã OTP phải có ít nhất 6 chữ số!",
            maxlength: "Mã OTP chỉ được phép có tối đa 6 chữ số!",
          },
        },
      });
      jQuery("#changepass").validate({
        rules: {
          passwordNew: {
            required: true,
            minlength: 6,
          },
          comfirm: {
            required: true,
            equalTo: "#passwordNew",
          },
        },
        messages: {
          passwordNew: {
            required: "Vui lòng không để trống",
            minlength: "Mật khẩu ít nhất 6 ký tự",
          },
          comfirm: {
            required: "Vui lòng không để trống",
            equalTo: "Mật khẩu không trùng khớp",
          },
        },
      });
    }
    static init() {
      this.initValidation();
    }
  }.init()
);

$("#btnRecover").click(function (e) {
  e.preventDefault();
  if ($(".js-validation-reminder").valid()) {
    let mail = $("#reminder-credential").val();
    $.ajax({
      type: "post",
      url: "./auth/checkEmail",
      data: {
        email: mail,
      },
      success: function (response) {
        let data = JSON.parse(response);
        console.log(data);
        if (response == "null") {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: `Tài khoản không tồn tại!`,
          });
        } else {
          $.ajax({
            type: "post",
            url: "./auth/sendOptAuth",
            data: {
              email: mail,
            },
            success: function (response) {
              location.href = `./auth/otp`;
            },
          });
        }
      },
    });
  }
});

$("#opt").click(function (e) {
  e.preventDefault();

  if ($("#formOpt").valid()) {
    let opt = $("#txtOpt").val();

    $.ajax({
      type: "post",
      url: "./auth/checkOpt",
      data: {
        otp: opt,
      },
      dataType: "json", // Quan trọng: ép kiểu dữ liệu trả về là JSON
      success: function (response) {
        console.log("Response:", response);

        if (response === false || response == 0) {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: `Mã OTP không đúng`,
          });
        } else {
          // Trường hợp đúng, chuyển hướng sang giao diện đổi mật khẩu
          window.location.href = "./auth/changepass";
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", error);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-exclamation-circle me-1",
          message: `Lỗi hệ thống, vui lòng thử lại.`,
        });
      },
    });
  }
});

$("#btnChange").click(function (e) {
  e.preventDefault();
  if ($("#changepass").valid()) {
    let passwordNew = $("#passwordNew").val();
    $.ajax({
      type: "POST",
      url: "./auth/changePassword",
      data: {
        password: passwordNew,
      },
      success: function (response) {
        const data = JSON.parse(response);
        if (data.status === "success") {
          Dashmix.helpers("jq-notify", {
            type: "success",
            icon: "fa fa-check me-1",
            message: data.message,
          });
          setTimeout(() => {
            location.href = "./auth/signin";
          }, 2000);
        } else {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: data.message,
          });
        }
      },
      error: function () {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: "Đã xảy ra lỗi kết nối đến máy chủ",
        });
      },
    });
  }
});
