<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file ContribuyenteBase.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 16-09-2015
 *
 *  @class Contribuyente modelo principal de la entidad "contribuyentes"
 *
 *
 *	@property
 *
 *	@method
 *
 *	@inherits
 *
 */
	namespace backend\models\solicitud\especial\inmueble\certificadocatastral;

	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\conexion\ConexionController;
	use yii\db\Exception;


	/**
	*
	*/
	class VistaPreliminarCertificado extends ActiveRecord
	{
//sl certificado catastral
		public $id_certificado;
		public $nro_solicitud; 
		public $id_contribuyente; 
		public $id_impuesto; 
		public $tipo;
		public $ano_impositivo;
		public $certificado_catastral;
		public $usuario; 
		public $fecha_hora; 
		public $origen;
		public $estatus; 
		public $fecha_hora_proceso; 
		public $user_funcionario;

//inmueble
        public $ano_inicio;
        public $direccion;
        public $liquidado;
        public $manzana_limite;
        public $lote_1;
        public $lote_2;
        public $nivel;
        public $lote_3; 
        public $av_calle_esq_dom;
        public $casa_edf_qta_dom;
        public $piso_nivel_no_dom;
        public $apto_dom;
        public $tlf_hab;
        public $medidor;
        public $id_sim;
        public $observacion;
        public $inactivo;
        public $catastro;
        public $id_habitante;
        public $tipo_ejido;
        public $propiedad_horizontal;
        public $estado_catastro;
        public $municipio_catastro;
        public $parroquia_catastro;
        public $ambito_catastro;
        public $sector_catastro;
        public $manzana_catastro;
        public $parcela_catastro;
        public $subparcela_catastro;
        public $nivel_catastro; 
        public $unidad_catastro;


//inmueble resgistros
		public $id_inmueble_registro;
		public $num_reg;
		public $reg_mercantil;
		public $tomo;
		public $folio;
		public $fecha;
		public $valor_documental;
		public $id_tipo_documento_inmueble;
		public $nro_matricula;
		public $protocolo; 
		public $asiento_registral;

//historico avaluos
		public $id_historico_avaluo;
		public $valor;
		public $mts;
		public $id_uso_inmueble;
		public $valor_por_mts2;
		public $clase_inmueble;
		public $tipo_inmueble;
		public $tipo_aseo;
		public $mts2_terreno;
		public $valor_por_mts2_terreno;
		public $lindero_norte;
		public $lindero_sur;
		public $lindero_este;
		public $lindero_oeste;
		public $id_tipologia_zona;


//contribuyentes
		public $ente;
		public $naturaleza;
		public $cedula;
		public $tipo_naturaleza;
		public $id_rif;
		public $id_cp;
		public $nombres;
		public $apellidos;
		public $razon_social;
		public $representante;
		public $nit;
		public $fecha_nac;
		public $sexo;
		public $domicilio_fiscal;
		public $tlf_hab_otro;
		public $tlf_ofic;
		public $tlf_ofic_otro;
		public $tlf_celular;
		public $fax;
		public $email;
		public $cuenta;
		public $capital;
		public $horario;
		public $extension_horario;
		public $num_empleados;
		public $tipo_contribuyente; 
		public $licencia;
		public $agente_retencion;
		public $fecha_inclusion;
		public $fecha_inicio;
		public $foraneo;
		public $no_declara;
		public $econ_informal;
		public $grupo_contribuyente;
		public $fe_inic_agente_reten;
		public $no_sujeto;
		public $ruc;
		public $naturaleza_rep; 
		public $cedula_rep;

		/**
		 *	Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
		 * 	Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
		 * 	@return Nombre de la base de datos
		 */
		public static function getDb()
		{
			return Yii::$app->db;
		}




		/**
		 * 	Metodo que retorna el nombre de la tabla que utiliza el modelo.
		 * 	@return Nombre de la tabla del modelo.
		 */
		public static function tableName()
		{
			return 'contribuyentes';
		}

		 public function rules()
    {
        
        return [ 


         
            [['documento_propiedad','tomo'], 'integer','message' => Yii::t('backend', 'Debe seleccionar una opcion'),'when'=> function($modelRegistro){ return $this->validacion3 == 3; }],
            [['documento_propiedad', 'fecha','num_reg','reg_mercantil','valor_documental' ], 'required','message' => Yii::t('backend', 'Campo requerido'),'when'=> function($modelRegistro){ return $this->validacion3 == 3; }],
            [['valor_documental'], 'double','message' => Yii::t('backend', 'debe ser numerico'),'when'=> function($modelRegistro){ return  $this->validacion3 == 3; }],
            
            [['nro_matricula', 'asiento_registral'], 'required','message' => Yii::t('backend', 'Campo requerido'),'when'=> function($modelRegistro){ return $this->documento_propiedad == 2 and $this->validacion3 == 3; }],
            [['tomo', 'protocolo','folio'], 'required','message' => Yii::t('backend', 'Campo requerido'),'when'=> function($modelRegistro){ return $this->documento_propiedad == 1 and $this->validacion3 == 3; }],

          
                        
        ];  
    }

    
    public function attributeLabels()
    {
        return [ 
            'lindero_norte' => Yii::t('backend', 'Lindero Norte'), 
            'lindero_sur' => Yii::t('backend', 'Lindero Sur'),
            'lindero_este' => Yii::t('backend', 'Lindero Este'),
            'lindero_oeste' => Yii::t('backend', 'Lindero Oeste'),
            
        ];  
    }
	}
 ?>