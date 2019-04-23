<?php
FloadModels(['user']);
$user=new user();
$login= $user->get([
        ['username','=',$_POST['username']],
        ['password','=',md5($_POST['password'])]
    ]
);

if(count($login)>=1){
    $_SESSION['login'] = "admin";
    echo json_encode(['status'=>'1',"message"=>"login success"]);  
}else{
    echo json_encode(['status'=>'nomatch',"message"=>"username/password does not match"]);
}