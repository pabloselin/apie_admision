<?php

function fpost_cursequi($curso, $otro = NULL) {
	//transforma los valores de curso en valores legibles
	switch($curso) {
		case('pg'):
			$lcurso = 'Playgroup';
		break;
		case('pk'):
			$lcurso = 'Pre-Kinder';
		break;
		case('k'):
			$lcurso = 'Kinder';
		break;
		case('1'):
			$lcurso = '1º Básico';
		break;
		case('2'):
			$lcurso = '2º Básico';
		break;
		case('3'):
			$lcurso = '3º Básico';
		break;
		case('4'):
			$lcurso = '4º Básico';
		break;
		case('5'):
			$lcurso = '5º Básico';
		break;
		case('6'):
			$lcurso = '6º Básico';
		break;
		case('7'):
			$lcurso = '7º Básico';
		break;
		case('8'):
			$lcurso = '8º Básico';
		break;
		case('9'):
			$lcurso = 'Iº Medio';
		break;
		case('10'):
			$lcurso = 'IIº Medio';
		break;
		case('jardin'):
			$lcurso = 'Jardín';
		break;
		default:
			$lcurso = $curso;
		break;	
	}
	return $lcurso;
}

function fpost_parseyear($year) {
	return $year;
}

function fpost_formatjornada( $jornada ) {
	/**
	 * Devuelve la jornada con formato
	 */
	if( $jornada == 'manana' ) {
		return 'Mañana';
	} elseif( $jornada == 'tarde') {
		return 'Tarde';
	} elseif( $jornada == 'cualquiera') {
		return 'Cualquiera';
	} else {
		return $jornada;
	}

}

function fpost_formatrut($r = false){
    if((!$r) or (is_array($r)))
        return false; /* Hace falta el rut */
 
    if(!$r = preg_replace('|[^0-9kK]|i', '', $r))
        return false; /* Era código basura */
 
    if(!((strlen($r) == 8) or (strlen($r) == 9)))
        return false; /* La cantidad de carácteres no es válida. */
 
    $v = strtoupper(substr($r, -1));
    if(!$r = substr($r, 0, -1))
        return false;
 
    if(!((int)$r > 0))
        return false; /* No es un valor numérico */
 
    $x = 2; $s = 0;
    for($i = (strlen($r) - 1); $i >= 0; $i--){
        if($x > 7)
            $x = 2;
        $s += ($r[$i] * $x);
        $x++;
    }
    $dv=11-($s % 11);
    if($dv == 10)
        $dv = 'K';
    if($dv == 11)
        $dv = '0';
    if($dv == $v)
        return number_format($r, 0, '', '.').'-'.$v; /* Formatea el RUT */
    return false;
}