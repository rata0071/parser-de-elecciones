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


$files = find_all_files('./www.resultados.gob.ar');

require('simplehtmldom_1_5/simple_html_dom.php');

foreach ( $files as $file ) {
	$pieces = explode('/', $file);
	if ( count($pieces) == 7 && pathinfo($file, PATHINFO_EXTENSION) == 'html' ) {
		$html = file_get_html($file);

		echo $pieces[3].','; // Distrito
		echo $pieces[4].','; // Seccion
		echo $pieces[5].','; // Circuito
		echo substr(pathinfo($file, PATHINFO_FILENAME),10).','; // Mesa

		$pt1 = $html->find('div.pt1 > td');

		if ( isset($pt1[0]) ) {
			echo $pt1[0]->plaintext.','; // Nulos
			echo $pt1[1]->plaintext.','; // Blanco
			echo $pt1[2]->plaintext.','; // Recurridos

			$total = $pt1[0]->plaintext + $pt1[1]->plaintext + $pt1[2]->plaintext;
			$izquierda = 0;

			$rows = $html->find('#TVOTOS tbody tr');
			foreach ( $rows as $r ) {
				if ( stripos($r->children(0)->plaintext, 'IZQUIERDA Y DE LOS TRABAJADORES') !== false ) {
					$izquierda = $r->children(1)->plaintext;
				}
				$total += $r->children(1)->plaintext;
			}

			echo $izquierda.','; // Votos para la izquierda
			echo $total."\n"; // Total de votos x mesa
		} else {
			echo "0,0,0,0,0\n";
		}
	}
}
