<?php
FloadModels (['images','barang']);
use models\images;
use models\barang;

if (0 < $_FILES['file']['error']) {
    echo json_encode(['status'=>'err','message'=>'Error: ' . $_FILES['file']['error'] ]);
} else {
    //barang
    $barang = new barang();
    $barang->filterQuery([['idbarang', '=', $_POST['idbarang']]]);
    $data = $barang->query(
        "SELECT * FROM barang 
                     INNER JOIN etalase USING(idetalase)
                     " . $barang->getFilter() . "
                     ",
        $barang->getData()
    )->fetchAll();

    if(count($data)>0){
        $dataBarang=$data[0];

        //images
        $images = new images();
        $ins = $images->insert([
            'idbarang' => $_POST['idbarang'],
            'label' => $_POST['label'],
        ]);
        if ($ins->lastInsertId()) {
            //filename
            $filename= "SKU" . str_pad($dataBarang['idetalase'], 3, "0", STR_PAD_LEFT) . "".str_pad($_POST['idbarang'],5,"0",STR_PAD_LEFT)."IMG" . str_pad( $ins->lastInsertId() , 4, "0", STR_PAD_LEFT).".jpg";
            //update filename
            $images->update(
                [
                    'filename' => $filename,
                ],
                [['idimages', '=', $ins->lastInsertId() ]]
            );
            //move uploaded file
            move_uploaded_file($_FILES['file']['tmp_name'], '../contents/product/' . $filename);
    
            echo json_encode(['status' => '1', 'message' => "Gambar ditambahkan"]);
    
        } else {
            echo json_encode(['status' => '2', 'message' => "Kesalahan saat menyimpan"]);
        }
    }else{
        echo json_encode(['status' => '2', 'message' => "Barang tidak ditemukan"]);
    }

}

