<?php
require __DIR__ . '/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
$connector = new FilePrintConnector("\\\\localhost\\GP58printer");
$printer = new Printer($connector);

//DATA
$data=Array(
	'kode'=>"000012321232",
	'nama'=>"HARTINI",
	'berat'=>5.5,
	'waktu'=>strtotime("2019-06-01 09:36:00"),
	'jenis'=>"Cuci Seterika",
	'harga'=>4000,
	'lunas'=>TRUE,
	);

//PRINT
//logo
$img = EscposImage::load("logo.png",false);
$printer -> bitImage($img);
//A---------------01234567890123456789012345678912---
//title
$printer -> feed();
$printer -> setEmphasis(TRUE);
$printer -> text("     STRUK PENYERAHAN CUCIAN    \n");
$printer -> setEmphasis(FALSE);
$printer -> feed();
//formatting
$nama=(strlen($data['nama'])>24)? substr($data['nama'],0,23)."." : $data['nama'];
$berat=$data['berat'] . " Kg";
$harga="Rp. " . number_format($data['harga'],0,",",".") . " /Kg";
$total="Rp. " . number_format($data['harga'] * $data['berat'],0,",",".");
$waktu=date("d/m/Y H:i",$data['waktu']);

//print detail
$printer -> text("Nama  : ".str_pad($nama,24," ",STR_PAD_LEFT)."\n");
$printer -> text("Berat : ".str_pad($berat,24," ",STR_PAD_LEFT)."\n");
$printer -> text("Waktu : ".str_pad($waktu,24," ",STR_PAD_LEFT)."\n");
$printer -> text("Jenis : ".str_pad($data['jenis'],24," ",STR_PAD_LEFT)."\n");
$printer -> text("Harga : ".str_pad($harga,24," ",STR_PAD_LEFT)."\n");
$printer -> setEmphasis(TRUE);
$printer -> text("Total : ".str_pad($total,24," ",STR_PAD_LEFT)."\n");
$printer -> setEmphasis(FALSE);
if($data['lunas']){
	$printer -> setJustification(Printer::JUSTIFY_CENTER);
	$printer -> text("LUNAS (Dibayar di muka)"."\n");
	$printer -> setJustification();
}
$printer -> feed();

//footnote
//B---------------012345678901234567890123456789012345678912---
$printer -> selectPrintMode(Printer::MODE_FONT_B);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Mohon bawa struk ini sebagai bukti ketika\n");
$printer -> text("mengambil cucian. Terima kasih atas\n");
$printer -> text("kepercayaan anda kepada kami.\n");
$printer -> selectPrintMode(Printer::MODE_FONT_A);

//barcode
$printer -> setBarcodeHeight(80);
$printer -> barcode($data['kode'], Printer::BARCODE_JAN13);
$printer -> text($data['kode']);
$printer -> feed();
//url
$printer -> selectPrintMode(Printer::MODE_FONT_B);
$printer -> text("http://348laundry.com/t/".$data['kode']."\n");
$printer -> selectPrintMode(Printer::MODE_FONT_A);

//$printer -> feed();
//$printer -> feed();
//
//$qrData="http://348laundry.com/t/".$data['kode']."";
//$printer -> setJustification(Printer::JUSTIFY_CENTER);
//$printer -> qrCode($qrData,Printer::QR_ECLEVEL_L,9,Printer::QR_MODEL_2);
//$printer -> feed();
//$printer -> feed();
//$printer -> text("".$qrData."\n");
//$printer -> setJustification();


//$printer -> feed();
$printer -> feed();
$printer -> feed();
$printer -> feed();
$printer -> cut();
$printer -> close();
