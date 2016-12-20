<?php
namespace backend\models\vehiculo\calcomania;

use Yii;

/**
 * This is the model class for table "funcionario_calcomania".
 *
 * @property integer $id_funcionario_calcomania
 * @property integer $id_funcionario
 * @property integer $estatus
 * @property string $naturaleza
 * @property integer $ci
 */
class FuncionarioCalcomaniaForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'funcionario_calcomania';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_funcionario', 'estatus', 'ci'], 'integer'],
            [['naturaleza'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_funcionario_calcomania' => Yii::t('backend', 'Id Funcionario Calcomania'),
            'id_funcionario' => Yii::t('backend', 'Id Funcionario'),
            'estatus' => Yii::t('backend', 'Estatus'),
            'naturaleza' => Yii::t('backend', 'Naturaleza'),
            'ci' => Yii::t('backend', 'Ci Funcionario'),
            'funcionariosName' => Yii::t('backend', 'Name'),
        ];
    }

    /**
    *   Contiene la relacion de 1 a M, de las tablas funcionario_calcomania y funcionarios, 
    *   las cuales se relacionan  por su id referencial
    */
    public function getFuncionarios()
    {
       return $this->hasOne(\backend\models\Funcionario::className(), ['id_funcionario' => 'id_funcionario']);
    }

    public function getFuncionarioName()
    {
        return $this->funcionarios->nombres;
    }

    public function getFuncionarioApellido()
    {
        return $this->funcionarios->apellidos;
    }

    public function getFuncionarioCargo()
    {
        return $this->funcionarios->cargo;
    }

    /**
    *   Contiene la relacion de 1 a M, de las tablas distribucion_calcomania y funcionarios, 
    *   las cuales se relacionan  por su id referencial
    */
    public function getDistribucion()
    {
       return $this->hasOne(DistribucionCalcomaniaForm::className(), ['id_funcionario_calcomania' => 'id_funcionario_calcomania']);
    }

    /**
    *   Almacena el campo rango_inicial de la tabla distribucion_calcomania, en una variable GET para retornarla
    *   a la vista
    */
    public function getDistribucionRangoInicial()
    {
        if ($this->distribucion == null) {
            return "No Set";
        }else{
            return $this->distribucion->rango_inicial;
        }
    }

    /**
    *   Almacena el campo rango_final de la tabla distribucion_calcomania, en una variable GET para retornarla
    *   a la vista
    */
    public function getDistribucionRangoFinal()
    {
        if ($this->distribucion == null) {
            return "No Set";
        }else{
            return $this->distribucion->rango_final;
        }        
    }
}
