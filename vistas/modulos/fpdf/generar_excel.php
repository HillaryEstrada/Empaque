<?php

// Incluir la biblioteca PHPExcel
require_once '../PHPExcel/Classes/PHPExcel.php';

// Recibir los parámetros por GET
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';
$CodigoCuentaContable = isset($_GET['CodigoCuentaContable']) ? $_GET['CodigoCuentaContable'] : '';
$id_etiqueta_analitica = isset($_GET['id_etiqueta_analitica']) ? $_GET['id_etiqueta_analitica'] : '';
$id_cuenta_analitica = isset($_GET['id_cuenta_analitica']) ? $_GET['id_cuenta_analitica'] : '';

// Construir la consulta con las condiciones WHERE si hay parámetros
$query = "SELECT i.*, 
            p.nombre AS proyecto, 
            c.CuentaContable AS CodigoCuentaContable_nombre, 
            d.nombre AS etiqueta_analitica_nombre, 
            cu.nombre AS cuenta_analitica_nombre, 
            a.nombre AS Area_nombre, IdPresupuestoGasto id, 
            i.Fecha,
            i.CodigoCuentaContable, 
            i.id_etiqueta_analitica, 
            i.id_cuenta_analitica, 
            i.Area, 
            i.id_proyecto_inversion, 
            CAST(i.importe as numeric(18,2)) AS Importe
            FROM presupuestoGastos i
            INNER JOIN CuentasContables c ON c.CodigoCuentaContable = i.CodigoCuentaContable
            INNER JOIN etiqueta_analitica d ON d.id_etiqueta_analitica = i.id_etiqueta_analitica
            INNER JOIN cuenta_analitica cu ON cu.id_cuenta_analitica = i.id_cuenta_analitica
            INNER JOIN area_gasto a ON a.id_area_gasto = i.Area
            INNER JOIN proyecto_inversion p ON p.id_proyecto_inversion = i.id_proyecto_inversion";

$conditions = array();

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

// Agregar las condiciones WHERE si existen
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

try {
    $pdo = include '../../db/connectdb.php';
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query($query);

    // Crear un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    // Establecer propiedades del documento
    $objPHPExcel->getProperties()->setCreator("Tu Nombre")
                                 ->setLastModifiedBy("Tu Nombre")
                                 ->setTitle("Informe de Presupuesto Gastos")
                                 ->setSubject("Presupuesto Gastos")
                                 ->setDescription("Informe generado automáticamente de presupuesto de gastos")
                                 ->setKeywords("presupuesto gastos")
                                 ->setCategory("Informe");

    // Configurar las celdas iniciales
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // Encabezados de columna
    $sheet->setCellValue('A1', 'Fecha');
    $sheet->setCellValue('B1', 'Proyecto');
    $sheet->setCellValue('C1', 'Cuenta');
    $sheet->setCellValue('D1', 'Etiqueta');
    $sheet->setCellValue('E1', 'Área');
    $sheet->setCellValue('F1', 'Cuenta analítica');
    $sheet->setCellValue('G1', 'Importe');

    // Establecer estilo para los encabezados
    $styleArray = array(
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
            ),
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F0F0F0'),
        ),
    );
    $sheet->getStyle('A1:G1')->applyFromArray($styleArray);

    // Iterar sobre los resultados y escribir en el archivo Excel
    $row = 2;
    while ($row_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $row, utf8_encode($row_data['Fecha']));
        $sheet->setCellValue('B' . $row, utf8_encode($row_data['proyecto']));
        $sheet->setCellValue('C' . $row, utf8_encode($row_data['CodigoCuentaContable_nombre']));
        $sheet->setCellValue('D' . $row, utf8_encode($row_data['etiqueta_analitica_nombre']));
        $sheet->setCellValue('E' . $row, utf8_encode($row_data['Area_nombre']));
        $sheet->setCellValue('F' . $row, utf8_encode($row_data['cuenta_analitica_nombre']));
        $sheet->setCellValue('G' . $row, utf8_encode($row_data['Importe']));
        $row++;
    }

    // Ajustar anchos de columna automáticamente
    foreach(range('A','G') as $columnID) {
        $sheet->getColumnDimension($columnID)
              ->setAutoSize(true);
    }

    // Guardar el archivo Excel
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Informe_Presupuesto_Gastos.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');

    exit;

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
