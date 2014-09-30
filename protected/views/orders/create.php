<?php
/* @var $this OrdersController */
/* @var $model Orders */

$this->breadcrumbs=array(
	'Orders'=>array('index'),
	'Create',
);

$this->menu=array(
	// array('label'=>'List Orders', 'url'=>array('index')),
	array('label'=>'Manage Orders', 'url'=>array('index')),
);
?>

<h1>Create New Order</h1>

<?php $this->renderPartial('_new', 
		array(
			'model'=>$model,
			'members'=>$members,
			'users'=>$users,
			'orderStatus'=>$orderStatus,
		)); ?>