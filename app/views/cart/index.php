<?php
include_once 'app/views/share/header.php';

// Kiểm tra xem session cart có tồn tại không
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Giỏ hàng trống!";
} else {
    // Hiển thị danh sách sản phẩm trong giỏ hàng
    echo "<h2>Danh sách giỏ hàng</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Tên sản phẩm</th><th>Số lượng</th><th>Remove</th></tr>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>$item->id</td>";
        echo "<td>$item->name</td>";
        echo "<td>
                <div class='input-group'>
                    <button type='button' class='btn btn-outline-secondary' onclick='decrementQuantity(this)' data-item-id='$item->id'>-</button>
                    <input name='quality' type='number' value='".$item->quantity."' data-item-id='$item->id'/>
                    <button type='button' class='btn btn-outline-secondary' onclick='incrementQuantity(this)' data-item-id='$item->id'>+</button>
                </div>
              </td>";
        echo "<td><button type='button' class='btn btn-outline-danger' onclick='removeItem(\"$item->id\")'>Remove</button></td>";
        echo "</tr>";
    }

    echo "</table>";
    
    

    // Hiển thị nút Checkout
    echo "<form action='checkout.php' method='post'>";
    echo "<input type='submit' value='Checkout'>";
    echo "<a href='/chieu2'>Quay lại trang chủ</a>";
    echo "</form>";

}

include_once 'app/views/share/footer.php';
?>
<script>
 function updateQuantity(itemId, newQuantity) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/chieu2/cart/updateQuality/" + itemId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Cập nhật giao diện người dùng tại đây nếu cần
            console.log(xhr.responseText); // Hiển thị kết quả từ máy chủ (nếu có)
        }
    };
    xhr.send("quality=" + newQuantity);
}

function incrementQuantity(button) {
    var input = button.parentElement.querySelector('input[type="number"]');
    input.stepUp();
    updateQuantity(input.dataset.itemId, input.value);
}

function decrementQuantity(button) {
    var input = button.parentElement.querySelector('input[type="number"]');
    var newQuantity = parseInt(input.value) - 1;
    if (newQuantity < 0) {
        // Nếu số lượng nhỏ hơn 0, không thực hiện gì cả
        return;
    }
    input.value = newQuantity;
    updateQuantity(input.dataset.itemId, newQuantity);
}

function removeItem(itemId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/chieu2/cart/removeItem/" + itemId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            window.location.reload();
        }
    };
    xhr.send();
}
</script>
