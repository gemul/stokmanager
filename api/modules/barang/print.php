<?php
FloadModels(['barang']);
use models\barang;

$id = abs($_GET['id']);
if ($id == 0) {
    //invalid id
    echo json_encode(['status' => '0', 'message' => "ID not specified or invalid"]);
} else {
    //valid id
    $barang = new barang();
    $barang->filterQuery([['idbarang', '=', $id]]);
    $data = $barang->query(
        "SELECT * FROM barang 
                     INNER JOIN etalase USING(idetalase)
                     " . $barang->getFilter() . "
                     ",
        $barang->getData()
    )->fetchAll();
    $count = count($data);
    $num = (isset($_GET['num']))?$_GET['num']:1;
    if($count>=1){
        $data=$data[0];
        $printData = array(
            'sku' => strtoupper( $data['kodebarang'])."".str_pad($data['idetalase'], 3, "0", STR_PAD_LEFT) . "" . str_pad($data['idbarang'], 5, "0", STR_PAD_LEFT),
            'name' => $data['namabarang'],
            'desc' => $data['catatan'],
            'bcid' => date('m').date('d')."".str_pad($data['idetalase' ], 3, "0", STR_PAD_LEFT) . "" . str_pad($data['idbarang'], 5, "0", STR_PAD_LEFT),
        );
        //PRINT
        for($i=1;$i<=$num;$i++){
            FprintLabel($printData);
        }
    }
    echo json_encode(['status' => '1', 'message' => "success. data printed $num times", 'data' => $printData]);
}
