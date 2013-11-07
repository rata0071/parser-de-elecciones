<?php

$votos = file('votos_cordoba_izquierda2.csv');

array_shift($votos); // Sacamos la cabecera

$i = 0;

$t_nul = $t_izq = $t_tot = 0;

$h_nul = array();
$h_izq = array();

foreach ( $votos as $mesa ) {
	$i++;
	$datos = explode(',',$mesa);

	$nul = $datos[4] + $datos[5] + $datos[6];
	$izq = $datos[7];
	$tot = $datos[8];

	if ( $tot > 0) {
		$tot = trim($tot);
		$t_nul += $nul;
		$t_izq += $izq;
		$t_tot += $tot;
		@$h_izq[ round( ($izq * 100) / $tot) ]++;

		if ( $tot > 300 ) {
			$url = "http://www.resultados.gob.ar/telegramas/".$datos[0].'/'.$datos[1].'/'.$datos[2].'/'.$datos[0].$datos[1].$datos[2];
			if ( strlen($datos[2]) == 4 ) {
				$url .= '_';
			}
			$url .= $datos[3].'.htm';
			echo $url."    $tot\n\n"; 
		}

	}
}
// echo 'Nulos '.($t_nul / $i).' Izq '.($t_izq / $i);
