<div id='order-details' class="row-fluid">
	<?php 
		$orderDetails = $order->attributes;
		$orderDetails['memberName'] = $order->member->codeName;
		$orderDetails['orderStatus'] = $order->orderStatus->description;
		$this->renderPartial(
				'_detail',
				array(
					'order' => $orderDetails,
				)
			); 		
	?>
</div>
<div class="space"><br /></div>
<div class="row-fluid pull-right">
<?php
	$this->widget(
		'booster.widgets.TbButton',
		array(
			'label' => 'Refresh',
			'icon' => 'refresh',
			'htmlOptions' => array(
				'id' => 'refresh',
				'onclick' => 'location.reload()',
			)
		)
	);

	echo '&nbsp;';

	$this->widget(
		'booster.widgets.TbButton',
		array(
			'label' => 'Print Order',
			'icon' => 'print',
			'url' => Yii::app()->createUrl('order/print'),
			'htmlOptions' => array(
				'id' => 'print',
				'onclick' => "window.open('" . Yii::app()->createUrl('order/print', array('id' => $order->id)) . "', '_blank');"
			)
		)
	);

	$this->widget(
		'booster.widgets.TbButtonGroup',
		array(
			'buttons' => array(
				array(
					'label' => 'Change Order Status',
					'icon' => 'cog',
					'items' => array(
						array(
							'label' => 'Cancel', 
							'url' => Yii::app()->createUrl('order/changeStatus', array('id' => $order->id, 'status' => 'cancelled')),
							'visible' => in_array($order->orderStatus->status, array('temp', 'partial', 'paid')),
						),
						/*array(
							'label' => 'In Order', 
							'url' => Yii::app()->createUrl('order/changeStatus', array('id' => $order->id, 'status' => 'inOrder')),
							'visible' => !in_array($order->orderStatus->status, array('inOrder', 'temp', 'cancelled', 'served')),
						),
						array(
							'label' => 'Delivered', 
							'url' => Yii::app()->createUrl('order/changeStatus', array('id' => $order->id, 'status' => 'delivered')),
							'visible' => !in_array($order->orderStatus->status, array('delivered', 'temp', 'cancelled', 'served')),
						),*/
						array(
							'label' => 'Serve', 
							'url' => Yii::app()->createUrl('order/changeStatus', array('id' => $order->id, 'status' => 'served')),
							'visible' => in_array($order->orderStatus->status, array('paid', 'delivered')) && $order->fullyPaid,
						),
						array(
							'label' => 'Delete Permanently', 
							'url' => Yii::app()->createUrl('order/delete', array('id' => $order->id)),
							'visible' => 0, //in_array($order->orderStatus->status, array('cancelled')),
						),
					)
				)
			),
		)
	);				
?>
</div>	