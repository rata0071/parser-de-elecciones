<?php

$votos = file('votos_cordoba_izquierda.csv');

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
		$t_nul += $nul;
		$t_izq += $izq;
		$t_tot += $tot;
		$h_izq[ round( ($izq * 100) / $tot) ]++;

		if ( ( $nul * 100 / $tot ) > 6 && ($izq * 100 / $tot) < 15 && $tot > 50 ) {
			echo $mesa;
		}
	}
}
echo 'Nulos '.($t_nul / $i).' Izq '.($t_izq / $i);
