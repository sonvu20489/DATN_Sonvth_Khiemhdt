<?php
    require_once("../connection.php");
    header('Content-type: text/html');
    if(isset($_POST['ser_code'])&& isset($_POST['message'])&& isset($_POST['time']))
    {
        $securitycode = $_POST['ser_code'];
        $messsage = $_POST['message'];
        $time = $_POST['time'];
        $sql = "Select * from ref_table where device_code = '$securitycode'";
        $result = mysqli_query($conn,$sql);
        if(mysqli_num_rows($result))
        {
            $row = mysqli_fetch_array($result);
            $ref_history = $row['ref_history'];
            $sql = "insert into $ref_history (message,Timestamp) values ('$messsage','$time')";
            $result = mysqli_query($conn,$sql);
            if($result)
            {
                echo "Successfully insert!";
            }
            else
            {
                echo "Failed insert";
            }
        }
        else
        {
            echo "the security code is not exist or wrong!";
        }
    }
?>
