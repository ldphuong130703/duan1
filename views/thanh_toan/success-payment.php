<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đặt hàng thành công</title>
    <!-- Link đến Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link đến Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .notification-box1 {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            border-left: 6px solid #28a745;
        }
        .notification-box2 {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            border-left: 6px solid #ff0000;
        }

        .notification-icon {
            font-size: 60px;
            color: #28a745;
        }

        .notification-box h4 {
            color: #343a40;
        }

        .notification-box p {
            color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        a {
            text-decoration: none;
            color: white;
            padding: 7px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="notification-box1 text-center" <?= $style1 ?>>
                    <div class="notification-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="mb-3">Bạn đã thanh toán đơn hàng thành công!</h3>
                    <p class="mb-4">Cảm ơn bạn đã đặt hàng. Chúng tôi đã nhận được đơn hàng của bạn và sẽ xử lý ngay lập tức.</p>
                    <a href="<?= BASE_URL ?>" class="btn btn-primary p-3">Về trang chủ</a>
                </div>
                <div class="notification-box2 text-center" <?= $style2 ?>>
                    <div class="notification-icon mb-3">
                        <i class="fas fa-exclamation-circle" style="color: #ff0000;"></i>
                    </div>
                    <h3 class="mb-3">Bạn đã thanh toán đơn hàng thất bại!</h3>
                    <p class="mb-4">Giao dịch không thành công, quá hạn hoặc bị hủy. Bạn vui lòng tiến hành thanh toán lại</p>
                    <a href="<?= BASE_URL ?>" class="btn btn-primary p-3">Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Link đến Bootstrap JS và Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>

</html>