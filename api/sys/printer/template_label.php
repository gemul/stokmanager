<?php
require __DIR__ . '/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
$connector = new FilePrintConnector(PRINTER_NAME);
$printer = new Printer($connector);

$MAXLENGTH=23;

//PRINT
//staplemark
$img = EscposImage::load(__DIR__ . "/logo/staplemark.png",false);
$printer -> bitImage($img);

$printer->feed();
//logo
$img = EscposImage::load(__DIR__ . "/logo/gemastore_01.png",false);
$printer -> bitImage($img);
//A---------------01234567890123456789012345678912---
//formatting

//feed
$printer->feed();

//print detail
$printer -> text("SKU    : ".substr($data['sku'],0,$MAXLENGTH)."\n");
$printer -> text("ITEM ID: ".substr( $data['id'],0,$MAXLENGTH)."\n");
$printer -> text("NAME   : ".substr( $data['name'],0,$MAXLENGTH)."\n");
//description
$printer -> text("DESC   : \n");
$printer->selectPrintMode(Printer::MODE_FONT_B);
if( $data['desc'] ==""){
	$printer->text( "-NO DESCRIPTION-\n");
}else{
	$printer->text( $data['desc']. "\n");
}
$printer->selectPrintMode(Printer::MODE_FONT_A);

//barcode
$printer -> setBarcodeHeight(80); 
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> barcode($data['id'], Printer::BARCODE_JAN13);
$printer -> feed();

//$printer -> feed();
$printer -> feed();
$printer -> cut();
$printer -> close();
