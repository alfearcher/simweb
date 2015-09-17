<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use kartik\icons\Icon;
use common\models\menu\Menu;
   
$typeIcon = Icon::FA;
$typeLong = 'fa-2x';
$this->title = Yii::t( 'backend', 'Main Menu' );
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="col-xs-3">
    <div class="menu-funcionario" >
        <div class="nav-side-menu">
            <div class="brand"><?= $this->title;?></div>
                <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
                    <div class="menu-list">
                        <ul id="menu-content" class="menu-content collapse out">
                            <ul id="nav" >

                                <?php   
                                        foreach( $model as $row ): 
                                            if( $row['seccion'] == '0' ) {
                                ?>
                                                    <li><a href="<?= $row['enlace'] ?>"><i class="<?= $row['icono'] ?>"></i>&nbsp; <?= Yii::t( 'backend', $row['nombre_esp'] )?> <?php if( $row['seccion'] == '0' ){ echo '<span class="arrow"></span>'; }?> </a>
                                <?php       }

                                            if( $row['seccion'] == '1' ) {
                                ?>
                                                <ul id="nav1">
                                                    <li><a href="<?= $row['enlace'] ?>" ><i class="<?= $row['icono'] ?>"></i>&nbsp;<?= Yii::t('backend', $row['nombre_esp'])?><?php if( $row['seccion'] == '1' ){ echo '<span class="arrow"></span>'; }?></a> 
                                                </ul>     
                                <?php       }

                                            $model = Menu::find()->where( [ 'id_padre' => $row['id_menu'] ] )->andwhere( ['inactivo' => '0'] )->orderBy( 'id_menu' )->all();

                                            foreach ($model as $row1):
                                            if( $row1['seccion'] == '2' ) {
                                ?>
                                                <ul>
                                                    <li><a href="<?= $row1['enlace'] ?>" style="color:#fff;"><i class="<?= $row1['icono'] ?>"></i>&nbsp;<?= Yii::t( 'backend', $row1['nombre_esp'] )?></a></li> 
                                                </ul>

                                <?php       }
                                            endforeach;
                                        endforeach;
                                ?>
                                </li>
                            </ul>
                        </ul>
                    </div>
        </div>
    </div>
</div>