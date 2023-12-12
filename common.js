function toggleLogoutDropdown() {
    var dropdown = document.querySelector('.logout-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function logout() {
    // Thực hiện các bước đăng xuất ở đây
    window.location.href = 'logout.php';
}

// Open Orders Popup
function openOrders() {
    // Đóng popup giỏ hàng nếu đang mở
    closeOldPopup();

    // Thực hiện AJAX request để lấy lịch sử mua hàng
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getOrderHistory.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Hiển thị lịch sử mua hàng nhận được trong một popup mới
            showOrdersPopup(xhr.responseText);
        }
    };

    xhr.send();
}

function showOrdersPopup(orderHistory) {
    // Tạo một overlay (lớp làm mờ)
    var overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);

    // Tạo một popup mới cho lịch sử mua hàng
    var newModal = document.createElement('div');
    newModal.id = 'new-orders-popup';  // Thêm ID cho popup mới
    newModal.className = 'orders-popup';

    // Đặt nội dung HTML với lịch sử mua hàng nhận được
    newModal.innerHTML = orderHistory;

    // Thêm popup mới vào body
    document.body.appendChild(newModal);

    // Thêm nút đóng vào popup mới
    var closeButton = document.createElement('button');
    closeButton.textContent = 'Đóng';
    closeButton.style.backgroundColor = 'black';
    closeButton.addEventListener('click', function () {
        // Loại bỏ overlay và popup mới khi nút đóng được nhấp
        document.body.removeChild(overlay);
        document.body.removeChild(newModal);
    });

    // Thêm nút đóng vào popup mới
    newModal.appendChild(closeButton);
}


// Open Cart Popup
function openCartPopup() {
    // Đóng popup cũ trước khi mở popup mới
    closeOldPopup();

    // Thực hiện AJAX request để lấy các mục trong giỏ hàng
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getCartItems.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Hiển thị các mục giỏ hàng nhận được trong một popup mới
            showCartPopup(xhr.responseText);
        }
    };

    xhr.send();
}

function showCartPopup(cartItems) {
    // Tạo một overlay (lớp làm mờ)
    var overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);

    // Tạo một popup mới cho các mục giỏ hàng
    var newModal = document.createElement('div');
    newModal.id = 'new-cart-popup';  // Thêm ID cho popup mới
    newModal.className = 'cart-popup';

    // Đặt nội dung HTML với các mục giỏ hàng nhận được
    newModal.innerHTML = cartItems;

    // Thêm popup mới vào body
    document.body.appendChild(newModal);

    // Thêm nút đóng vào popup mới
    var closeButton = document.createElement('button');
    closeButton.textContent = 'Đóng';
    closeButton.style.backgroundColor = 'black';
    closeButton.addEventListener('click', function () {
        // Loại bỏ overlay và popup mới khi nút đóng được nhấp
        document.body.removeChild(overlay);
        document.body.removeChild(newModal);
    });

    // Thêm nút thanh toán vào popup mới
    var checkoutButton = document.createElement('button');
    checkoutButton.textContent = 'Thanh toán';
    checkoutButton.style.backgroundColor = '#2ecc71'; // Màu xanh lá cây
    checkoutButton.style.color = '#fff'; // Màu chữ trắng
    checkoutButton.addEventListener('click', checkout);

    // Thêm nút đóng và nút thanh toán vào popup mới
    newModal.appendChild(closeButton);
    newModal.appendChild(checkoutButton);
}

function removeFromCart(cartId) {
    // Thực hiện AJAX request để loại bỏ sản phẩm từ giỏ hàng
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'removeCartItem.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // Đóng popup cũ nếu bạn có một hàm cho việc đóng popup
            closeOldPopup();

            if (xhr.status === 200) {
                // Xử lý phản hồi, ví dụ: cập nhật hiển thị giỏ hàng
                console.log(xhr.responseText);

                // Hiển thị popup mới
                openCartPopup();  // Giả sử bạn có một hàm hiển thị giỏ hàng
            } else {
                // Xử lý lỗi
                console.error('Lỗi khi loại bỏ sản phẩm khỏi giỏ hàng.');
            }
        }
    };

    // Gửi cartId đến removeCartItem.php
    xhr.send('cartId=' + cartId);
}

function closeOldPopup() {
    // Thực hiện các bước để loại bỏ popup cũ
    var oldPopup = document.getElementById('new-cart-popup'); // Thay 'old-popup' bằng ID của popup cũ
    if (oldPopup) {
        document.body.removeChild(oldPopup);
    }

    // Loại bỏ lớp làm mờ khi popup cũ được đóng
    var overlay = document.querySelector('.overlay');
    if (overlay) {
        document.body.removeChild(overlay);
    }
}

function checkout() {
    // Thực hiện AJAX request để thực hiện thanh toán
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'checkout.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // Đóng popup và overlay
            closeOldPopup();

            if (xhr.status === 200) {
                // Hiển thị thông báo hoặc thực hiện các hành động khác tùy thuộc vào kết quả thanh toán
                console.log(xhr.responseText);
                alert('Thanh toán thành công! Sản phẩm sẽ được đóng gói và gửi cho bạn trong thời gian từ 2-3 ngày làm việc. Cảm ơn bạn đã ủng hộ Shop');
            } else {
                // Xử lý lỗi
                console.error('Lỗi khi thực hiện thanh toán.');
            }
        }
    };

    // Gửi yêu cầu thanh toán
    xhr.send();
}
