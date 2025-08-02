<?php

require 'fpdf.php';

// Recibir los parámetros por GET
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';
$CodigoCuentaContable = isset($_GET['CodigoCuentaContable']) ? $_GET['CodigoCuentaContable'] : '';
$id_etiqueta_analitica = isset($_GET['id_etiqueta_analitica']) ? $_GET['id_etiqueta_analitica'] : '';

$id_cuenta_analitica = isset($_GET['id_cuenta_analitica']) ? $_GET['id_cuenta_analitica'] : '';
$id_grupo_gasto = isset($_GET['id_grupo_gasto']) ? $_GET['id_grupo_gasto'] : '';
$id_etapa = isset($_GET['id_etapa']) ? $_GET['id_etapa'] : '';
$id_proyecto_inversion = isset($_GET['id_proyecto_inversion']) ? $_GET['id_proyecto_inversion'] : '';

$id_ca_usuario = isset($_GET['id_ca_usuario']) ? $_GET['id_ca_usuario'] : '';
$id_area_gasto2 = isset($_GET['id_area_gasto']) ? $_GET['id_area_gasto'] : '';





session_start();
// Verificar si el usuario tiene un área asignada
$id_area_gasto = intval($_SESSION['id_area_gasto']);

// Construir la consulta con las condiciones WHERE si hay parámetros
$query = "SELECT i.*, 
                p.nombre AS proyecto, 
                CONCAT(c.CodigoCuentaContable,' - ',c.CuentaContable) AS CodigoCuentaContable_nombre, 
                d.nombre AS etiqueta_analitica_nombre, 
                cu.nombre AS cuenta_analitica_nombre, 
                g.nombre AS grupo_gasto,
                a.nombre AS Area_nombre, 
                IdPresupuestoGasto AS id,  
                CAST(i.Importe AS numeric(18,2)) AS Importe,
                CAST(i.Cantidad AS numeric(18,2)) AS Cantidad,
                COUNT(*) OVER() AS count_rows
                    FROM presupuestoGastos i
                    INNER JOIN CuentasContables c ON c.CodigoCuentaContable = i.CodigoCuentaContable
                    LEFT JOIN etiqueta_analitica d ON d.id_etiqueta_analitica = i.id_etiqueta_analitica
                    LEFT JOIN cuenta_analitica cu ON cu.id_cuenta_analitica = i.id_cuenta_analitica
                    LEFT JOIN area_gasto a ON a.id_area_gasto = cu.id_area_gasto
                    LEFT JOIN proyecto_inversion p ON p.id_proyecto_inversion = i.id_proyecto_inversion
                    LEFT JOIN grupo_gasto g ON g.id_grupo_gasto = c.id_grupo_gasto
                    LEFT JOIN etapas e ON e.id_etapa = i.id_etapa
                    LEFT JOIN ca_usuario u ON u.id_ca_usuario = i.id_ca_usuario";
$conditions = array();

if ($id_area_gasto < 1) {
    // Solo limitar por usuario si el área no está asignada
    $conditions[] = "i.id_ca_usuario = " . intval($_SESSION['id_ca_usuario']);
}

// Agregar filtros basados en parámetros GET
if (!empty($fechaInicio)) {
    $conditions[] = "i.Fecha >= '$fechaInicio'";
}

if (!empty($fechaFin)) {
    $conditions[] = "i.Fecha <= '$fechaFin'";
}

if (!empty($CodigoCuentaContable)) {
    $conditions[] = "i.CodigoCuentaContable = '$CodigoCuentaContable'";
}

if (!empty($id_etiqueta_analitica)) {
    $conditions[] = "i.id_etiqueta_analitica = '$id_etiqueta_analitica'";
}

if (!empty($id_cuenta_analitica)) {
    $conditions[] = "i.id_cuenta_analitica = '$id_cuenta_analitica'";
}

if (!empty($id_grupo_gasto)) {
    $conditions[] = "c.id_grupo_gasto = '$id_grupo_gasto'";
}
if (!empty($id_etapa)) {
    $conditions[] = "e.id_etapa = '$id_etapa'";
}
if (!empty($id_proyecto_inversion)) {
    $conditions[] = "i.id_proyecto_inversion = '$id_proyecto_inversion'";
}
if (!empty($id_ca_usuario)) {
    $conditions[] = "i.id_ca_usuario = '$id_ca_usuario'";
}
if (!empty($id_area_gasto2)) {
    $conditions[] = "cu.id_area_gasto = '$id_area_gasto2'";
}



// Agregar las condiciones WHERE si existen
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}
#var_dump($query);exit;
class PDF extends FPDF {

    // Cabecera de página
    function Header() {
        $this->SetLineWidth(0.5); // Ancho de la línea
        $this->SetDrawColor(128, 128, 128); // Color de la línea (negro)
        $this->Line(10, 30, 280, 30); // Línea horizontal

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(149, 149, 149); // Color de texto (azul)

        $this->Image('img/norte.jpeg', 7, 10, 50); // Logo
        $this->setXY(60, 15);

        $this->setXY(205, 8);
        $this->Cell(100, 8, mb_convert_encoding('INDUSTRIAL LAS NORTEÑAS','Windows-1252'), 0, 1, 'C', 0);

        $this->setXY(266, 13);
        $this->Cell(10, 8, mb_convert_encoding('Platino 96','Windows-1252'), 0, 1, 'C', 0);

        $this->setXY(215, 18);
        $this->Cell(100, 8, mb_convert_encoding('63173 Tepic, NAY','Windows-1252'), 0, 1, 'C', 0);

        $this->setXY(142, 23);
        $this->Cell(263, 8, mb_convert_encoding('México','Windows-1252'), 0, 1, 'C', 0);
        $this->Ln(20);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(170, 10, 'Todos los derechos reservados', 0, 0, 'C', 0);
        $this->Cell(25, 10, mb_convert_encoding('Página ','Windows-1252') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Método para adaptar las celdas
    function MultiCellBordered($w, $h, $txt) {
        $lines = $this->MultiCell($w, $h, $txt, 0, 'C');
        return $lines;
    }
    
    // Método para establecer los anchos de las celdas
    function SetWidths($widths) {
        @$this->widths = $widths;
    }

    // Método para establecer las alineaciones de las celdas
    function SetAligns($aligns) {
        @$this->aligns = $aligns;
    }

    // Método para generar una fila de celdas
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
            $this->SetXY($this->lMargin, $this->tMargin);
            return true;
        }
        return false;
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
// Agregar un margen de 10mm en todas las direcciones
$pdf->SetMargins(10, 40, 10);
$pdf->SetAutoPageBreak(true, 20);

// Encabezado de tabla
$pdf->SetXY(10, 35);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 8, 'Fecha', 1, 0, 'C', 0);
$pdf->Cell(50, 8, 'Proyecto', 1, 0, 'C', 0);
$pdf->Cell(30, 8, 'Concepto', 1, 0, 'C', 0);
$pdf->Cell(30, 8, 'Cuenta contable', 1, 0, 'C', 0);
$pdf->Cell(30, 8, 'Grupo Gasto', 1, 0, 'C', 0);
$pdf->Cell(30, 8, 'Zona', 1, 0, 'C', 0);
$pdf->Cell(30, 8, 'Area', 1, 0, 'C', 0);
$pdf->Cell(30, 8, 'Departamento', 1, 0, 'C', 0);
$pdf->Cell(25, 8, 'Importe', 1, 1, 'C', 0);

$pdf->SetFillColor(233, 229, 235);
$pdf->SetDrawColor(61, 61, 61);
$pdf->SetFont('Arial', '', 10);

try {
    $pdo = include '../../db/connectdb.php';
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    #var_dump($query);
    exit;
    $stmt = $pdo->query($query);

    //var_dump($query);

    // Establecer los anchos y alineaciones de las celdas
    $pdf->SetWidths(array(25, 50, 30, 30, 30, 30, 30, 30, 25));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Row(array(
            $row['Fecha'],
            $row['proyecto'],
            $row['Concepto'],
            $row['CodigoCuentaContable_nombre'],
            $row['grupo_gasto'],
            $row['etiqueta_analitica_nombre'],
            $row['Area_nombre'],
            $row['cuenta_analitica_nombre'],
            $row['Importe']
        ));
    }

    $pdo = null;

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$pdf->Output();
?>
