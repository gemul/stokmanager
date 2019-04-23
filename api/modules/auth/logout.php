<?php
unset($_SESSION['login']);
echo json_encode(['status'=>'1',"message"=>"logout success"]);  