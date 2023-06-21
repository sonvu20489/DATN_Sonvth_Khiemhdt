<?php

    require_once('../connection.php');

    // Check if the request method is POST

    header('Content-type: text/html');



    if(isset($_GET['ser_code'])&&isset($_POST['RFID']) &&isset($_POST['slot'])&&isset($_POST['Name']))

    {



        $securitycode = $_GET['ser_code'];

        $rfid = $_POST['RFID'];

        $slot = $_POST['slot'];

        $Name = $_POST['Name']; 
        $sql = "Select * from ref_table where device_code = '$securitycode'";

        $result = mysqli_query($conn,$sql);

        if(mysqli_num_rows($result))

        {

            $row = mysqli_fetch_array($result);

            $ref_RFID = $row['ref_RFID'];

            $sql = "Delete from $ref_RFID";

            $result = mysqli_query($conn,$sql);

            if($result === True)
            {

                echo "Deleted!";

            }

            else
            {

                echo "Failed Delete!";

            }
            if($rfid!='None'&&$slot!='NULL'&&$Name!='NULL')
            {
                $rfid = json_decode($rfid);

                $slot = json_decode($slot);
        
                $Name = json_decode($Name);
        
                $RFID = array();
        
                for($i = 0;$i<count($slot);$i++)
        
                {
        
                    array_push($RFID,json_encode($rfid[$i]));
        
                }

                for ($i = 0;$i<count($Name);$i++)

                {

                    $sql = "INSERT INTO `$ref_RFID` (

                        `slot`,

                        `RFID_name`,

                        `RFID_code`

                        ) VALUES (

                        '$slot[$i]',

                        '$Name[$i]',

                        '$RFID[$i]'

                        )";

                    $result1 = mysqli_query($conn,$sql);

                    if($result)

                    {

                        echo $slot[$i]."\n";

                        echo $Name[$i]."\n";

                        echo $RFID[$i]."\n";

                    }

                    else

                    {

                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);

                    }

                }
            }
            

        }

        else

        {

            echo 'có lỗi xảy ra vui lòng thử lại';

        }

    }

    $conn->close();

?>