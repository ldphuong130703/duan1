<?php
class DashboardControllers
{
    public $TK;

    public $user;

    public $SP;

    public $dh;

    public $mm;
    public $ctl;
    public function __construct()
    {
        $this->TK = new ClientThanhToanModel();
        $this->user = new LoginModel();
        $this->SP = new ClientSanPhamModel();
        $this->dh = new ClientDonHangModel();
        $this->ctl = new ClientThanhToanController();
        $this->mm = new ClientMoMoModel();
    }
    function home()
    {
        $title = 'clothes';
        $view = 'home';
        require_once PATH_VIEW . 'layouts/master.php';
    }
    function info()
    {
        $title = 'thông tin khách hàng';
        $listDonHang = $this->dh->getDonHangByIdAcc($_SESSION['user']['id']);

        require_once PATH_VIEW . 'info/index.php';
    }
    function detailOrder($id)
    {
        $order = $this->dh->getDonHangById($id);
        $details = $this->dh->getDetailDonHang($id);
        
        if (isset($_POST['huyhang'])) {
            $this->dh->updatePayment(11, $id);
            foreach ($details as $product) {
                $old_soluong = $this->SP->getSoLuong($product['san_pham_id']);
                $new_soluong = $old_soluong + $product['so_luong'];
                $this->SP->updateSoLuong($product['san_pham_id'], $new_soluong);
            }
            header("Location: " . BASE_URL . '?act=ct-donhang&id=' . $id);
            exit();
        }
        if (isset($_POST['hoanhang'])) {
            $this->dh->updatePayment(10, $id);
            header("Location: " . BASE_URL . '?act=ct-donhang&id=' . $id);
            exit();
        }
        if (isset($_POST['hoanthanh'])) {
            $this->dh->updatePayment(9, $id);
            header("Location: " . BASE_URL . '?act=ct-donhang&id=' . $id);
            exit();
        }
        if (isset($_POST['thanhtoan'])) {
            $this->ctl->online_checkout((double) $order['tong_tien'], time(), $order['id']);
        }
        require_once PATH_VIEW . 'info/detailorder.php';
    }
    function lienhe()
    {
        $title = 'liên hệ';
        require_once PATH_VIEW . 'lien_he/index.php';
        exit();
    }

    function thanhtoan()
    {
        $title = 'thanh toán';
        require_once PATH_VIEW . 'thanh_toan/index.php';
        exit();
    }


    function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            header("location:" . BASE_URL_ADMIN . "?act=addlogin");
            exit();
        }
        $title = 'clothes';
        $view = 'dashboard';
        require_once PATH_VIEW_ADMIN . 'layouts/master.php';
    }
    function addformlogin()
    {
        require_once PATH_VIEW_ADMIN . 'login/login.php';
        exit();
    }
    function login()
    {
        $getAllTaiKhoan = $this->login->getAllTaiKhoan();


        if (!isset($_SESSION['user'])) {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['loi'] = "Vui lòng nhập thông tin !!";
                header("location:" . BASE_URL_ADMIN);
                exit();
            }

            $email = $_POST['email'];
            $password = $_POST['password'];
            $isLoginSuccessful = false;

            foreach ($getAllTaiKhoan as $key) {
                if ($email == $key["email"] && $password == $key["password"] && $key['id_vai_tro'] == 1) {
                    $_SESSION['user'] = $key['username'];
                    $_SESSION['img'] = $key['img'];
                    $isLoginSuccessful = true;
                    break;
                }
            }

            if ($isLoginSuccessful) {
                $this->dashboard();
            } else {
                $_SESSION['loi'] = "Tên đăng nhập hoặc mật khẩu không chính xác.";
                header("location:" . BASE_URL_ADMIN . "?act=addlogin");
            }
            header("location:" . BASE_URL_ADMIN . "?act=addlogin");
            exit();
        } else {
            $this->dashboard();
        }
    }
    function logout()
    {
        session_destroy();
        require_once PATH_VIEW_ADMIN . 'login/login.php';
        exit();
    }
}
