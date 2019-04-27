<?php
use models\user;
FloadModels(['user']);
$user=new user();
$login= $user->get([],[
        ['username','=',$_POST['username']],
        ['password','=',md5($_POST['password'])]
    ]
);

if(count($login)>=1){
    $_SESSION['stokman']['login'] = "admin";
    $_SESSION['stokman']['iduser'] = $login[0]['iduser'];
    $_SESSION['stokman']['hak'] = $login[0]['hak'];
    $_SESSION['stokman']['username'] = $login[0]['username'];
    echo json_encode(['status'=>'1',"message"=>"login success"]);  
}else{
    echo json_encode(['status'=>'nomatch',"message"=>"username/password does not match"]);
}