<?php
    require_once('../connection.php');
    // Check if the request method is POST
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
    
    if(isset($_GET['table']) && isset($_GET['id']))
    {
        $feedKey = '<Feed_Key>';
        $table = $_GET['table'];
        $id_del = $_GET['id'];
        if($table == 'RFID')
        {
            $url = "https://io.adafruit.com/api/v2/sonvu20489/feeds/$securitycode.delrfid/data";
        }
        else
        {
            $url = "https://io.adafruit.com/api/v2/sonvu20489/feeds/$securitycode.delfinger/data";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['value' => $id_del]));
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
            sleep(2);
            header('Location: /monitor');
        }
    }
    else
    {
        echo "Có lỗi xảy ra vui lòng nhấp ".'<a style = "color: blue"href="/monitor" >Vào đây</a> để trở về';
    }
    $conn->close();
?>