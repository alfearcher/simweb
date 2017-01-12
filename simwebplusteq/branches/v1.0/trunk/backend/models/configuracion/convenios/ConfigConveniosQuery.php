<?php

namespace backend\models\configuracion\convenios;

/**
 * This is the ActiveQuery class for [[ConfigConvenios]].
 *
 * @see ConfigConvenios
 */
class ConfigConveniosQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ConfigConvenios[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    } 

    /**
     * @inheritdoc
     * @return ConfigConvenios|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}