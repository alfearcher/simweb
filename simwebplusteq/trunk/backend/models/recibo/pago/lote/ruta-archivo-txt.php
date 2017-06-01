<?php
	// Para la ruta en linux
	// /home/banesco
	// /home/bod
	// Para windows
	// /lote/archivo/bod/
	// /lote/archivo/banesco/
	$rutaBase = dirname(__DIR__);
	$rutaBase = '';
	return [
		12 => $rutaBase . '/home/banesco',
		14 => $rutaBase . '/home/bod',
	];
 ?>