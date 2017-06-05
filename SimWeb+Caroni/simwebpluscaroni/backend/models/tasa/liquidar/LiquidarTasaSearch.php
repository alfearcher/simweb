<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *  > This library is free software; you can redistribute it and/or modify it under
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *  > any later version.
 *  >
 *  > This library is distributed in the hope that it will be usefull,
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *  > for more details.
 *  >
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *  @file LiquidarTasaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-01-2017
 *
 *  @class LiquidarTasaSearch
 *  @brief Clase Modelo principal
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */

 	namespace backend\models\tasa\liquidar;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\base\ErrorException;
	use common\conexion\ConexionController;
	use common\models\presupuesto\codigopresupuesto\CodigosContables;
	use backend\models\impuesto\Impuesto;
	use common\models\tasas\GrupoSubnivel;
	use common\models\tasas\TiposRangos;
	use backend\models\tasa\Tasa;
	use yii\helpers\ArrayHelper;
	use backend\models\configuracion\tasasolicitud\TasaMultaSolicitud;
	use backend\models\tasa\TasaForm;



	/**
	 * Clase que gestiona
	 */
	class LiquidarTasaSearch
	{




		/**
		 * Metodo que permite rebderizar un view para generar un combo lista de los
		 * impuestos.
		 * @param  array $impuesto arreglo de identificadores de impuestos. Si se recibe
		 * un vacio se considerara buscar todos los impuestos.
		 * @return array retorna una lista o un guion (-).
		 */
		public function generarViewListaImpuesto($impuesto = [])
	    {

	    	if ( count($impuesto) > 0 ) {
				$model = Impuesto::find()->where(['IN','impuesto', $impuesto])->all();
			} else {
				$model = Impuesto::find()->all();
			}
	        if ( count($model) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $model as $mod ) {
	                echo "<option value='" . $mod->impuesto . "'>" . $mod->descripcion . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        return;
	    }




	    /**
	     * Metodo que permite renderizar una vista tipo combo lista de los
	     * año impositivo que esten activo en las tasas. con la intencion
	     * de ofrecer una lista de los años activos. Esta lista se generrara por impuesto.
	     * @param integer $impuesto identificador del impuesto.
	     * @return array retorna una lista o un guion (-).
	     */
	    public function generarViewListaAnoImpositivo($impuesto)
	    {
	    	$model = [];
	    	if ( $impuesto > 0 ) {
				$model = Tasa::find()->select('ano_impositivo')
									 ->distinct('ano_impositivo')
					                 ->where('impuesto =:impuesto',
											[':impuesto' => $impuesto])
					                 ->andWhere('inactivo =:inactivo',
					                 		[':inactivo' => 0])
								     ->all();
			}
	        if ( count($model) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $model as $mod ) {
	                echo "<option value='" . $mod->ano_impositivo . "'>" . $mod->ano_impositivo . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        ///return;
	    }




	    /**
	     * Metodo que permite renderizar un combo lista con los codigos presupuestarios.
	     * @param integer $impuesto identificador del impuesto.
	     * @param  integer $añoImpositivo año impositivo.
	     * @return array retorna una lista o un guion (-).
	     */
	    public function generarViewListaCodigoPresupuesto($impuesto, $añoImpositivo)
	    {
	    	$model = [];
	    	if ( $impuesto > 0 && $añoImpositivo > 0 ) {
				$model = CodigosContables::find()->alias('C')
												 ->select(['C.id_codigo',
												  		   'concat(C.codigo, " - ", C.descripcion) as cod'])
												 ->distinct('C.id_codigo')
												 ->joinWith('tasa T', true, 'INNER JOIN')
								                 ->where('T.impuesto =:impuesto',
														[':impuesto' => $impuesto])
								                 ->andWhere('T.ano_impositivo =:ano_impositivo',
								                 		[':ano_impositivo' => $añoImpositivo])
								                 ->andWhere('C.inactivo =:inactivo',
								                 		[':inactivo' => 0])
								                 ->andWhere('T.inactivo =:inactivo',
								                 		[':inactivo' => 0])
											     ->all();
			}
	        if ( count($model) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $model as $mod ) {
	                echo "<option value='" . $mod->id_codigo . "'>" . $mod->cod . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        return;
	    }




	    /**
	     * Metodo que permite renderizar una vista tipo combo lista con los grupos
	     * subniveles y su descripcion.
	     * @param integer $impuesto identificador del impuesto.
	     * @param  integer $añoImpositivo año impositivo.
	     * @param  integer $idCodigo identificador de los codigos contables.
	     * @return array retorna una lista o un guion (-).
	     */
	    public function generarViewListaGrupoSubNivel($impuesto, $añoImpositivo, $idCodigo)
	    {
	    	$model = [];
	    	if ( $impuesto > 0 && $añoImpositivo > 0 && $idCodigo > 0 ) {
				$findModel = GrupoSubnivel::find()->alias('G')
											  ->select(['G.grupo_subnivel',
											  		   'concat(G.grupo_subnivel, " - ", G.descripcion) as grupo'])
											  ->distinct('G.grupo_subnivel')
											  ->joinWith('tasa T', true, 'INNER JOIN')
							                  ->where('T.impuesto =:impuesto',
													[':impuesto' => $impuesto])
							                  ->andWhere('T.ano_impositivo =:ano_impositivo',
							                 		[':ano_impositivo' => $añoImpositivo])
							                  ->andWhere('T.id_codigo =:id_codigo',
							                 		[':id_codigo' => $idCodigo])
							                  ->andWhere('G.inactivo =:inactivo',
							                 		[':inactivo' => 0])
							                  ->andWhere('T.inactivo =:inactivo',
							                 		[':inactivo' => 0]);

				$model = $findModel->all();

			}

	        if ( count($model) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $model as $mod ) {
	                echo "<option value='" . $mod->grupo_subnivel . "'>" . $mod->grupo . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        return;
	    }




	     /**
	     * Metodo que permite renderizar una vista tipo combo lista con los grupos
	     * subniveles y su descripcion.
	     * @param integer $impuesto identificador del impuesto.
	     * @param  integer $añoImpositivo año impositivo.
	     * @param  integer $idCodigo identificador de los codigos contables.
	     * @param  boolean $excluirTasaConfig indica si se desea excluir de la consulta a los
	     * identificadores de las tasas que se encuentran configurados en las solicitudes.
	     * @return array retorna una lista o un guion (-).
	     */
	    public function generarViewListaCodigoSubNivel($impuesto, $añoImpositivo, $idCodigo, $grupoSubNivel, $excluirTasaConfig = false)
	    {
	    	$model = [];
	    	if ( $excluirTasaConfig ) {
	    		$excluirTasa = self::tasaConfiguradaSegunImpuesto($impuesto, $añoImpositivo);
	    	} else {
	    		$excluirTasa = [];		// vacio.
	    	}

	    	if ( $impuesto > 0 && $añoImpositivo > 0 && $idCodigo > 0 && $grupoSubNivel > 0 ) {
				$findModel = Tasa::find()->alias('T')
										 ->select(['T.codigo',
									  		   'concat(T.codigo, " - ", T.descripcion) as codigosub'])
					    	             ->where('T.impuesto =:impuesto',
												[':impuesto' => $impuesto])
					        	         ->andWhere('T.ano_impositivo =:ano_impositivo',
					                 			[':ano_impositivo' => $añoImpositivo])
					            	     ->andWhere('T.id_codigo =:id_codigo',
					                 			[':id_codigo' => $idCodigo])
					                	 ->andWhere('T.grupo_subnivel =:grupo_subnivel',
					                 			[':grupo_subnivel' => $grupoSubNivel])
					                 	->andWhere('T.inactivo =:inactivo',
					                 			[':inactivo' => 0]);

				if ( count($excluirTasa) > 0 ) {
					$model = $findModel->andWhere(['NOT IN', 'T.id_impuesto', $excluirTasa])->all();
				} else {
					$model = $findModel->all();
				}
			}

	        if ( count($model) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $model as $mod ) {
	                echo "<option value='" . $mod->codigo . "'>" . $mod->codigosub . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        return;
	    }





	    /***/
	    public function findIdImpuesto($impuesto, $añoImpositivo, $idCodigo, $grupoSubNivel, $codigo)
	    {
	    	$idImpuesto = 0;
	    	$model = [];
	    	if ( $impuesto > 0 && $añoImpositivo > 0 && $idCodigo > 0 && $grupoSubNivel > 0 && $codigo > 0 ) {
				$model = Tasa::find()->alias('T')
					                 ->where('T.impuesto =:impuesto',
											[':impuesto' => $impuesto])
					                 ->andWhere('T.ano_impositivo =:ano_impositivo',
					                 		[':ano_impositivo' => $añoImpositivo])
					                 ->andWhere('T.id_codigo =:id_codigo',
					                 		[':id_codigo' => $idCodigo])
					                 ->andWhere('T.grupo_subnivel =:grupo_subnivel',
					                 		[':grupo_subnivel' => $grupoSubNivel])
					                 ->andWhere('T.codigo =:codigo',
					                 		[':codigo' => $codigo])
					                 ->andWhere('T.inactivo =:inactivo',
					                 		[':inactivo' => 0])
					                 ->asArray()
					                 ->all();

			}

	        return $model;
	    }




	    /***/
	    public function findTasa($idImpuesto)
	    {
	    	$findModel = Tasa::find()->alias('T')
	    	                         ->joinWith('impuestos I', true, 'INNER JOIN')
	    	                         ->joinWith('codigoContable C', true, 'INNER JOIN')
	    	                         ->joinWith('grupoSubNivel G', true, 'INNER JOIN')
	    	                         ->joinWith('tipoRango R', true, 'INNER JOIN')
	    	                         ->where('id_impuesto =:id_impuesto',
	    	                        		[':id_impuesto' => $idImpuesto])
	    	                         ->asArray()
	    	                         ->one();
			return $findModel;
	    }



	    /**
	     * Metodo que determina los identificadores de las tasas que se encuatran configuradas
	     * segun el identificador del impuesto. El metodo arma un arreglo con los identificadores
	     * de las tasas. Estos identificadores de las tasas se deben encontrar activos al igual
	     * que el registro de la configuracion.
	     * @param integer $impuesto identificador del impuesto.
	     * @param integer $añoImpositivo año donde se desea encontrar el identificador de la tasa.
	     * @return array
	     */
	    public function tasaConfiguradaSegunImpuesto($impuesto, $añoImpositivo)
	    {
	    	$idTasas = [];	// identificadores de las tasas.
	    	$registers = TasaMultaSolicitud::find()->alias('M')
	    										   ->joinWith('tasa T', true, 'INNER JOIN')
	    										   ->where('M.inactivo =:inactivo',[':inactivo' => 0])
	    										   ->andWhere('T.inactivo =:inactivo',[':inactivo' => 0])
	    										   ->andWhere('T.impuesto =:impuesto',[':impuesto' => $impuesto])
	    										   ->asArray()
	    										   ->all();
	    	if ( count($registers) > 0 ) {
	    		$miTasa = New TasaForm();

	    		foreach ( $registers as $register ) {
	    			if ( !in_array($register['id_impuesto'], $idTasas) ) {

	    				$idImpuesto = $miTasa->determinarTasaRealSegunAnoImpositivo($register['id_impuesto'], $añoImpositivo);
	    				if ( $idImpuesto > 0 ) {
	    					$idTasas[]= $idImpuesto;
	    				}

	    			}
	    		}
	    	} else {
	    		$idTasas = [];		// Vacio.
	    	}

	    	return $idTasas;
	    }

	}
 ?>