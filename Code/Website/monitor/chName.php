<?php

    session_start();

    $securitycode = '';
    require_once("../connection.php");
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


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['ch-select'])&&isset($_POST['id_ch'])&&isset($_POST['name_ch']))
        {
            
            $feedKey = '<Feed_Key>';
            $ch_select = $_POST['ch-select'];
            $id_ch = $_POST['id_ch'];
            $name_ch = $_POST['name_ch'];

            

            if($ch_select == "RFID")
            {
                $url = "https://io.adafruit.com/api/v2/sonvu20489/feeds/$securitycode.chnamerfid/data";
                $json = array();
                $json["name"] = $name_ch;
                $json["ch_RFID"] = $id_ch;
                $sendjson = json_encode($json);
            }
            else
            {
                $url = "https://io.adafruit.com/api/v2/sonvu20489/feeds/$securitycode.chnamefinger/data";
                $json = array();
                $json["name"] = $name_ch;
                $json["ch_FingerID"] = $id_ch;
                $sendjson = json_encode($json);
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); //set server to post
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //set return value if successful = 1
            curl_setopt($ch, CURLOPT_POST, 1); //
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['value' => $sendjson]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'X-AIO-Key: ' . $feedKey
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            if ($response === false) {
                echo "Có lỗi xảy ra vui lòng nhấp ".'<a style = "color: blue"href="/monitor" >Vào đây</a> để trở về';
            } else {
                echo "Message published to Adafruit IO successfully.";
                $sql = "SELECT * FROM ref_table where device_code = '$securitycode'";

                $result = mysqli_query($conn,$sql);

                if(mysqli_num_rows($result))
                {
                    $row = mysqli_fetch_array($result);
                    $ref_RFID = $row['ref_RFID'];
                    $ref_Finger = $row['ref_Finger'];
                }
                else
                {
                    echo "Có lỗi xảy ra vui lòng nhấp ".'<a style = "color: blue"href="/monitor" >Vào đây</a> để trở về';
                }
                if($ch_select == 'RFID')
                {
                    $sql = "UPDATE $ref_RFID SET RFID_name = '$name_ch' where slot = '$id_ch'";
                    $result = mysqli_query($conn,$sql);
                    if($result)
                    {
                        echo "Update tên cho RFID thành công";
                        sleep(2);
                        header('Location: /monitor');
                    }
                    else
                    {
                        echo "Có lỗi xảy ra vui lòng nhấp ".'<a style = "color: blue"href="/monitor" >Vào đây</a> để trở về';
                    }
                }
                else
                {
                    $sql = "UPDATE $ref_Finger SET Name = '$name_ch' where id = '$id_ch'";
                    $result = mysqli_query($conn,$sql);
                    if($result)
                    {
                        echo "Update tên cho vân tay thành công";
                        sleep(2);
                        header('Location: /monitor');
                    }
                    else
                    {
                        echo "Có lỗi xảy ra vui lòng nhấp ".'<a style = "color: blue"href="/monitor" >Vào đây</a> để trở về';
                    }
                }
                
            }
            
        }

    }
?>