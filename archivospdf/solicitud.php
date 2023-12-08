<?php
require_once("sys_root.inc.php");
// require_once("$SYS_ROOT/plugins/fpdf/fpdf.php");
require_once("$SYS_ROOT/libs/fpdf/fpdf.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
// require_once("$SYS_ROOT/php/knl/locale.inc.php");
// require_once("$SYS_ROOT/php/knl/logs.inc.php");
// require_once("$SYS_ROOT/php/knl/phpext.inc.php");
// require_once("$SYS_ROOT/php/knl/num_to_letter.inc.php");

class PDF extends FPDF {
    function Header() {
        global $a_data;
   
        //Logo
        $img_w = 25 * 1.2;
        $img_h = 18.5 * 1.2;
		$img_x = 5;
		$img_y = 8;
		// $this->Image('../dist/img/logo_cfdi.png', $img_x, $img_y, $img_w, $img_h);
		
		// color y ancho lineas
		$this->SetDrawColor(190, 190, 190);
		$this->SetLineWidth(.3);

        //empresa
		$this->SetFillColor(240, 240, 240);
        $title = $a_data['title'];
        $this->SetFont('Arial', 'B', 14);
        $this->setLine($title, 40, 8, 170, 'C', 1, 1, 1, $img_h);
        
        // FOLIO
		$folio = $a_data['Folio'];
		$this->SetFont('Arial', 'B', 9);
        $this->SetLine("Folio", 5, 33, 16, 'L');
		$this->SetFont('Arial', '', 9);
        $this->SetLine($folio, 5, 38, 40, 'L', 0, 'T', 1, 7);
		
		// Fecha
		// $fecha = $a_data['Folio'];
		$fecha = DtDbToShow($a_data['Fecha']);
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Fecha", 47, 33, 1, 'L');
		$this->SetFont('Arial', '', 9);
		$this->SetLine($fecha, 47, 38, 40, 'L', 0, 'T', 1, 7);
		
		// NombreEstacion
		$EstacionServicio = $a_data['EstacionServicio'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Estacion de Servicio", 89, 33, 1, 'L');
		$this->SetFont('Arial', '', 9);
		$this->SetLine($EstacionServicio, 89, 38, 75, 'L', 0, 'T', 1, 7);
		
		// NumEstacion
		$NoEstacion = $a_data['NoEstacion'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("No. Estacion", 166, 33, 1, 'L');
		$this->SetFont('Arial', '', 9);
		$this->SetLine($NoEstacion, 166, 38, 45, 'L', 0, 'T', 1, 7);

		// gerente estacion
		$Gerente = $a_data['Gerente'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Gerente Estacion", 5, 46, 16, 'L');       
		$this->SetFont('Arial', '', 9);
		$this->SetLine($Gerente, 5, 51, 80, 'L', 0, 'T', 1, 7);
		
		// Correo electronico
		$Email = $a_data['Email'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Correo electronico", 87, 46, 16, 'L');       
		$this->SetFont('Arial', '', 9);
		$this->SetLine($Email, 87, 51, 65, 'L', 0, 'T', 1, 7);

		// Materiales entregados
		$MatEntregado = $a_data['MatEntregado'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Materiales entregados", 154, 46, 16, 'L');       
		$this->SetFont('Arial', '', 9);
		$this->SetLine($MatEntregado, 154, 51, 56, 'L', 0, 'T', 1, 7);

		// Area que instalo/entrego
		$AreaInstaloEntrego = $a_data['AreaInstaloEntrego'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Area que instalo/entrego", 5, 60, 16, 'L');       
		$this->SetFont('Arial', '', 9);
		$this->SetLine($AreaInstaloEntrego, 5, 65, 80, 'L', 0, 'T', 1, 7);

		// Folio de remision
		$FolioRemision = $a_data['FolioRemision'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Folio de Remision", 87, 60, 16, 'L');       
		$this->SetFont('Arial', '', 9);
		$this->SetLine($FolioRemision, 87, 65, 65, 'L', 0, 'T', 1, 7);

		// Nomenclatura
		$Nomenclatura = $a_data['Nomenclatura'];
		$this->SetFont('Arial', 'B', 9);
		$this->SetLine("Nomenclatura", 154, 60, 16, 'L');       
		$this->SetFont('Arial', '', 9);
		$this->SetLine($Nomenclatura, 154, 65, 56, 'L', 0, 'T', 1, 7);

		// encabezado de columnas
		$folio = $a_data['Folio'];
		$this->SetFillColor(180, 180, 180);
		$this->SetFont('Arial', 'B', 9);
		// $this->SetLine("Partida", 5, 75, 20, 'C', 0, 'B');
		$this->SetLine("Referencia", 5, 75, 125, 'L', 0, 'B');
		$this->SetLine("Descripcion", 57, 75, 118, 'L', 0, 'B');
		$this->SetLine("Cantidad", 175, 75, 35, 'C', 0, 'B');
   
    }

    // pie de pagina (definido en TEMPLATE)
    function Footer() {
    
    }

    // imprime una campo o una linea, es una manera facil de usar "cell" (definido en TEMPLATE)
    function setLine($text, $col_x, $line_y = '', $text_width, $text_align, $ln = 0, $border = 0, $is_fill = false, $line_height = 5) {
        if ($line_y == '') {
            $this->SetX($col_x);
        } else {
            $this->SetXY($col_x, $line_y);
        }
        $this->Cell($text_width, $line_height, $text, $border, $ln, $text_align, $is_fill);
    }
    
    // imprime una campo o una linea, es una manera facil de usar "multicell" (definido en TEMPLATE)
    function setMultiLine($text, $col_x, $line_y = '', $text_width, $text_align, $border = 0, $is_fill = false, $line_height = 5) {
        if ($line_y == '') {
            $this->SetX($col_x);
        } else {
            $this->SetXY($col_x, $line_y);
        }
        $this->MultiCell($text_width, $line_height, $text, $border, $text_align, $is_fill);
    }
    
    function WordWrap(&$text, $maxwidth) {
        $text = trim($text);
        if ($text==='') {
            return 0;
        }
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line) {
            $words = preg_split('/ +/', $line);
            $width = 0;

            foreach ($words as $word) {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth) {
                    // Word is too long, we cut it
                    for($i=0; $i<strlen($word); $i++) {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if($width + $wordwidth <= $maxwidth) {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        }
                        else {
                            $width = $wordwidth;
                            $text = rtrim($text)."\n".substr($word, $i, 1);
                            $count++;
                        }
                    }
                }
                elseif($width + $wordwidth <= $maxwidth) {
                    $width += $wordwidth + $space;
                    $text .= $word.' ';
                }
                else {
                    $width = $wordwidth + $space;
                    $text = rtrim($text)."\n".$word.' ';
                    $count++;
                }
            }
            $text = rtrim($text)."\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }
}



// funcion para imprimir el reporte
function generaVentaPdf($id_solicitud, $is_show_venta = 0, $fecha_show) {
	// $pdf = new PDF('P', 'mm', 'A5');
	$pdf = new PDF('P', 'mm', 'Letter');
	$pdf->AliasNbPages();
	$pdf->AddFont('ArialNarrow','','ARIALN.php');
	$pdf->SetAutoPageBreak(15);

	//OBTIENE LOS DATOS DEL HEADER
	$qry = "SELECT t1.Folio, t1.Fecha, t2.EstacionServicio, t2.NoEstacion, CONCAT(t3.Nombre,' ',t3.ApellidoPaterno,' ',t3.ApellidoMaterno) AS Gerente, t3.Email,
			t1.MatEntregado, t1.AreaInstaloEntrego, t1.FolioRemision, t1.Nomenclatura, t1.Observaciones
			FROM solicitudes t1
			LEFT JOIN estaciones t2 ON t1.IdEstacion_fk = t2.IdEstacion
			LEFT JOIN seg_usuarios t3 ON t1.IdUsuario_fk = t3.IdUsuario
			WHERE t1.IdSolicitud = $id_solicitud";
	$a_datos_hd = DbQryToRow($qry);

	// $id_solciitud = $a_datos_hd['IdSolicitud'];
	
	//OBTIENE LOS DATOS DE LOS PRODUCTOS 
	$qry = "SELECT t2.IdPartida, t3.Referencia, t3.NombreRefaccion AS Descripcion, t2.Cantidad 
			FROM solicitudes t1 
			LEFT JOIN productos_solicitud t2 ON t1.IdSolicitud = t2.IdSolicitud
			LEFT JOIN productos t3 ON t2.IdProducto_fk = t3.IdProducto
			WHERE t1.IdSolicitud = $id_solicitud";
	$a_refacciones_dt = DbQryToArray($qry, true);
	

	global $a_data;
	$a_data = array();

	$a_data['title'] = "Orden de Compra";
	$a_data['Folio'] = $a_datos_hd['Folio'];
	$a_data['Fecha'] = $a_datos_hd['Fecha'];
	$a_data['EstacionServicio'] = $a_datos_hd['EstacionServicio'];
	$a_data['NoEstacion'] = $a_datos_hd['NoEstacion'];
	$a_data['Gerente'] =  $a_datos_hd['Gerente'];
	$a_data['Email'] = $a_datos_hd['Email'];
	$a_data['MatEntregado'] = $a_datos_hd['MatEntregado'];
	$a_data['AreaInstaloEntrego'] = $a_datos_hd['AreaInstaloEntrego'];
	$a_data['FolioRemision'] = $a_datos_hd['FolioRemision'];
	$a_data['Nomenclatura'] = $a_datos_hd['Nomenclatura'];
	$a_data['Observaciones'] =  $a_datos_hd['Observaciones'];

	// comienza pagina
	$pdf->AddPage();

	// Detalle de la factura (xml)
	// posicion de la primera linea
	$pdf->SetY(80);

	// Detalle de la factura (xml)
	$simbolo_moneda = '$';
	$clave_moneda = 'MXN';
	$subtotal_dt = 0;
	$descuento_dt = 0;
	$subtotal_desc = 0;
	$impuesto = 0;
	$impuesto2 = 0;
	$total = 0;
	$pdf->SetFont('Arial', '', 9);
	$tt_lineas = 27;
	$cont = 0;
	$pdf->SetFillColor(160, 160, 160); // tenia 240
	$pdf->SetDrawColor(100, 100, 100);
	$ln = 6;
	// echo print_r($a_refacciones_dt, true);
	// exit();
	foreach ($a_refacciones_dt as $a_line) {
		// $id_partida = $a_line['IdPartida'];
		$referencia = $a_line['Referencia'];
		$descripcion = utf8_encode($a_line['Descripcion']);
		$cantidad = $a_line['Cantidad'];
			
		$cont++;
		$is_fill = (fmod($cont, 2) == 0);
		$brd = 0;
		// IMPRIME DATOS DE LOS PRODUCTOS
		// $pdf->SetLine($id_partida, 5, '', 19, 'C', 0, $brd, $is_fill, $ln);
		$pdf->SetLine($referencia, 5, '', 50, 'l', 0, $brd, $is_fill, $ln);
		$pdf->SetLine($descripcion, 57, '', 116, 'L', 0, $brd, $is_fill, $ln);
		$pdf->SetLine($cantidad, 175, '', 35, 'C', 1, $brd, $is_fill, $ln);
		
		if ($cont > 1) {
			$pos = $pdf->GetY() - $ln;
			$pdf->Line(5, $pos, 210, $pos);
		}
		
	
	}/* END FORECH */
	
	// lineas adicionales
	// $ln = 8;
	$faltan = $tt_lineas - $cont;
	if ($faltan > 0) {
		for($i = 0; $i < $faltan; $i++) {
			$cont++;
			$is_fill = (fmod($cont, 2) == 0);
			// LINEAS DEL PDF 
			// $pdf->SetLine('', 5, '', 19, 'C', 0, $brd, $is_fill, $ln);
			$pdf->SetLine('', 5, '', 50, 'L', 0, $brd, $is_fill, $ln);
			$pdf->SetLine('', 57, '', 116, 'L', 0, $brd, $is_fill, $ln);
			$pdf->SetLine('', 175, '', 35, 'L', 1, $brd, $is_fill, $ln);
			$pos = $pdf->GetY() - $ln;
			$pdf->Line(5, $pos, 210, $pos);
		}
	}
	
	$pos_y = $pdf->GetY();

	$pdf->SetY($pos_y);

	// posicion de subtotal
	$pdf->SetDrawColor(190, 190, 190);
	$pos_subtotal = $pdf->GetY(); // 186; //100; //198; // 188
	$pdf->Line(5, $pos_subtotal, 210, $pos_subtotal);
	$subtt_top = 'T';
	$st_offset = 0;

	// $pdf->Ln(2);
	$pdf->SetFont('Arial', 'B', 9);
	
	// mensaje junto al total o subtotal
	$pos_msg = 245;
	$observaciones = $a_data['Observaciones'];
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetLine("Observaciones:", 5, $pos_msg,  20, 'L', 1);
	$pdf->SetFont('Arial', '', 9);
	$pdf->SetLine($observaciones, 5, 250, 207, 'L', 0, 'T', 1, 7);
	$fecha_footer = DtDbLgToShowLong($fecha_show, true);
	$pdf->SetLine($fecha_footer, 5, 260, 207, 'L');


	// $filename_b = str_pad(0, 6, 0, STR_PAD_LEFT);
	$fecha = FechaPersonalizada($fecha_show);
	$NoEstacion  = $a_data['NoEstacion'];
	$filename_b = $fecha.'_'.$NoEstacion;
	$filename = "$filename_b" . ".pdf";
	// las notas solo se guardan si el parametro is_show_nota = 0
	if ($is_show_venta == 1) {
		// envia salida a la pantalla
		// $pdf->Output();
		// $pdf->Output('I', 'pdf_downloads/pedido_'.$filename_b.'.pdf');	
		/* no permite repetir los archivos */
		$pdf->Output('F', '../pdf_downloads/pedido_'.$filename_b.'.pdf');
	} else {
		// guarda archivo
		$pdf->Output($filename);
		return $filename;
	}
}


// $id_venta = '1';
// $id_solicitud = 72;
// $fecha_show =  DtDbToday();
$fecha_show = $_REQUEST['fecha'];
$id_solicitud = $_REQUEST['IdSolicitud'];
$is_show = 1; // para que se muestre por pantalla (no se grabara el PDF)
generaVentaPdf($id_solicitud, $is_show, $fecha_show);
?>