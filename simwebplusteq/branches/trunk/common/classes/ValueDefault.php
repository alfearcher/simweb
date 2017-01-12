<?php 

	namespace common\classes;

	/**
	* 	Clase que permite retornar un array de valores por defecto que esten definos
	* 	en las reglas de validacion (metodo rules del modelo).
	* 
	* 	Se recibe el modelo (model) y de retorna un array de campos = valores por defecto.
	* 	Solo se retornaran los campos con valores por defecto.
	*/
	class ValueDefault
	{
		
		public function init($model)
		{
			//die(var_dump(self::getArrayValue($model)));

			// Ya obtengo el array de las rules
			$arrayValores = self::getArrayValue($model);

			if ( is_array($arrayValores) ) {
				self::detectarValoresDefault($arrayValores);
				die();
			}
		}


		private static function getArrayValue($model)
		{
			return $arrayRules = $model->rules(); 
		}


		public function contarDefault($arrayValores)
		{
			die(array_count_values($arrayValores));
		}



		/**
		*	Se busca determiniar los campos que poseen valores por defectos.
		*/
		private static function detectarValoresDefault($arrayValores)
		{
			
				if ( in_array('default', $arrayValores) ) {
					echo 'existe default';
				}


			
		}


	}
 ?>