// D:\XAMPP\htdocs\admin_web\publics\js\dashboard.js

/**
 * Dashboard Charts Initialization
 * Biểu đồ cho trang tổng quan dashboard
 */

document.addEventListener("DOMContentLoaded", function () {
  console.log("Dashboard script loaded");
  initializeRevenueChart();
  initializeGenreChart();
  setupAutoSubmit();
});

/**
 * Khởi tạo biểu đồ doanh thu
 */
function initializeRevenueChart() {
  const revenueCanvas = document.getElementById("revenueChart");
  if (!revenueCanvas) {
    console.log("Không tìm thấy element #revenueChart");
    return;
  }

  // Lấy dữ liệu từ data attributes
  const revenueLabels = revenueCanvas.dataset.labels
    ? JSON.parse(revenueCanvas.dataset.labels)
    : [];
  const revenueValues = revenueCanvas.dataset.values
    ? JSON.parse(revenueCanvas.dataset.values)
    : [];

  console.log("Revenue chart data:", revenueLabels, revenueValues);

  if (revenueLabels.length === 0 || revenueValues.length === 0) {
    console.log("Không có dữ liệu cho biểu đồ doanh thu");

    // Tạo placeholder chart nếu không có dữ liệu
    new Chart(revenueCanvas, {
      type: "line",
      data: {
        labels: ["Không có dữ liệu"],
        datasets: [
          {
            label: "Doanh thu (VNĐ)",
            data: [0],
            borderColor: "#4a90e2",
            backgroundColor: "rgba(74, 144, 226, 0.1)",
            tension: 0.3,
            fill: true,
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });
    return;
  }

  try {
    new Chart(revenueCanvas, {
      type: "line",
      data: {
        labels: revenueLabels,
        datasets: [
          {
            label: "Doanh thu (VNĐ)",
            data: revenueValues,
            borderColor: "#4a90e2",
            backgroundColor: "rgba(74, 144, 226, 0.1)",
            tension: 0.3,
            fill: true,
            borderWidth: 3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            labels: {
              color: "#333",
              font: {
                size: 14,
              },
            },
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return "Doanh thu: " + formatCurrency(context.parsed.y);
              },
            },
            backgroundColor: "rgba(0, 0, 0, 0.8)",
            titleColor: "#fff",
            bodyColor: "#fff",
            borderColor: "#4a90e2",
            borderWidth: 1,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: "#666",
              callback: function (value) {
                if (value >= 1000000) {
                  return (value / 1000000).toFixed(1) + "M";
                } else if (value >= 1000) {
                  return (value / 1000).toFixed(0) + "K";
                }
                return value;
              },
              font: {
                size: 12,
              },
            },
            grid: {
              color: "rgba(0, 0, 0, 0.1)",
              drawBorder: false,
            },
          },
          x: {
            ticks: {
              color: "#666",
              font: {
                size: 12,
              },
            },
            grid: {
              color: "rgba(0, 0, 0, 0.1)",
              drawBorder: false,
            },
          },
        },
        interaction: {
          intersect: false,
          mode: "index",
        },
        elements: {
          point: {
            radius: 4,
            hoverRadius: 6,
            backgroundColor: "#4a90e2",
          },
        },
      },
    });

    console.log("Biểu đồ doanh thu đã được khởi tạo thành công");
  } catch (error) {
    console.error("Lỗi khi khởi tạo biểu đồ doanh thu:", error);

    // Hiển thị thông báo lỗi
    revenueCanvas.parentNode.innerHTML +=
      '<div class="chart-error">Không thể tải biểu đồ doanh thu</div>';
  }
}

/**
 * Khởi tạo biểu đồ thể loại
 */
function initializeGenreChart() {
  const genreCanvas = document.getElementById("genreChart");
  if (!genreCanvas) {
    console.log("Không tìm thấy element #genreChart");
    return;
  }

  // Lấy dữ liệu từ data attributes
  const genreLabels = genreCanvas.dataset.labels
    ? JSON.parse(genreCanvas.dataset.labels)
    : [];
  const genreValues = genreCanvas.dataset.values
    ? JSON.parse(genreCanvas.dataset.values)
    : [];
  const genreColors = genreCanvas.dataset.colors
    ? JSON.parse(genreCanvas.dataset.colors)
    : [
        "#f0c419",
        "#ff6b6b",
        "#4ecdc4",
        "#95e1d3",
        "#aaa",
        "#6a89cc",
        "#b8e994",
        "#f8c291",
        "#82ccdd",
        "#e55039",
      ];

  console.log("Genre chart data:", genreLabels, genreValues);

  if (genreLabels.length === 0 || genreValues.length === 0) {
    console.log("Không có dữ liệu cho biểu đồ thể loại");

    // Tạo placeholder chart nếu không có dữ liệu
    new Chart(genreCanvas, {
      type: "doughnut",
      data: {
        labels: ["Không có dữ liệu"],
        datasets: [
          {
            data: [100],
            backgroundColor: ["#ccc"],
            borderColor: "#fff",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });
    return;
  }

  try {
    new Chart(genreCanvas, {
      type: "doughnut",
      data: {
        labels: genreLabels,
        datasets: [
          {
            data: genreValues,
            backgroundColor: genreColors,
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: "right",
            labels: {
              color: "#333",
              padding: 15,
              font: {
                size: 12,
              },
              usePointStyle: true,
              pointStyle: "circle",
            },
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || "";
                const value = context.parsed || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: ${value} phim (${percentage}%)`;
              },
            },
            backgroundColor: "rgba(0, 0, 0, 0.8)",
            titleColor: "#fff",
            bodyColor: "#fff",
            borderColor: "#f0c419",
            borderWidth: 1,
          },
        },
        cutout: "60%",
      },
    });

    console.log("Biểu đồ thể loại đã được khởi tạo thành công");
  } catch (error) {
    console.error("Lỗi khi khởi tạo biểu đồ thể loại:", error);

    // Hiển thị thông báo lỗi
    genreCanvas.parentNode.innerHTML +=
      '<div class="chart-error">Không thể tải biểu đồ thể loại</div>';
  }
}

/**
 * Hàm định dạng tiền tệ Việt Nam
 */
function formatCurrency(amount) {
  if (typeof amount !== "number") {
    amount = parseFloat(amount) || 0;
  }
  return amount.toLocaleString("vi-VN") + " VNĐ";
}

/**
 * Hàm định dạng phần trăm
 */
function formatPercent(amount) {
  if (typeof amount !== "number") {
    amount = parseFloat(amount) || 0;
  }
  return Math.round(amount * 10) / 10 + "%";
}

/**
 * Hàm reload biểu đồ (nếu cần refresh dữ liệu)
 */
function reloadCharts() {
  console.log("Reloading charts...");

  // Hủy các chart cũ
  const charts = Chart.instances;
  for (let i = 0; i < charts.length; i++) {
    if (
      charts[i].canvas.id === "revenueChart" ||
      charts[i].canvas.id === "genreChart"
    ) {
      charts[i].destroy();
    }
  }

  // Khởi tạo lại charts
  initializeRevenueChart();
  initializeGenreChart();
}

/**
 * Hàm xử lý filter form tự động submit
 */
function setupAutoSubmit() {
  console.log("Setting up auto submit...");

  // Xử lý select có onchange
  const filterSelects = document.querySelectorAll(
    "#filter-time, #filter-cinema"
  );
  filterSelects.forEach((select) => {
    select.addEventListener("change", function () {
      console.log("Select changed:", this.id, this.value);
      this.form.submit();
    });
  });

  // Xử lý input date có onchange
  const dateInputs = document.querySelectorAll(
    'input[name="custom-date"], input[name="custom-month"]'
  );
  dateInputs.forEach((input) => {
    input.addEventListener("change", function () {
      console.log("Date input changed:", this.name, this.value);
      this.form.submit();
    });
  });

  // Xử lý các nút filter
  const filterButtons = document.querySelectorAll(".btn-filter");
  filterButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Filter button clicked");
      this.form.submit();
    });
  });

  // Xử lý các nút reset
  const resetButtons = document.querySelectorAll(".btn-reset");
  resetButtons.forEach((button) => {
    if (button.tagName === "A") {
      button.addEventListener("click", function (e) {
        console.log("Reset link clicked:", this.href);
        // Không cần preventDefault, để link hoạt động bình thường
      });
    }
  });
}

/**
 * Hàm cập nhật thời gian thực (nếu cần)
 */
function startRealTimeUpdates() {
  // Nếu cần cập nhật dữ liệu theo thời gian thực
  // setInterval(() => {
  //   // Gọi API hoặc reload charts
  //   reloadCharts();
  // }, 300000); // 5 phút
}

// Khởi động cập nhật thời gian thực nếu cần
// document.addEventListener("DOMContentLoaded", startRealTimeUpdates);

/**
 * Xử lý responsive cho charts
 */
function handleChartResponsive() {
  const charts = document.querySelectorAll("#revenueChart, #genreChart");

  charts.forEach((chart) => {
    const container = chart.parentElement;
    const updateSize = () => {
      const width = container.clientWidth;
      chart.style.width = width + "px";
      chart.style.height = Math.min(width * 0.6, 400) + "px";
    };

    updateSize();
    window.addEventListener("resize", updateSize);
  });
}

// Khởi tạo responsive cho charts
document.addEventListener("DOMContentLoaded", handleChartResponsive);
