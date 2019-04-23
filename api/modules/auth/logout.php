<?php
unset( $_SESSION['stokman']);
echo json_encode(['status'=>'1',"message"=>"logout success"]);  