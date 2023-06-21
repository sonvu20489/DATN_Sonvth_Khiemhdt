<?php
    require_once('../connection.php');
    if(isset($_GET['ser_code']))
    {
        $securitycode = $_GET['ser_code'];
        $sql = "Select * from ref_table where device_code='$securitycode'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)>0)
        {
            $row = mysqli_fetch_array($result);
            $ref_history = $row['ref_history'];
            $sql = "Delete from $ref_history";
            if(mysqli_query($conn, $sql) === true)
            {
                echo "đã xóa thành công";
                header('Location: /history_door');
            }
            else
            {
                echo "Có lỗi xảy ra";
                echo "<a href = \"/history_door\"> Nhấn vào đây để quay lại</a> ";
            }
        }
        else
        {
            echo "Có lỗi xảy ra";
            echo "<a href = \"/history_door\"> Nhấn vào đây để quay lại</a> ";
        }
    }
    else
    {
        echo "Có lỗi xảy ra";
        echo "<a href = \"/history_door\"> Nhấn vào đây để quay lại</a> ";
    }
?>