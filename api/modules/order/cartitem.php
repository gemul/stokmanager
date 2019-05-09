<?php
FloadModels(['barang','transaksi','mutasi']);
use models\barang;
use models\transaksi;
use models\mutasi;

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
    case 'save':
        // validasi
        if( 
            !isset($_POST['jenis']) ||
            empty($_POST['jenis']) ||
            !( $_POST['jenis'] == 1 || $_POST['jenis'] == 2 ) ||
            !isset($_POST['subject']) ||
            empty($_POST['subject']) 
        ){
            echo json_encode(['status' => '2', 'message' => "Data tidak lengkap"]);
            return false;
        }
        $waktu= date("Y-m-d H:i:s");
        //save transaksi
        $transaksi = new transaksi();
        $qTransaksi=$transaksi->insert([
            'jenistransaksi' => $_POST['jenis'],
            'subject' => $_POST['subject'],
            'note' => $_POST['note'],
            'waktutransaksi' => $waktu,
        ]);
        $idTransaksi= $qTransaksi->lastInsertId();

        //save mutasi
        $mutasi = new mutasi();
        $result = [];
        $nominal=0;
        foreach ($cart as $index => $item) {
            // data barang
            $barang = new barang();
            $barang->filterQuery([['idbarang', '=', $item['idbarang']]]);
            $data = $barang->query(
                "SELECT * FROM barang 
                         INNER JOIN etalase USING(idetalase)
                         " . $barang->getFilter() . "
                         ",
                $barang->getData()
            )->fetchAll()[0];
            $result[$index] = [
                'namabarang' => $data['namabarang'],
                'volume' => $item['volume'],
                'harga' => $item['harga'],
            ];

            //mutasi
            $qtyMasuk="NULL";
            $qtyKeluar="NULL";
            $nomMasuk="NULL";
            $nomKeluar="NULL";
            if( $_POST['jenis'] == 1){
                //masuk
                $qtyMasuk = $item['volume'];
                $nomMasuk = $item['volume'] * $item['harga'];
            }else{
                //keluar
                $qtyKeluar = $item['volume'];
                $nomKeluar = $item['volume'] * $item['harga'];
            }
            $qMutasi = $mutasi->insert([
                'idtransaksi' => $idTransaksi,
                'idbarang' => $data['idbarang'],
                'waktu' => $waktu,
                'jenis' => $_POST['jenis'],
                'masuk' => $qtyMasuk,
                'nominalmasuk' => $nomMasuk,
                'keluar' => $qtyKeluar,
                'nominalkeluar' => $nomKeluar,
            ]);
        }
        echo json_encode(['status' => '1', 'message' => "", 'data' => $result]);
    break;
}