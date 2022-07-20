<?php

$conn = oci_connect('', '', '', 'AL32UTF8');
try {
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        /*File Upload*/

        $attachment_path = '';
        if ($_FILES['attachment_path']['tmp_name']!='') {
            $filType = mime_content_type($_FILES['attachment_path']['tmp_name']);

            if (!in_array($filType, ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf'])) {
                echo "Only  Image or PDF is allowed!";
                exit;
            }

            $uploaddir = 'uploads/';

            $extension = pathinfo($_FILES["attachment_path"]["name"], PATHINFO_EXTENSION);
//            $name = $_POST["attachment_path"];
            $fileName = date('m-d-Y_hia') . "." . $extension;

            $uploadfile = $uploaddir . basename($fileName);

            if (move_uploaded_file($_FILES['attachment_path']['tmp_name'], $uploadfile)) {
                $attachment_path = $fileName;
            }
        }

        /*File Upload*/

        $stid = oci_parse($conn, 'INSERT INTO DATA_APPLICATIONS a(name,designation,ORGANISATION,mobile,email,nid,purpose,DATA_FORMAT,ATTACHMENT_PATH,question,APPLICATION_STATUS,APPLICATION_DATE)
values(:name,:designation,:organisation,:mobile,:email,:nid,:purpose,:data_format,:attachment_path,:question,:application_status,to_date(:application_date,:format))');
        $application_status = 1;
        $application_date = date('Y/m/d');
        $format = 'yyyy/mm/dd';
        oci_bind_by_name($stid, ":name", $_POST['name']);
        oci_bind_by_name($stid, ":designation", $_POST['designation']);
        oci_bind_by_name($stid, ":organisation", $_POST['organisation']);
        oci_bind_by_name($stid, ":mobile", $_POST['mobile']);
        oci_bind_by_name($stid, ":email", $_POST['email']);
        oci_bind_by_name($stid, ":nid", $_POST['nid']);
        oci_bind_by_name($stid, ":purpose", $_POST['purpose']);
        oci_bind_by_name($stid, ":data_format", $_POST['data_format']);
        oci_bind_by_name($stid, ":attachment_path", $attachment_path);
        oci_bind_by_name($stid, ":question", $_POST['question']);
        oci_bind_by_name($stid, ":application_status", $application_status);
        oci_bind_by_name($stid, ":application_date", $application_date);
        oci_bind_by_name($stid, ":format", $format);
        $r = oci_execute($stid);
        if (!$r) {
            echo "<h2 align='center'>কারিগরি সমসার কারনে আপনার জিজ্ঞাসাটি জমা হয়নি। আবার চেষ্টা করুন। </h2><br> <a href='index.html'><button class='btn btn-warning'>Back</button></a> ";
        } else {
            echo "<h2 align='center'>আপনার জিজ্ঞাসাটি সফলভাবে জমা হয়েছে। শীগ্রই আপনার ইমেইলে তথ্য প্রদান করা হবে।</h2> <br> <a href='index.html'><button class='btn btn-warning'>Back</button></a> ";
            $to = "suzon.du@hotmail.com";
            $subject = "This is subject";

            $message = "<b>This is HTML message.</b>";
            $message .= "<h1>This is headline.</h1>";

            $header = "From:sebabox@banbeis.gov.bd \r\n";
//            $header .= "Cc:afgh@somedomain.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            $retval = mail ($to,$subject,$message,$header);

            if( $retval == true ) {
                echo "Message sent successfully...";
            }else {
                echo "Message could not be sent...";
            }
        }
    }
} catch (Exception $e) {
    echo "<h2 align='center'>কারিগরি সমসার কারনে আপনার জিজ্ঞাসাটি জমা হয়নি। আবার চেষ্টা করুন।</h2> <br> <a href='index.html'><button class='btn btn-warning'>Back</button></a> ";
}

