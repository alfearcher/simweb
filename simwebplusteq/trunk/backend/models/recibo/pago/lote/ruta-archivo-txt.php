<?php
	// Para la ruta en linux
	// /home/banesco/
	// /home/bod/
	// /home/caroni/
	// Para windows
	// 12/lote/archivo/bod/
	// 16/lote/archivo/banesco/
	// 14/lote/archivo/caroni/
	$rutaBase = dirname(__DIR__);

	// Windows
	return [
		12 => $rutaBase . '/lote/archivo/bod/',
		14 => $rutaBase . '/lote/archivo/caroni/',
		16 => $rutaBase . '/lote/archivo/banesco/',
	];

	//Linux
	// $rutaBase = '';
	// return [
	// 	12 => $rutaBase . '/home/bod',
	// 	14 => $rutaBase . '/home/bcocaroni',
	// 	16 => $rutaBase . '/home/banesco',
	// ];
 ?>