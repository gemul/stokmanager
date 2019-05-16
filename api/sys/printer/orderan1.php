<?php
require __DIR__ . '/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

define('PRINTER_NAME', "\\\\localhost\\GP58printer");
$connector = new FilePrintConnector(PRINTER_NAME);
$printer = new Printer($connector);

$MAXLENGTH = 23;
$MAXLENGTHB = 33;

//PRINT
//staplemark
// $img = EscposImage::load(__DIR__ . "/logo/staplemark.png", false);
// $printer->bitImage($img);

$printer->feed();
//logo
$img = EscposImage::load(__DIR__ . "/logo/gemastore_01.png", false);
$printer->bitImage($img);
//A---------------01234567890123456789012345678912---
//formatting

$printer->selectPrintMode(Printer::MODE_FONT_A);
//A-------------01234567890123456789012345678912---
$printer->feed();
$printer->setEmphasis(true);
$printer->text("         STRUK PEMBELIAN        \n");
$printer->setEmphasis(false);
$printer->text("================================\n");
$printer->setEmphasis(true);
$printer->text("RFID READER MIFARE RC522        \n");
$printer->setEmphasis(false);
$printer->text("Rp.    35.000 x1   Rp.    35.000\n");
$printer->setEmphasis(true);
$printer->text("LCD Display 1602                \n");
$printer->setEmphasis(false);
$printer->text("Rp.    27.000 x1   Rp.    27.000\n");
$printer->setEmphasis(true);
$printer->text("RELAY MODULE 1 CH 5V-10A        \n");
$printer->setEmphasis(false);
$printer->text("Rp.    14.000 x1   Rp.    14.000\n");
$printer->setEmphasis(true);
$printer->feed();
$printer->text("TOTAL              Rp.    76.000\n");
$printer->setEmphasis(false);
$printer->feed();
$printer->text("================================\n");
$printer->text("Issued                14-05-2019\n");
$printer->text("================================\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->selectPrintMode(Printer::MODE_FONT_B);
$printer->text("Simpanlah struk ini sebagai\n");
$printer->text("bukti pembayaran Anda.\n");
$printer->text("Terima Kasih.\n");
$printer->selectPrintMode(Printer::MODE_FONT_A);
//feed
$printer->feed();

//barcode
$printer->setBarcodeHeight(80);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->barcode("051400000104", Printer::BARCODE_JAN13);
$printer->selectPrintMode(Printer::MODE_FONT_B);
$printer->text("TrxID:051400000104\n");
$printer->selectPrintMode(Printer::MODE_FONT_A);
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->feed();

$printer->feed();
$printer->feed();
$printer->cut();
$printer->close();
