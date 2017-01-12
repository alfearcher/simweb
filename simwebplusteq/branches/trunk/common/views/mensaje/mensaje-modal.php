<?php
	use yii\helpers\Html;
	use yii\bootstrap\Alert;
	use yii\bootstrap\Modal;
	use yii\web\View;
	use yii\jui\Dialog;
	use kartik\icons\Icon;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-3x';

    Icon::map($this, $typeIcon);


//  Dialog::begin([
//     'clientOptions' => [
//         'modal' => true,
//         'title' => 'Basic dialog',
//     ],
// ]);
// echo Html::encode($cuerpoMensaje);
// Dialog::end();


?>

<div class="row" style="width: 60%; alignment: center; margin-top:15px; margin-left: 15px;">
	<?php
	 	Alert::begin([
	    	'options' => [
	    	//'alert-warning'
	        	'class' => 'alert-'.$cuerpoMensaje[1],
	    	],
	    	'clientOptions' => [
	    		'modal' => true,
	     	],
	     	'body' => '<div class="row">
	     			   		<div class="col-sm-1">' .
	     						Icon::show($cuerpoMensaje[2],["class" => $typeLong], $typeIcon) .
	     					'</div>' .
	     					'<div class="col-sm-3" style="width: 80%; margin-top: 10px;">' .
	     						"<h4>" . Html::encode($cuerpoMensaje[0]) . "</h4>" .
	     					'</div>' .
	     			  '</div>',
		]);

		Alert::end();
	?>
</div>

<?php
	// Modal::begin([
 //    	'header' => '<h2>Hello world</h2>',
 //    	'toggleButton' => ['label' => 'click me'],
 //    	// 'toggleButton' => ['function() {
 //    	// 					<script> alert("hola"); </script>
 //    	// 				}'],
	// ]);

	// echo 'kakakakakka';

	// Modal::end();
	//
// $this->registerJs('
// 		$(function() {
// 			alert("kksks");
// 		});
// 	');
 ?>


<!-- <div class="row" style="alignment: center;">
	<div class="panel panel-warning" style="width: 60%;">
		<div class="panel-heading">
	    	<h3 class="panel-title"><strong><?//= Yii::t('backend', 'NOTE')?></strong></h3>
	  	</div>
	  	<div class="panel-body">
	  		<class="message"><h3><p><?//= Html::encode($cuerpoMensaje) ?></p></h3>
	  	</div>
	</div>
</div>
 -->