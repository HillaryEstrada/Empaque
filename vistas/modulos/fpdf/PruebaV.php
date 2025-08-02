<?php
require_once('./fpdf.php'); // Utilizamos require_once para asegurarnos de que el archivo se incluya solo una vez

 // Incluimos el archivo de conexión a la base de datos

class PDF extends FPDF
{
   // Cabecera de página
   function Header()
   {
      #global $conn; // Hacemos que la conexión sea global para poder usarla en esta función
      
      $this->Image('logo.png', 185, 5, 20); // Logo de la empresa, moverDerecha, moverAbajo, tamañoIMG
      $this->SetFont('Arial', 'B', 19); // Tipo de fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(45); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); // Color
      // Creamos una celda o fila
      $this->Cell(110, 15, utf8_decode('Categoria'), 1, 1, 'C', 0); // AnchoCelda, AltoCelda, titulo, borde(1-0), saltoLinea(1-0), posicion(L-C-R), ColorFondo(1-0)
      $this->Ln(3); // Salto de línea
      $this->SetTextColor(103); // Color

      /* TITULO DE LA TABLA */
      // Color
      $this->SetTextColor(228, 100, 0);
      $this->Cell(50); // Mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE CATEGORÍAS "), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      // Color
      $this->SetFillColor(228, 100, 0); // ColorFondo
      $this->SetTextColor(255, 255, 255); // ColorTexto
      $this->SetDrawColor(163, 163, 163); // ColorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(40, 10, utf8_decode('ID'), 1, 0, 'C', 1);
      $this->Cell(100, 10, utf8_decode('NOMBRE'), 1, 1, 'C', 1);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); // Tipo de fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); // Pie de página (número de página)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); // Tipo de fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // Pie de página (fecha de página)
   }
}

$pdf = new PDF();
$pdf->AddPage(); /* Aquí entran dos para parametros (horientazion,tamaño) V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); // Muestra la página / y total de páginas

$pdf->SetFont('Arial', '', 12);
$conn = include '../../db/connectdb.php';
// Realizamos la consulta a la base de datos
$query = 'SELECT * FROM ca_categoria';
$result = $conn->query($query);

// Iteramos sobre los resultados y los mostramos en el PDF
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(40, 10, $row['id_categoria'], 1, 0, 'C', 0); // ID
    $pdf->Cell(100, 10, utf8_decode($row['nombre']), 1, 1, 'C', 0); // Nombre
}

$pdf->Output('Reporte.pdf', 'I'); // NombreDescarga, Visor(I->visualizar - D->descargar)
?>
