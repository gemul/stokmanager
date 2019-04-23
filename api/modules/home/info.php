<?php
$data=Array(
    'is_login'=>false,
    'site_name'=>'',
    'shop_name'=>'',
    'user_name'=>'',
);

if (isset( $_SESSION['stokman']) && $_SESSION['stokman']['login'] == "admin") {
    $data['is_login']=true;
    $data['site_name']=SITE_NAME;
    $data['shop_name']=SHOP_NAME;
    $data['user_name']= $_SESSION['stokman']['username'];
}else{
    //nothing
}
echo json_encode($data);