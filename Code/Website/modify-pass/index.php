<?php

    session_start();

    $securitycode = '';

    if(isset($_SESSION['device'])&&isset($_SESSION['name']))

    {

        $securitycode = $_SESSION['device'];

        // $substr_sercode = substr($securitycode,-6);

        $username = $_SESSION['username'];

        $nameacc = $_SESSION['name'];

    }

    else

    {

        echo "failed";

        header('Location: /login');

    }

?>

<html>

    <head>

        <title>Đổi mật khẩu</title>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="/C-File/logo.png" type="image/x-icon">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

        <link rel="stylesheet" href="./assets/css/style.css">

        <link rel="stylesheet" href="./assets/css/topnav.css">

        <link rel="stylesheet" href="./assets/css/responsive.css">

        <script src="./assets/js/javascript.js"></script>

        <style>

            .form-group.invalid .form-control {

                border-color: #f33a58;

            }



            .form-group.invalid .form-message {

                color: #f33a58;

            }

        </style>

    </head>

    <body>

        <div class="top-nav" id="#">

            <div class="navigation">

                <span class="symbol_menu">≡</span>

                <ul class="menu">

                    <li><a href="/">Trang chủ</a></li>

                    <li><a class = "select" href="/history_door">Quản lý khóa của bạn</a></li>

                    <li><a href="/about-us">Giới thiệu</a></li>

                    <li><a href="/Contact">Liên hệ</a></li>

                </ul>

                <div class="user_info">

                    <span> Xin chào <?php echo $nameacc?></span>

                    <ul>

                        <li><a href="/modify-pass/">Đổi mật khẩu</a></li>

                        <li><a href="/login/logout.php">Đăng xuất</a></li>

                    </ul>

                </div>

            </div>

        </div>

        <div class="nav-control">

            <ul>

                <li><a href="#"><i class="fas fa-chevron-up"></i></a></li>

                <li><a href="/history_door/"><i class="fas fa-home"></i></a></li>

                <li><a href="/monitor/"><i class="fas fa-tablet-alt"></i></a></li>

                <li><a href="./"><i class="fas fa-sync"></i></a></li>

                <li><a href="/api/"><i class="fas fa-images"></i></a></li>

                <li><a href="http://khoacuaonline.000webhostapp.com/video_stream/"><i class="fas fa-video"></i></a></li>

            </ul>

        </div>

        <form action="" method ="POST" id ="form-1">

            <p style="font-size: 24px">Đổi mật khẩu tài khoản của bạn</p>

            <div class="form-group">

                <Label for="input-oldpassword"><b>Old Password</b></Label>

                <div class="input-field">

                    <input class ="form-control" type="password" placeholder="Input your old password" name="input-oldpassword" id = "input-oldpassword">

                    <div class = "btnshow">

                        <i class="far fa-eye"></i>

                    </div>

                </div>

                

                <span class="form-message"></span>

            </div>

            <div class="form-group">

                    <Label for="input-password"><b>New Password</b></Label>

                    <div class="input-field">

                        <input class ="form-control" type="password" placeholder="8-16 characters" name="input-password" id = "input-password">

                        <div class = "btnshow">

                            <i class="far fa-eye"></i>

                        </div>

                    </div>

                    

                    <span class="form-message"></span>

            </div>

            <div class="form-group">

                <Label for="input-repass"><b>Confirm new password</b></Label>

                <div class="input-field">

                    <input class ="form-control" type="password" placeholder="8-16 characters" name="input-repass" id="input-repass">

                    <div class = "btnshow">

                        <i class="far fa-eye"></i>

                    </div>

                </div>

                <span class="form-message"></span>

            </div>

            <script type="text/javascript" src="./assets/js/validation.js"></script>

            <script>



                document.addEventListener('DOMContentLoaded', function () {

                    // Mong muốn của chúng ta

                    Validator({

                    form: '#form-1',

                    formGroupSelector: '.form-group',

                    errorSelector: '.form-message',

                    rules: [



                        Validator.isRequired('#input-oldpassword'),

                        Validator.minLength('#input-password', 8),

                        Validator.isRequired('#input-repass'),

                        Validator.isConfirmed('#input-repass', function () {

                        return document.querySelector('#form-1 #input-password').value;

                        }, 'Mật khẩu nhập lại không chính xác')

                    ],

                    onSubmit: ''

                    });

                });

                



                </script>

                <?php

                    require_once('../connection.php');

                    $sql = "select * from user_infor where username = '$username'";

                    if(isset($_POST['btn_submit']))

                    {

                        $old_password = $_POST['input-oldpassword'];

                        $password = $_POST['input-password'];

                        $result = mysqli_query($conn,$sql);

                        if(mysqli_num_rows($result))

                        {

                            $row = mysqli_fetch_array($result);

                            $db_password = $row['password'];
                            if($old_password == $db_password)

                            {

                                $sql = "update user_infor SET password = '$password' where username = '$username'";

                                if(mysqli_query($conn,$sql) === TRUE)

                                {

                                    echo "<p style = \"color: red;\">đã đổi mật khẩu thành công</p>";

                                }

                                else

                                {

                                    echo "<p style = \"color: red;\">đã có lỗi xảy ra vui lòng  thử lại</p>";

                                }

                            }

                            else

                            {

                                echo "<p style = \"color: red;\">Password sai vui lòng thử lại</p>";

                            }

                        }

                    }

                    

                ?>

            <button class="form-submit"name="btn_submit">Đổi mật khẩu!</button>



        </form>

    </body>

</html>