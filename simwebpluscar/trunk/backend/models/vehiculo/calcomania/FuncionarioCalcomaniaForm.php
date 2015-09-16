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
    *   Contiene la relacion de 1 a M, de las tablas lote_calcomania y funcionarios, 
    *   las cuales se relacionan  por su id referencial
    */
    public function getLoteCalcomania()
    {
       return $this->hasOne(LoteCalcomaniaForm::className(), ['ano_impositivo' => date('Y')]);
    }

    /**
    *   Almacena el campo rango_inicial de la tabla lote_calcomania, en una variable GET para retornarla
    *   a la vista
    */
    public function getLoteCalcomaniaRangoInicial()
    {
        return $this->lotecalcomania->rango_inicial;
    }
}
