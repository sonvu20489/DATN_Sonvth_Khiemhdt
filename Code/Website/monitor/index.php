<?php

    session_start();

    $securitycode = '';

    if(isset($_SESSION['device'])&&isset($_SESSION['name']))

    {

        $securitycode = $_SESSION['device'];

        // $substr_sercode = substr($securitycode,-6);

        $nameacc = $_SESSION['name'];

    }

    else

    {

        header('Location: /login');

    }

?>

<html>

    <head>

        <title>Info access key</title>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="/C-File/logo.png" type="image/x-icon">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

        <link rel="stylesheet" href="./assets/css/style.css">

        <link rel="stylesheet" href="./assets/css/topnav.css">

        <link rel="stylesheet" href="./assets/css/responsive.css">

        <script src="./assets/js/javascript.js"></script>

    </head>

    <body>

    <div class="top-nav" id="#">

            <div class="navigation">

                <span class="symbol_menu">≡</span>

                <ul class="menu">

                    <li><a href="/">Trang chủ</a></li>

                    <li><a class = "select"href="/history_door">Quản lý khóa của bạn</a></li>

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

        <?php 

            require_once('../connection.php');

            $sql = "SELECT * FROM ref_table where device_code = '$securitycode'";

            $result = mysqli_query($conn,$sql);

            if(mysqli_num_rows($result))

            {

                $row = mysqli_fetch_array($result);

                $ref_RFID = $row['ref_RFID'];

                $ref_Finger = $row['ref_Finger'];
                $sql = "SELECT * FROM $ref_RFID ORDER BY slot ASC";
                $resultR = mysqli_query($conn, $sql);

                $sql = "SELECT * FROM $ref_Finger ORDER BY id ASC";

                $resultF = mysqli_query($conn, $sql);
            }

            else

            {

                echo "Có lỗi xảy ra vui lòng thử lại";

            }

        ?>

        <div class="container">
            <form action="chName.php" method="POST" class="Update-Form">
                <h3 style="color:green"> Cập nhật tên người dùng</h3>
                <select name="ch-select">
                    <option value="RFID">RFID</option>
                    <option value="Finger">Finger</option>
                </select>
                <label for="id_ch">id:</label>
                <input type="number" id ="id_ch" placeholder="id" name="id_ch" min="1" max = "20" value="1" required>
                <label for="name_ch">name</label>
                <input id="name_ch" type="text" placeholder="name" maxlength="10" name="name_ch" required>
                <button class="form-submit" type="submit" name="btn_submit">Cập nhật</button>
            </form>
            
            <div class="RFID_table">
                <h2>Bảng thông tin RFID lưu trữ</h2>

                <table>

                    <thead>

                        <tr>

                            <td>slot</td>

                            <td>Name</td>

                            <td>RFID Card</td>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                            

                            if($resultR)

                            {

                                if(mysqli_num_rows($resultR))

                                {

                                    

                                    while ($row = $resultR->fetch_assoc())

                                    {

                                        $slot = $row['slot'];

                                        $Name = $row['RFID_name'];

                                        $RFID = $row['RFID_Code'];

                                        echo '<tr>'

                                                .'<td> ' . $slot . '</td>'

                                                .'<td> ' . $Name . '</td>'

                                                .'<td> ' . $RFID . '</td>'
                                                ."<td> <a href = \"./del.php?table=RFID&id=$slot\"> Xóa </a>"
                                                . '</tr>'; 

                                                

                                    }

                                }

                                else

                                {

                                    '<tr><td colspan = "3">Không có data trên cơ sở dữ liệu</td></tr>';

                                }

                                $resultR->free();

                            }      

                        ?>

                    </tbody>

                </table>

            </div>

            

            <div class="Finger_Table">

                <h2>Bảng thông tin vân tay lưu trữ</h2>

                <table>

                    <thead>

                        <tr>

                            <td>id</td>

                            <td>Name</td>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                            

                            if($resultF)

                            {

                                if(mysqli_num_rows($resultF))

                                {

                                    

                                    while ($row = $resultF->fetch_assoc())

                                    {

                                        $id = $row['id'];

                                        $Name = $row['Name'];

                                        echo '<tr>'

                                                .'<td> ' . $id . '</td>'

                                                .'<td> ' . $Name . '</td>'
                                                ."<td> <a href = \"./del.php?table=Finger&id=$id\"> Xóa </a>"
                                                . '</tr>'; 

                                                

                                    }

                                }

                                else

                                {

                                    '<tr><td colspan = "3">Không có data trên cơ sở dữ liệu</td></tr>';

                                }

                                $resultF->free();

                            }      

                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </body>

</html>