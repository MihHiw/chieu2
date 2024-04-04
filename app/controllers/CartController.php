<?php
class CartController
{

    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }


    public function updateQuality($id)
    {
        $newQuantity = $_POST['quality'];
        foreach ($_SESSION['cart'] as &$item) {
            if ($item->id == $id) {
                $item->quantity = $newQuantity;

                break;
            }
        }
        header('Location: /chieu2/cart/show');
    }

    public function Add($id)
    {
        // Khởi tạo một phiên cart nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Lấy sản phẩm từ ProductModel bằng $id
        $product = $this->productModel->getProductById($id);

        // Nếu sản phẩm tồn tại, thêm vào giỏ hàng
        if ($product) {
            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
            $productExist = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item->id == $id) {
                    $item->quantity++;
                    $productExist = true;
                    break;
                }
            }

            // Nếu sản phẩm chưa tồn tại trong giỏ hàng, thêm mới vào
            if (!$productExist) {
                $product->quantity = 1;
                $_SESSION['cart'][] = $product;
            }

            header('Location: /chieu2/cart/show');
        } else {
            echo "Không tìm thấy sản phẩm với ID này!";
        }
    }
    function show()
    {
        include_once 'app/views/cart/index.php';
    }

    public function removeItem($id)
    {
    // Kiểm tra xem giỏ hàng có tồn tại không
    if (isset($_SESSION['cart'])) {
        // Duyệt qua mỗi sản phẩm trong giỏ hàng
        foreach ($_SESSION['cart'] as $key => $item) {
            // Nếu ID của sản phẩm trùng khớp với ID của sản phẩm cần xóa
            if ($item->id == $id) {
                // Xóa sản phẩm khỏi giỏ hàng
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
    // Chuyển hướng người dùng trở lại trang giỏ hàng
    header('Location: /chieu2/cart/show');
}

}
