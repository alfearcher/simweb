<?php
	  use kartik\icons\Icon;
  use yii\helpers\Html;
  use yii\bootstrap\Nav;

  $typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

  Icon::map($this, $typeIcon);
?>
<form  id="barra-inferior-form" >
	<div class="form-inline" style="height: 50px; width: 83%; padding-top:7px; padding-left: 620px;" >
	 	<div class="form-group" style="width: 20%;">
	    	<input type="text" value='<?php echo isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : null; ?>' class="form-control" id="id" readonly style="width: 100%;">
	   	</div>
	   	<div class="form-group" style="width: 70%;">
	   		<input type="text" value='<?php echo isset($_SESSION['contribuyente']) ? $_SESSION['contribuyente'] : null; ?>' class="form-control" id="contribuyente" readonly style="width: 100%;">
	   	</div>
  	</div>
</form>
