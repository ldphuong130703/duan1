<?php
class ClientThanhToanController
{
    public $TK;

    public $SP;

    public $dh;

    public $mm;
    public function __construct()
    {
        $this->TK = new ClientThanhToanModel();
        $this->SP = new ClientSanPhamModel();
        $this->dh = new ClientDonHangModel();
        $this->mm = new ClientMoMoModel();
    }
    public function successOrder()
    {
        // Sau khi xử lý xong, mới unset các session liên quan
        unset($_SESSION['product']);

        foreach ($_SESSION['select_product'] as $item => $value) {
            unset($_SESSION['cart'][$value]);
        }
        require_once PATH_VIEW . 'thanh_toan/success.php';
    }
    public function successPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['transId']) && isset($_GET['resultCode'])){
            $transId = ($_GET['transId']);
            $resultCode = $_GET['resultCode'];
            $amount = $_GET['amount'];
            $id = $_GET['id'];
            $this->mm->insertMoMo($transId, $resultCode, $amount);
            if($resultCode == 0){
                $this->dh->updatePayment(8,$id);
                $style1 = 'style="display: block;"';
                $style2 = 'style="display: none;"';
            }else{
                $style1 = 'style="display: none;"';
                $style2 = 'style="display: block;"';
            }
        }


        require_once PATH_VIEW . 'thanh_toan/success-payment.php';
    }
    public function thanhToan()
    {
        if (isset($_POST["select_product"])) {

            $_SESSION['select_product'] = $_POST['select_product'];
        }
        $selected_product_ids = isset($_SESSION['select_product']) ? $_SESSION['select_product'] : [];

        $selected_products = array_filter($_SESSION['cart'], function ($item) use ($selected_product_ids) {
            return in_array($item['id'], $selected_product_ids);
        });
        $_SESSION['product'] = $selected_products;

        
        $listPhuongThuc = $this->TK->getPhuongThucTT();
        $list1TK = $this->TK->getOneTK($_SESSION['user']['id']);

        $title = "1 tai khoan";

        require_once PATH_VIEW . 'thanh_toan/index.php';
    }
    public function tienHanhThanhToan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($_POST['id_pt'] == '1') {
                $id_tk = $_SESSION['user']['id'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $sdt = $_POST['sdt'];
                $diachi = $_POST['diachi'];
                $tinh = $_POST['province_name'];
                $huyen = $_POST['district_name'];
                $xa = $_POST['ward_name'];
                $diachi_full = $diachi . ', ' . $xa . ', ' . $huyen . ', ' . $tinh;
                $ngay_dat = date('Y-m-d');
                $id_pt = $_POST['id_pt'];
                $ghi_chu = $_POST['ghi_chu'];
                $ma_don_hang = time();
                $tong_tien = $_POST['tong_tien'];

                // Insert đơn hàng vào bảng don_hang
                $id_don_hang = $this->dh->insertdonhang($name, $email, $sdt, $diachi_full, $ngay_dat, $tong_tien, $ghi_chu, $id_tk, $id_pt, 1, $ma_don_hang);

                // Xử lí chi tiết đơn hàng
                $i = 0;
                foreach ($_SESSION['product'] as $product) {
                    $i = $i + 1;
                    $don_gia = str_replace(',', '', $product['price_sp']);
                    $so_luong = $product['soluong_sp'];
                    $thanh_tien = $don_gia * $so_luong;
                    $san_pham_id = $product['id'];

                    // Insert từng sản phẩm vào bảng chitietdonhang
                    $this->dh->insertchitietdonhang($don_gia, $so_luong, $thanh_tien, $id_don_hang, $san_pham_id);

                    // Cập nhật lại số lượng sản phẩm
                    $old_soluong = $this->SP->getSoLuong($product['id']);
                    $new_soluong = $old_soluong - $so_luong;
                    $this->SP->updateSoLuong($product['id'], $new_soluong);
                }

                header("location:" . BASE_URL . "?act=success-order");
                exit();
            } else {
                $id_tk = $_SESSION['user']['id'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $sdt = $_POST['sdt'];
                $diachi = $_POST['diachi'];
                $tinh = $_POST['province_name'];
                $huyen = $_POST['district_name'];
                $xa = $_POST['ward_name'];
                $diachi_full = $diachi . ', ' . $xa . ', ' . $huyen . ', ' . $tinh;
                $ngay_dat = date('Y-m-d');
                $id_pt = $_POST['id_pt'];
                $ghi_chu = $_POST['ghi_chu'];
                $ma_don_hang = time();
                $tong_tien = $_POST['tong_tien'];

                // Insert đơn hàng vào bảng don_hang
                $id_don_hang = $this->dh->insertdonhang($name, $email, $sdt, $diachi_full, $ngay_dat, $tong_tien, $ghi_chu, $id_tk, $id_pt, 6, $ma_don_hang);

                // Xử lí chi tiết đơn hàng
                $i = 0;
                foreach ($_SESSION['product'] as $product) {
                    $i = $i + 1;
                    $don_gia = str_replace(',', '', $product['price_sp']);
                    $so_luong = $product['soluong_sp'];
                    $thanh_tien = $don_gia * $so_luong;
                    $san_pham_id = $product['id'];

                    // Insert từng sản phẩm vào bảng chitietdonhang
                    $this->dh->insertchitietdonhang($don_gia, $so_luong, $thanh_tien, $id_don_hang, $san_pham_id);

                    // Cập nhật lại số lượng sản phẩm
                    $old_soluong = $this->SP->getSoLuong($product['id']);
                    $new_soluong = $old_soluong - $so_luong;
                    $this->SP->updateSoLuong($product['id'], $new_soluong);
                }
                unset($_SESSION['product']);

                foreach ($_SESSION['select_product'] as $item => $value) {
                    unset($_SESSION['cart'][$value]);
                }
                $this->online_checkout($tong_tien, $ma_don_hang, $id_don_hang);
            }

        }
        // Xử lý GET request khi MoMo trả về kết quả thanh toán

    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function online_checkout($tong_tien, $ma_don_hang, $id)
    {

        // Endpoint cho thanh toán bằng thẻ MoMo
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        // Các thông tin cấu hình MoMo
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua thẻ MoMo";

        // Tính toán số tiền và ID đơn hàng
        $amount = $tong_tien; // Bạn cần tính toán tổng số tiền từ giỏ hàng
        $orderId = $ma_don_hang; // ID đơn hàng
        $redirectUrl =BASE_URL. "?act=payment&id=" . $id;
        $ipnUrl =BASE_URL. "?act=payment&id=" . $id;
        $extraData = ""; // Các thông tin thêm nếu cần

        $requestId = time() . "";
        $requestType = "payWithATM"; // Chuyển thành "payWithATM" cho thanh toán bằng thẻ MoMo
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType, // Sử dụng "payWithATM" cho thẻ MoMo
            'signature' => $signature
        );

        // Gọi API MoMo
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // Kiểm tra nếu có 'payUrl'
        if (isset($jsonResult['payUrl'])) {
            header('Location: ' . $jsonResult['payUrl']); // Điều hướng đến trang thanh toán của MoMo
            exit();
        } else {
            // Xử lý khi không có 'payUrl' trong phản hồi
            echo "Đã xảy ra lỗi trong quá trình tạo đơn hàng với MoMo.";
        }
    }
}
