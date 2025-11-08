// Đợi cho tất cả HTML được tải xong
document.addEventListener('DOMContentLoaded', function() {
    
    // Lấy các phần tử DOM
    const filterBtn = document.getElementById('filterBtn');
    const filterDropdown = document.getElementById('filterDropdown');
    
    // Chỉ chạy code nếu tìm thấy các phần tử
    if (filterBtn && filterDropdown) {
        
        const arrowIcon = filterBtn.querySelector('.fa-chevron-down'); // Lấy icon mũi tên

        // 1. Khi nhấp vào NÚT
        filterBtn.addEventListener('click', function(event) {
            // Ngăn sự kiện click này lan ra ngoài (để không bị bắt bởi window.addEventListener)
            event.stopPropagation(); 
            
            // Thêm/xóa class 'show' trên dropdown
            filterDropdown.classList.toggle('show');
            
            // Xoay mũi tên
            if (arrowIcon) {
                if (filterDropdown.classList.contains('show')) {
                    arrowIcon.style.transform = 'rotate(180deg)';
                } else {
                    arrowIcon.style.transform = 'rotate(0deg)';
                }
            }
        });

        // 2. Khi nhấp BẤT CỨ ĐÂU BÊN NGOÀI
        window.addEventListener('click', function(event) {
            // Nếu dropdown đang mở VÀ người dùng không nhấp vào nút
            if (filterDropdown.classList.contains('show') && !filterBtn.contains(event.target)) {
                
                // Đóng dropdown
                filterDropdown.classList.remove('show');
                
                // Xoay mũi tên về vị trí cũ
                if (arrowIcon) {
                    arrowIcon.style.transform = 'rotate(0deg)';
                }
            }
        });
    }
});