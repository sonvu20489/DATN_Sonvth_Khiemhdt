<!DOCTYPE html>

<html>



    <head>

        <title>Giới thiệu đề tài</title>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/C-File/logo.png" type="image/x-icon">

        <link rel="stylesheet" href="./assets/css/style.css">

        <link rel="stylesheet" href="./assets/css/topnav.css">

        <link rel="stylesheet" href="./assets/css/reponsive.css">


    </head>

    <body>

        <div class="top-nav" id="#">

            <div class="navigation">

                <span class="symbol_menu">≡</span>

                <ul class="menu">

                    <li><a href="/" >Trang chủ</a></li>

                    <li><a href="/history_door">Quản lý khóa của bạn</a></li>

                    <li><a href="/about-us" class="select">Giới thiệu</a></li>

                    <li><a href="/Contact">Liên hệ</a></li>

                </ul>

                <div>

            </div>

            <div class="overview">

                <a href="/">

                

                    <div class="content-header">

                        <img src="./assets/img/logo.jpg" alt="Logo website">

                        <div class="content-title">

                            <h1 class="test"> HỆ THỐNG KHÓA CỬA AN NINH CHO CĂN HỘ </h1>

                        </div>

                    </div>

                    <div class="content-">

                        <p>An ninh là một trong những yếu tố quan trọng nhất để đảm bảo sự

                            an toàn và an tâm trong không gian sống và làm việc của chúng ta.

                             Trong thời đại công nghệ hiện nay, các thiết bị an ninh thông minh

                              đã trở thành một phần không thể thiếu trong cuộc sống của con người.

                               Trong đó, khóa cửa là một trong những thiết bị an ninh cơ bản nhất, 

                               giúp ngăn chặn sự xâm nhập của người lạ vào không gian của mình. 

                               Với mục đích đó, chúng tôi đã tiến hành nghiên cứu và phát triển 

                               khóa cửa an ninh kết hợp RFID, vân tay và keypad kết hợp IoT

                               giám sát, quản lý khóa cửa trên website.

                        </p>

                    </div>

                </a>

            </div>

        </div>

        <div class="container">

            <div class="card">

                <div class="card-title">

                    <h2>MỤC TIÊU CỦA ĐỀ TÀI</h2>

                </div>

                <div class="card-content">

                    <p>

                    Mục tiêu của đề tài là xây dựng một hệ thống có độ bảo mật cao kết hợp 3
                    phương pháp mở khóa. Tạo ra một sản phẩm nhỏ gọn, 
                    dễ lắp đặt và sử dụng. Hệ thống giúp các gia đình có thể 
                    giám sát căn hộ nhà mình, giúp bảo vệ được tài sản tránh được các rủi ro 
                    về mất tài sản. Hệ thống có độ ổn định cao có thể hoạt động xuyên suốt và
                     đảm bảo được độ chính xác.

                    </p>

                    

                </div>

            </div>

            <div class="card">

                <div class="card-title">

                    <h2>HƯỚNG DẪN SỬ DỤNG KHOÁ AN NINH</h2>

                </div>

                <div class="card-content">
                    <h3>1. Hướng dẫn kết nối:</h3>

                    <p><b>Bước 1:</b> Kết nối các thiết bị và cấp điện cho mạch hoạt động.</p>

                    <p><b>Bước 2:</b> Khi thực hiện cấp nguồn xong kiểm tra ESP32-CAM có hiện đèn LED phía sau hay chưa, nếu chưa thì nhấn nút RST trên ESP32-CAM để restart lại.</p>
                    
                    <p><b>Bước 3:</b> Thiết lập WiFi bằng cách truy cập vào WiFi có SSID: ESP32-CAM Config (nếu không hiện SSID nhấn RST pin để restart lại ESP32-CAM cho đến khi thấy SSID hiển thị)</p>
                    <img src="/C-File/WiFi AP.jpg" height="380" width="214" alt="Kết nối WiFi AP để config">
                    <p>*Lưu ý: Khi restart ESP32-CAM nhiều lần mà cũng không hiện SSID kiểm tra lại camera.</p>
                    <p><b>Bước 4:</b> Thực hiện cấu hình:</p>
                    <p>+ Cấu hình email nhận cảnh báo (Setup) sau đó nhấn save rồi quay trở lại menu chính.</p>
                    <p>+ Tiếp theo, cấu hình WiFi cho ESP32-CAM và nhấn save. Khi kết nối WiFi thành công ESP32-CAM sẽ chớp tắt Flash 2 lần, nếu không thành công truy cập lại configuration WiFi để thực hiện cấu hình lại.</p>
                    <img src="/C-File/main-menu.jpg" height="380" width="214" alt="Hình các tùy chọn config">
                    <img src="/C-File/Config-wifi.jpg" height="380" width="214" alt="Cài đặt WiFi kết nối">
                    <img src="/C-File/setting-email rx.jpg" height="380" width="214" alt="Cài đặt email nhận cảnh báo">
                    <p><b>Bước 5:</b> Thực hiện đăng ký tài khoản để giám sát hệ thống trên WEB. </p>
                    <p><b>Bước 6:</b> Nhập mật khẩu admin: “12345678” để truy cập vào menu ủy quyền để đổi mật khẩu mặc định “123456” (mật khẩu mở cửa), thêm vân tay, RFID,…</p>
                    <p><b>Bước 7:</b> Khi thêm các mật khẩu xong ta có thể dùng các mật khẩu để mở cửa</p>
                    <h3>2. Thao tác trên WEBSITE</h3>
                    <p><b>Bước 1:</b> Quét mã QR được dán trên thiết bị để lấy mã thiết bị đăng ký tài khoản.</p>
                    <p><b>Bước 2:</b>Tạo tài khoản cho thiết bị bằng cách truy cập website: https://khoacuaonline.000webhostapp.com/register.</p>
                    <p>Điền các thông tin cho tài khoản và mã sản phẩm trên thiết bị, sau đó nhấn nút đăng ký “Đăng ký ngay”.</p>
                    <p><b>Bước 3:</b> Đăng nhập hệ thống qua trang: https://khoacuaonline.000webhostapp.com/login hoặc nhấn vào “Quản lý khóa của bạn” trên mục điều hướng website để tiến hành đăng nhập.</p>
                    <p><b>Bước 4:</b> Thực hiện giám sát hệ thống trên trang WEB.</p>

                </div>

            </div>

        </div>

        <script src="./assets/js/javascript.js"></script>

    </body>

</html>