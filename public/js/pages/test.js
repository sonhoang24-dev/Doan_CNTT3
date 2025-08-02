function dateIsValid(date) {
  return !Number.isNaN(new Date(date).getTime());
}

function showListTest(tests) {
  const format = new Intl.DateTimeFormat(navigator.language, {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });

  let html = ``;

  if (tests.length === 0) {
    html += `<div class="alert alert-info text-center py-3 mb-0">
               <i class="fa fa-info-circle me-1"></i> Không có đề thi nào.
             </div>`;
    $(".pagination").hide();
    $("#list-test").html(html);
    return;
  }

  html += `
    <div class="block block-rounded shadow-sm">
      <div class="block-header bg-body-light">
        <h3 class="block-title text-primary fw-bold">
          <i class="fa fa-list-alt me-2 text-warning"></i> Danh sách đề thi
        </h3>
      </div>
      <div class="block-content">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-light text-uppercase fw-semibold">
              <tr>
                <th>Tên đề thi</th>
                <th>Nhóm</th>
                <th>Học phần</th>
                <th>Năm học</th>
                <th>Học kỳ</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
  `;

  tests.forEach((test) => {
    const open = new Date(test.thoigianbatdau);
    const close = new Date(test.thoigianketthuc);

    const strOpenTime = dateIsValid(test.thoigianbatdau)
      ? format.format(open)
      : "N/A";
    const strCloseTime = dateIsValid(test.thoigianketthuc)
      ? format.format(close)
      : "N/A";

    // Trạng thái đề thi
    const now = Date.now();
    let state = { color: "secondary", text: "Chưa mở" };
    if (now >= +open && now <= +close) {
      state = { color: "primary", text: "Đang mở" };
    } else if (now > +close) {
      state = { color: "danger", text: "Đã đóng" };
    }

    const htmlTestState = `
      <span class="badge bg-${state.color} fw-normal px-3 py-2 rounded-pill">
        <i class="fa fa-circle me-1 small"></i>${state.text}
      </span>`;

    // Nhóm
    let groupDisplay = "Chưa gán nhóm";
    if (test.nhom) {
      if (Array.isArray(test.nhom)) {
        groupDisplay = [...new Set(test.nhom)].join(", ");
      } else if (typeof test.nhom === "string") {
        groupDisplay = [...new Set(test.nhom.split(", ").filter(Boolean))].join(
          ", "
        );
      }
    }

    // Học phần
    const hocPhan = {
      tenmonhoc: test.tenmonhoc || "Chưa xác định",
      namhoc: test.namhoc || "2025",
      hocky: test.hocky || "1",
    };

    html += `
      <tr>
        <td class="fw-semibold">
          <a href="./test/detail/${test.made}" class="text-dark link-fx">
            <i class="fa fa-file-alt text-muted me-1"></i> ${test.tende}
          </a>
        </td>
        <td>
          <span data-bs-toggle="tooltip" title="${groupDisplay}" style="cursor:pointer">
            <i class="fa fa-users text-muted me-1"></i>${groupDisplay}
          </span>
        </td>
        <td>${hocPhan.tenmonhoc}</td>
        <td>${hocPhan.namhoc}</td>
        <td>${hocPhan.hocky}</td>
        <td><i class="fa fa-calendar-alt me-1 text-muted"></i>${strOpenTime}</td>
        <td><i class="fa fa-calendar-check me-1 text-muted"></i>${strCloseTime}</td>
        <td>${htmlTestState}</td>
        <td>
          <a href="./test/detail/${test.made}" class="btn btn-sm btn-alt-success rounded-pill px-3 mb-1">
            <i class="fa fa-eye me-1"></i> Xem
          </a>
          <a href="./test/update/${test.made}" class="btn btn-sm btn-alt-primary rounded-pill px-3 mb-1" data-role="dethi" data-action="update">
            <i class="fa fa-edit me-1"></i> Sửa
          </a>
          <a href="javascript:void(0)" class="btn btn-sm btn-alt-danger rounded-pill px-3 mb-1 btn-delete" data-role="dethi" data-action="delete" data-id="${test.made}">
            <i class="fa fa-trash-alt me-1"></i> Xoá
          </a>
        </td>
      </tr>
    `;
  });

  html += `
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;

  $("#list-test").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
}

$(document).ready(function () {
  let e = Swal.mixin({
    buttonsStyling: !1,
    target: "#page-container",
    customClass: {
      confirmButton: "btn btn-success m-1",
      cancelButton: "btn btn-danger m-1",
      input: "form-control",
    },
  });

  // Fetch subjects for the dropdown
  function loadSubjects() {
    $.ajax({
      type: "GET",
      url: "./test/get_subjects",
      dataType: "json",
      success: function (response) {
        let subjectHtml =
          '<li><a class="dropdown-item filtered-by-subject" href="javascript:void(0)" data-value="">Tất cả môn học</a></li>';
        response.forEach((subject) => {
          subjectHtml += `<li><a class="dropdown-item filtered-by-subject" href="javascript:void(0)" data-value="${subject.mamonhoc}">${subject.tenmonhoc}</a></li>`;
        });
        $("#subject-filter-menu").html(subjectHtml);
      },
      error: function () {
        e.fire("Lỗi!", "Không thể tải danh sách môn học.", "error");
      },
    });
  }

  function loadGroups() {
    $.ajax({
      type: "GET",
      url: "./test/get_groups",
      dataType: "json",
      success: function (response) {
        let groupHtml =
          '<li><a class="dropdown-item filtered-by-group" href="javascript:void(0)" data-value="">Tất cả nhóm</a></li>';
        response.forEach((group) => {
          groupHtml += `<li><a class="dropdown-item filtered-by-group" href="javascript:void(0)" data-value="${group.manhom}">${group.tennhom}</a></li>`;
        });
        $("#group-filter-menu").html(groupHtml);
      },
      error: function () {
        e.fire("Lỗi!", "Không thể tải danh sách nhóm.", "error");
      },
    });
  }

  loadSubjects();
  loadGroups();

  $(document).on("click", ".filtered-by-subject", function (e) {
    e.preventDefault();
    $(".btn-filtered-by-subject").text($(this).text());
    const subject = $(this).data("value");
    if (subject) {
      mainPagePagination.option.subject = subject;
    } else {
      delete mainPagePagination.option.subject;
    }
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  $(document).on("click", ".filtered-by-group", function (e) {
    e.preventDefault();
    $(".btn-filtered-by-group").text($(this).text());
    const group = $(this).data("value");
    if (group) {
      mainPagePagination.option.group = group;
    } else {
      delete mainPagePagination.option.group;
    }
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Handle state filter click
  $(".filtered-by-state").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-state").text($(this).text());
    const state = $(this).data("value");
    if (state !== "3") {
      mainPagePagination.option.filter = state;
    } else {
      delete mainPagePagination.option.filter;
    }
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  // Handle delete button
  $(document).on("click", ".btn-delete", function () {
    let index = $(this).data("index");
    e.fire({
      title: "Are you sure?",
      text: "Bạn có chắc chắn muốn xoá đề thi?",
      icon: "warning",
      showCancelButton: !0,
      customClass: {
        confirmButton: "btn btn-danger m-1",
        cancelButton: "btn btn-secondary m-1",
      },
      confirmButtonText: "Vâng, tôi chắc chắn!",
      html: !1,
      preConfirm: (e) =>
        new Promise((e) => {
          setTimeout(() => {
            e();
          }, 50);
        }),
    }).then((t) => {
      if (t.value == true) {
        $.ajax({
          type: "post",
          url: "./test/delete",
          data: {
            made: $(this).data("id"),
          },
          dataType: "json",
          success: function (response) {
            if (response) {
              e.fire("Deleted!", "Xóa đề thi thành công!", "success");
              mainPagePagination.getPagination(
                mainPagePagination.option,
                mainPagePagination.valuePage.curPage
              );
            } else {
              e.fire("Lỗi!", "Xoá đề thi không thành công!", "error");
            }
          },
        });
      }
    });
  });

  $(".filtered-by-state").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-state").text($(this).text());
    const state = $(this).data("value");
    if (state !== "3") {
      mainPagePagination.option.filter = state;
    } else {
      delete mainPagePagination.option.filter;
    }

    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });
});

// Get current user ID
const container = document.querySelector(".content");
const currentUser = container.dataset.id;
delete container.dataset.id;

// Pagination
const mainPagePagination = new Pagination(null, null, showListTest);
mainPagePagination.option.controller = "test";
mainPagePagination.option.model = "DeThiModel";
mainPagePagination.option.id = currentUser;
mainPagePagination.option.custom.function = "getAllCreatedTest";
mainPagePagination.getPagination(
  mainPagePagination.option,
  mainPagePagination.valuePage.curPage
);
