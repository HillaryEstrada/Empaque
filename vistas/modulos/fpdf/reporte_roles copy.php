<?php
require 'fpdf.php';
require_once '../../../modelo/conexion.php';

$pdo = Conexion::conectar();

// Consulta de roles activos
$query = "SELECT pk_rol, nombre, descripcion, estado, fecha, hora FROM rol WHERE estado = 1";
$stmt = $pdo->query($query);

// Clase personalizada FPDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Catalogo de Roles', 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 8, 'ID', 1, 0, 'C');
        $this->Cell(40, 8, 'Nombre', 1, 0, 'C');
        $this->Cell(80, 8, 'Descripcion', 1, 0, 'C');
        $this->Cell(25, 8, 'Fecha', 1, 0, 'C');
        $this->Cell(25, 8, 'Hora', 1, 1, 'C');
        
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Escribir cada fila del resultado
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(20, 8, $row['pk_rol'], 1, 0, 'C');
    $pdf->Cell(40, 8, mb_convert_encoding($row['nombre'], 'Windows-1252'), 1, 0, 'L');
    $pdf->Cell(80, 8, mb_convert_encoding($row['descripcion'], 'Windows-1252'), 1, 0, 'L');
    $pdf->Cell(25, 8, $row['fecha'], 1, 0, 'C');
    $pdf->Cell(25, 8, $row['hora'], 1, 1, 'C');
}

$pdf->Output();
?>
