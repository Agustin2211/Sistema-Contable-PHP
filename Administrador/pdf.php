<?php

require('../Administrador/FPDF/fpdf.php');

date_default_timezone_set('America/Argentina/Buenos_Aires');

$message = '';

class PDF extends FPDF  {
// Cabecera de página
    function Header(){
        // Arial bold 15
        $this->SetFont('Arial','B',10);
        // Título
        $this->Cell(130);
        $this->Cell(0,0, "RECIBO DE HABERES Ley no 20.744");
        $this->Ln(5);
        $this->Cell(0,0,'Estudio Contable "D&J"');
        //Corrernos para abajo
        $this->Ln(5);
        $this->Cell(0,0,'Galvez 2564');
        $this->Ln(5);
        $this->Cell(0,0, "Rosario");
        $this->Ln(5);
        $this->Cell(0,0, "CUIT: 20-40416523-0");
        // Salto de línea
        $this->Ln(10);
    }

// Pie de página
    function Footer(){
    // Posición: a 1,5 cm del final
        $this->SetY(-15);
    // Arial italic 8
        $this->SetFont('Arial','I',8);
    // Número de página
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }   
}

?>                                                                                                       
