<?php
FloadModels(['barang']);
use models\barang;

if(!isset( $_SESSION['stokman']['cart']) ){
    $_SESSION['stokman']['cart']=[];
    $cart = [];
}else{
    $cart = $_SESSION['stokman']['cart'];
}

switch($_GET['cart']){
    case 'add':
        $cart[] = [
            'idbarang'=>$_POST['idbarang'],
            'volume'=>$_POST['volume'],
            'harga'=>str_replace(".","",$_POST['harga'])
        ];
        $_SESSION['stokman']['cart']=$cart;
        echo json_encode(['status' => '1', 'message' => "Barang ditambahkan"]);
    break;
    case 'remove':
        if( $_GET['index'] =="all"){
            $cart=[];
            echo json_encode(['status' => '1', 'message' => "Keranjang dikosongkan"]);
        }else{
            unset($cart[$_GET['index']]);
            echo json_encode(['status' => '1', 'message' => "Barang dihapus"]);
        }
        $_SESSION['stokman']['cart'] = $cart;
    break;
    case 'list':
        $result=[];
        foreach($cart as $index=>$item){
            $barang = new barang();
            $barang->filterQuery([['idbarang','=',$item['idbarang'] ]]);
            $data = $barang->query(
                "SELECT * FROM barang 
                         INNER JOIN etalase USING(idetalase)
                         " . $barang->getFilter() . "
                         ",
                $barang->getData()
            )->fetchAll()[0];
            $result[$index]=[
                'namabarang'=>$data['namabarang'],
                'volume'=>$item['volume'],
                'harga'=>$item['harga'],
            ];
        }
        echo json_encode(['status' => '1', 'message' => "",'data'=>$result]);
    break;
}