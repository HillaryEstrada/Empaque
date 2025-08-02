<?php
require 'fpdf.php';
require_once '../../../modelo/conexion.php';

$pdo = Conexion::conectar();

// Consulta de roles activos
$query = "SELECT pk_rol, nombre, descripcion, estado, fecha, hora FROM rol WHERE estado = 1";
$stmt = $pdo->query($query);

// Clase personalizada FPDF con formato profesional
class PDF extends FPDF {
    
    // Cabecera de página
    function Header() {
        $this->SetLineWidth(0.5); // Ancho de la línea
        $this->SetDrawColor(128, 128, 128); // Color de la línea (gris)
        $this->Line(10, 30, 200, 30); // Línea horizontal ajustada para A4 vertical

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(149, 149, 149); // Color de texto (gris)

        // Logo (ajustar ruta según tu estructura de carpetas)
        $this->Image('img/emex.jpeg', 7, 5, 40); // Logo
        $this->setXY(60, 15);

        // Información de la empresa
        $this->setXY(125, 8);
        $this->Cell(100, 8, mb_convert_encoding('EMPACADORA EMEX','Windows-1252'), 0, 1, 'C', 0);

        $this->setXY(155, 13);
        $this->Cell(10, 8, mb_convert_encoding('Morelos 16','Windows-1252'), 0, 1, 'C', 0);

        $this->setXY(135, 18);
        $this->Cell(100, 8, mb_convert_encoding('63370 Santiago, NAY','Windows-1252'), 0, 1, 'C', 0);

        $this->setXY(62, 23);
        $this->Cell(263, 8, mb_convert_encoding('México','Windows-1252'), 0, 1, 'C', 0);
        
        $this->Ln(15);
        
        // Título del reporte
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 0); // Color negro para el título
        $this->Cell(0, 10, mb_convert_encoding('CATÁLOGO DE ROLES','Windows-1252'), 0, 1, 'C');
        $this->Ln(5);

        // Encabezados de tabla
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(233, 229, 235);
        $this->SetDrawColor(61, 61, 61);
        $this->Cell(20, 8, 'ID', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'Nombre', 1, 0, 'C', 1);
        $this->Cell(80, 8, mb_convert_encoding('Descripción','Windows-1252'), 1, 0, 'C', 1);
        $this->Cell(25, 8, 'Fecha', 1, 0, 'C', 1);
        $this->Cell(25, 8, 'Hora', 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(140, 10, 'Todos los derechos reservados', 0, 0, 'C', 0);
        $this->Cell(25, 10, mb_convert_encoding('Página ','Windows-1252') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Método para establecer los anchos de las celdas
    function SetWidths($widths) {
        $this->widths = $widths;
    }

    // Método para establecer las alineaciones de las celdas
    function SetAligns($aligns) {
        $this->aligns = $aligns;
    }

    // Método para generar una fila de celdas con altura automática
    function Row($data) {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = is_null($data[$i]) ? '' : mb_convert_encoding($data[$i],'Windows-1252');
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = 6 * $nb; // Ajusta el alto de la fila según el contenido
        $this->CheckPageBreak($h); 
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 6, $data[$i], 0, $a); // Ajusta el alto de la celda
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    // Método para obtener el número de líneas ocupadas por un MultiCell de ancho w
    function NbLines($w, $txt) {
        $cw = $this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
    
    // Método para verificar si es necesario un salto de página
    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            return true;
        }
        return false;
    }
}

// Crear el PDF
$pdf = new PDF('P', 'mm', 'A4'); // Orientación vertical para el catálogo de roles
$pdf->AliasNbPages();
$pdf->AddPage();

// Agregar márgenes
$pdf->SetMargins(10, 40, 10);
$pdf->SetAutoPageBreak(true, 20);

// Configurar fuente y colores para el contenido
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(233, 229, 235);
$pdf->SetDrawColor(61, 61, 61);

// Establecer los anchos y alineaciones de las celdas
$pdf->SetWidths(array(20, 40, 80, 25, 25));
$pdf->SetAligns(array('C', 'L', 'L', 'C', 'C'));

// Escribir cada fila del resultado usando el método Row para altura automática
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Row(array(
        $row['pk_rol'],
        $row['nombre'],
        $row['descripcion'],
        $row['fecha'],
        $row['hora']
    ));
}

$pdf->Output();
?>