<?php
  use kartik\icons\Icon;
  use yii\helpers\Html;
  use yii\bootstrap\Nav;

  $typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

  Icon::map($this, $typeIcon);
?>
<form  id="barra-inferior-form" style="height: 10px;" >
	<div class="form-inline">
		<div class="col-sm-10">
			<div class="row" style="width: 100%;" >
				<!--<div class="col-sm-6" style="width:98%;padding-top:0px; margin-bottom:0px; margin-top: 0px;">-->

					<div class="col-sm-3" style="width: 5%;">
					</div>

				 	<div class="col-sm-3" style="padding-right: 0px;padding-left: 0px;width: 15%;">
					 	<div class="form-group">
					    	<input type="text" value='<?php echo isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : null; ?>' class="form-control" id="id" readonly >
					   	</div>
				   	</div>


				   	<div class="col-sm-3" style="padding-left: 0px; padding-right: 0px;width: 80%;">
					   	<div class="form-group">
					   		<input type="text" value='<?php echo isset($_SESSION['contribuyente']) ? $_SESSION['contribuyente'] : null; ?>' class="form-control" id="contribuyente" readonly >
					   	</div>
				   	</div>

			  	<!--</div>-->
			</div>
		</div>
	</div>
</form>
