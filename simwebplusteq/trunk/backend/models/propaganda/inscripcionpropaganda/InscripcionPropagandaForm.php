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
 *  @file InscripcionPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @class InscripcionPropaganda
 *  @brief Clase Modelo
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

    namespace backend\models\propaganda\inscripcionpropaganda;

    use Yii;
    use yii\base\Model;
    use yii\db\ActiveRecord;
    use backend\models\propaganda\inscripcionpropaganda\InscripcionPropaganda;



    /**
    *  Clase
    */
    class InscripcionPropagandaForm extends InscripcionPropaganda
    {

    	public $id_impuesto;
    	public $nro_solicitud;
    	public $id_contribuyente;
    	public $ano_impositivo;
    	public $direccion;
    	public $id_cp;
    	public $clase_propaganda;
    	public $tipo_propaganda;
    	public $uso_propaganda;
    	public $medio_difusion;
    	public $medio_transporte;
    	public $fecha_inicio;
    	public $cantidad_tiempo;
    	public $id_tiempo;
    	public $inactivo;
    	public $id_sim;
    	public $cantidad_base;
    	public $base_calculo;
    	public $cigarros;
    	public $bebidas_alcoholicas;
    	public $cantidad_propagandas;
    	public $planilla;
    	public $idioma;
    	public $observacion;
    	public $fecha_fin;
    	public $fecha_hora;
    	public $usuario;
    	public $user_funcionario;
    	public $fecha_hora_proceso;
    	public $estatus;
    	public $alto;
    	public $ancho;
    	public $profundidad;
    	public $nombre_propaganda;
    	public $mts;
    	public $costo;
    	public $origen;
    	public $errorMensajeInput;
    	public $descripcion;

    	const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';


    	/***/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
        		self::SCENARIO_FRONTEND => [
        					'id_contribuyente',
        					'id_impuesto',
        					'nro_solicitud',
        					'ano_impositivo',
        					'direccion',
        					'id_cp',
        					'clase_propaganda',
        					'tipo_propaganda',
        					'uso_propaganda',
        					'medio_difusion',
        					'medio_transporte',
        					'cantidad_tiempo',
        					'id_tiempo',
        					'inactivo',
        					'id_sim',
        					'cantidad_base',
        					'base_calculo',
        					'cigarros',
        					'bebidas_alcoholicas',
        					'cantidad_propagandas',
        					'planilla',
        					'idioma',
        					'observacion',
        					'fecha_fin',
        					'fecha_inicio',
        					'fecha_hora',
        					'usuario',
        					'user_funcionario',
        					'fecha_hora_proceso',
        					'estatus',
        					'alto',
        					'ancho',
        					'profundidad',
        					'nombre_propaganda',
        					'mts',
        					'costo',
        					'origen',
        					'descripcion',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'id_impuesto',
        					'nro_solicitud',
        					'ano_impositivo',
        					'direccion',
        					'id_cp',
        					'clase_propaganda',
        					'tipo_propaganda',
        					'uso_propaganda',
        					'medio_difusion',
        					'medio_transporte',
        					'cantidad_tiempo',
        					'id_tiempo',
        					'inactivo',
        					'id_sim',
        					'cantidad_base',
        					'base_calculo',
        					'cigarros',
        					'bebidas_alcoholicas',
        					'cantidad_propagandas',
        					'planilla',
        					'idioma',
        					'observacion',
        					'fecha_fin',
        					'fecha_inicio',
        					'fecha_hora',
        					'usuario',
        					'user_funcionario',
        					'fecha_hora_proceso',
        					'estatus',
        					'alto',
        					'ancho',
        					'profundidad',
        					'nombre_propaganda',
        					'mts',
        					'costo',
        					'origen',
        					'descripcion',
        		]
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'nombre_propaganda',
	        	  'direccion',
	        	  'clase_propaganda', 'uso_propaganda',
	        	  'tipo_propaganda', 'id_tiempo', 'fecha_inicio',
	        	  'fecha_fin',
	        	  'cantidad_tiempo', 'base_calculo',
	        	  'cantidad_propagandas'],
	        	  'required'],
	        	[['id_impuesto', 'id_contribuyente', 'nro_solicitud',
	        	  'ano_impositivo', 'clase_propaganda', 'uso_propaganda',
	        	  'tipo_propaganda', 'id_tiempo',
	        	  'base_calculo',
	        	  'estatus', 'inactivo', 'idioma',
	        	  'bebidas_alcoholicas', 'planilla', 'medio_transporte',
	        	  'medio_difusion', 'id_cp', 'cigarros',],
	        	  'integer',
	        	  'message' => Yii::t('backend', '{attribute} no valido')],
	        	[['cantidad_tiempo',],
	        	   'number',
	        	   'message' => Yii::t('backend', '{attribute} no valido')],
	        	[['cantidad_propagandas'],
	        	   'number',
	        	   'message' => Yii::t('backend', '{attribute} no valido')],
	        	[['observacion', 'direccion',
	        	  'descripcion', 'nombre_propaganda', 'origen',],
	        	  'string',
	        	  'message' => Yii::t('backend', '{attribute} no valido')],
	        	[['alto', 'ancho', 'profundidad',
	        	  'mts', 'costo'],
	        	  'double',
	        	  'message' => Yii::t('backend', '{attribute} no valido')],
	        	[['estatus', 'inactivo', 'bebidas_alcoholicas',
	        	  'idioma', 'id_cp', 'medio_difusion', 'medio_transporte',
	        	  'id_sim', 'cigarros', 'planilla', 'id_cp',
	        	  'cantidad_base', 'alto', 'ancho', 'profundidad',
	        	  'mts', 'costo', 'id_impuesto',],
	        	  'default',
	        	  'value' => 0],
	        	[['ano_impositivo',],
	        	  'default',
	        	  'value' => date('Y')],
	        	[['fecha_desde', 'fecha_fin'],
	        	  'date',
	        	  'format' => 'dd-MM-yyyy',
	        	  'message' => Yii::t('backend','formatted date no valid')],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		['nombre_propaganda', 'filter', 'filter' => 'strtoupper'],
	     		[['fecha_hora', 'fecha_guardado'],
	     		  'default',
	     		  'value' => date('Y-m-d H:i:s')],
	     		[['fecha_guardado'],
	     		  'default',
	     		  'value' => date('Y-m-d')],
	     		['nombre_propaganda', 'filter', 'filter' => 'strtoupper'],
	        ];
	    }



	    /**
	     * Metodo que retorna un arreglo de atributos que seran actualizados
	     * al momento de procesar la solicitud (aprobar o negar). Estos atributos
	     * afectaran a la entidad respectiva de la clase.
	     * @param String $evento, define la accion a realizar sobre la solicitud.
	     * - Aprobar.
	     * - Negar.
	     * @return Array Retorna un arreglo de atributos segun el evento.
	     */
	    public function atributosUpDateProcesarSolicitud($evento)
	    {
	    	$atributos = [
	    		Yii::$app->solicitud->aprobar() => [
	    						'estatus' => 1,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => Yii::$app->identidad->getUsuario(),

	    		],
	    		Yii::$app->solicitud->negar() => [
	    						'estatus' => 9,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => Yii::$app->identidad->getUsuario(),

	    		],
	    	];

	    	return $atributos[$evento];
	    }



	    /***/
	    public function validateInputBaseCalculo()
	    {
	    	$result = false;
	    	if ( $this->base_calculo == 2 ) {
	    		if ( $this->alto > 0 && $this->ancho > 0 ) {
	    			$result = true;

	    		} else {
	    			$this->errorMensajeInput = Yii::t('backend', 'Debe registrar alto y ancho');
	    		}

	    	} elseif ( $this->base_calculo == 3 ) {
	    		if ( $this->mts > 0 ) {
	    			$result = true;
	    		} else {
	    			$this->errorMensajeInput = Yii::t('backend', 'Debe registrar metros');
	    		}

	    	} elseif ( $this->base_calculo == 7 ) {
	    		if ( $this->costo > 0 ) {
	    			$result = true;
	    		} else {
	    			$this->errorMensajeInput = Yii::t('backend', 'Debe registrar costo');
	    		}

	    	} else {
	    		$result = true;
	    	}

	    	return $result;
	    }




    }

?>