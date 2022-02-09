<?php

$conn = oci_connect('stat_sebabox', 'sebabox', '192.168.245.33/ORCL','AL32UTF8');
try {
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        $stid = oci_parse($conn, 'INSERT INTO DATA_APPLICATIONS a(name,designation,ORGANISATION,mobile,email,nid,purpose,DATA_FORMAT,ATTACHMENT_PATH,question,APPLICATION_STATUS,APPLICATION_DATE)
values(:name,:designation,:organisation,:mobile,:email,:nid,:purpose,:data_format,:attachment_path,:question,:application_status,to_date(:application_date,:format))');
        $application_status = 1;
        $application_date = '2020/3/3';
        $format = 'yyyy/mm/dd';
        oci_bind_by_name($stid, ":name", $_POST['name']);
        oci_bind_by_name($stid, ":designation", $_POST['designation']);
        oci_bind_by_name($stid, ":organisation", $_POST['organisation']);
        oci_bind_by_name($stid, ":mobile", $_POST['mobile']);
        oci_bind_by_name($stid, ":email", $_POST['email']);
        oci_bind_by_name($stid, ":nid", $_POST['nid']);
        oci_bind_by_name($stid, ":purpose", $_POST['purpose']);
        oci_bind_by_name($stid, ":data_format", $_POST['data_format']);
        oci_bind_by_name($stid, ":attachment_path", $_POST['attachment_path']);
        oci_bind_by_name($stid, ":question", $_POST['question']);
        oci_bind_by_name($stid, ":application_status", $application_status);
        oci_bind_by_name($stid, ":application_date", $application_date);
        oci_bind_by_name($stid, ":format", $format);
        $r = @oci_execute($stid);
        if(!$r){
            echo "কারিগরি সমসার কারনে আপনার জিজ্ঞাসাটি জমা হয়নি। আবার চেষ্টা করুন। <br> <a href='index.html'><button class='btn btn-warning'>Back</button></a> ";
        }else{
            echo "আপনার জিজ্ঞাসাটি সফলভাবে জমা হয়েছে। শীগ্রই আপনার ইমেইলে তথ্য প্রদান করা হবে। <br> <a href='index.html'><button class='btn btn-warning'>Back</button></a> ";
        }
    }
}
catch(Exception $e)
{
    echo "কারিগরি সমসার কারনে আপনার জিজ্ঞাসাটি জমা হয়নি। আবার চেষ্টা করুন। <br> <a href='index.html'><button class='btn btn-warning'>Back</button></a> ";
}

