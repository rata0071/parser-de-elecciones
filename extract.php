<?php

function find_all_files($dir)
{
    $root = scandir($dir);
    foreach($root as $value)
    {
        if($value === '.' || $value === '..') {continue;}
        if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;}
        foreach(find_all_files("$dir/$value") as $value)
        {
            $result[]=$value;
        }
    }
    return $result;
}

function mostrar_ambito( $ambito, $html, $impug ) {

	$clase = array(1=>'colorborde_colsn',2=>'colorborde_coldn',3=>'colorborde_colsp',4=>'colorborde_coldp');

	$pt1 = $html->find('div.pt1 > td.'.$clase[$ambito]);

	if ( isset($pt1[0]) ) {
		echo $pt1[0]->plaintext.','; // Nulos
		echo $pt1[1]->plaintext.','; // Blanco
		echo $pt1[2]->plaintext.','; // Recurridos

		$total = $pt1[0]->plaintext + $pt1[1]->plaintext + $pt1[2]->plaintext + $impug;
		$izquierda = 0;

		$rows = $html->find('#TVOTOS tbody tr');
		foreach ( $rows as $r ) {
			if ( stripos($r->children(0)->plaintext, 'OBRERO') !== false || 
				stripos($r->children(0)->plaintext, 'IZQUIERDA Y DE LOS TRABAJADORES') !== false ) {

				$izquierda = @$r->find( 'td.'.$clase[$ambito], 0)->plaintext;
			}
			
			$total += @$r->find('td.'.$clase[$ambito],0)->plaintext;
		}

		echo $izquierda.','; // Votos para la izquierda
		echo $total.','; // Total de votos x mesa

	} else {
		echo "0,0,0,0,0,";
	}
}

$files = find_all_files('www.resultados.gob.ar');

require('simplehtmldom_1_5/simple_html_dom.php');

foreach ( $files as $file ) {
	$pieces = explode('/', $file);
	$extension = pathinfo($file, PATHINFO_EXTENSION);
	if ( count($pieces) == 6 && ( $extension == 'htm' || $extension == 'html') ) {
		$html = file_get_html($file);

		echo $pieces[2].','; // Distrito
		echo $pieces[3].','; // Seccion
		echo $pieces[4].','; // Circuito
		$mesa = substr(pathinfo($file, PATHINFO_FILENAME),10); // Mesa
		echo $mesa.',';

		$pt2 = $html->find('div.pt2 > td');

		$impug = isset($pt2[0]) ? $pt2[0]->plaintext : 0; // Impugnados
		echo $impug.',';

		mostrar_ambito(1, $html, $impug );
		mostrar_ambito(2, $html, $impug );
		mostrar_ambito(3, $html, $impug );
		mostrar_ambito(4, $html, $impug );


		$url = "http://www.resultados.gob.ar/telegramas/".$pieces[2].'/'.$pieces[3].'/'.$pieces[4].'/'.$pieces[2].$pieces[3].$pieces[4];
		if ( strlen($pieces[4]) == 4 ) {
			$url .= '_';
		}
		$url .= $mesa.'.htm';
		echo $url."\n";
	}
}
