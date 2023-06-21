<?php
    require_once('./connection.php');
    if(isset($_GET['ser_code'])&& $_GET['url'])
    {
        $securitycode = $_GET['ser_code'];
        $url = $_GET['url'];
        $sql = "Select * from ref_table where device_code = '$securitycode'";
        $result = $conn->query($sql);
        if($result->num_rows>0)
        {
            $sql = "Update ref_table SET ref_stream = '$url' where device_code = '$securitycode'";
            if(mysqli_query($conn,$sql))
            {
                echo "update successfully!";
            }
            else
            {
                echo "failed!";
            }
        }
        else
        {
            echo "no device exist";
        }
    }
    else
    {
        echo "not enough parameters to query";
    }
?>