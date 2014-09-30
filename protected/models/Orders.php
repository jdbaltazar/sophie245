<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $id
 * @property string $dateCreated
 * @property string $dateLastModified
 * @property integer $memberId
 * @property integer $userId
 * @property integer $orderStatusId
 *
 * The followings are the available model relations:
 * @property Orderdetails[] $orderdetails
 * @property Members $member
 * @property Users $user
 * @property Orderstatus $orderStatus
 * @property Orderstatushistory[] $orderstatushistories
 * @property Payments[] $payments
 * @property Uporders[] $uporders
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('memberId, userId, orderStatusId', 'required'),
			array('memberId, userId, orderStatusId', 'numerical', 'integerOnly'=>true),
			// array('dateCreated, dateLastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dateCreated, dateLastModified, memberId, userId, orderStatusId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'orderdetails' => array(self::HAS_MANY, 'Orderdetails', 'orderId', 'with' => 'product' ),
			'member' => array(self::BELONGS_TO, 'Members', 'memberId'),
			'user' => array(self::BELONGS_TO, 'Users', 'userId'),
			'orderStatus' => array(self::BELONGS_TO, 'Orderstatus', 'orderStatusId'),
			'orderstatushistories' => array(self::HAS_MANY, 'Orderstatushistory', 'orderId'),
			'payments' => array(self::HAS_MANY, 'Payments', 'orderId', 'with' => 'paymentType'),
			'uporders' => array(self::MANY_MANY, 'Uporders', 'upordersorders(orderId, upOrderId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dateCreated' => 'Date Created',
			'dateLastModified' => 'Date Last Modified',
			'memberId' => 'Member',
			'userId' => 'User',
			'orderStatusId' => 'Order Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('dateCreated',$this->dateCreated,true);
		$criteria->compare('dateLastModified',$this->dateLastModified,true);
		$criteria->compare('memberId',$this->memberId);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('orderStatusId',$this->orderStatusId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function beforeSave() 
	{
		if ($this->isNewRecord)
		{
			$this->dateCreated = new CDbExpression('NOW()');
		}

		$this->dateLastModified = new CDbExpression('NOW()');
		
		return parent::beforeSave();
	}
	
	public function getOrderDetailSummary()
	{
		$gross = 0;
		$net = 0;
		$items = 0;
		$orderDetails = $this->orderdetails;
		foreach($orderDetails as $orderDetail)
		{
			$gross += $orderDetail->product->catalogPrice;
			$discount = 1 - ($orderDetail->discount / 100);
			$net += $orderDetail->product->catalogPrice * $discount * $orderDetail->quantity;
			$items += $orderDetail->quantity;
		}
		
		return array(
					'gross' => $gross,
					'net' => $net,
					'items' => $items
				);
	}
	
	public function getTotalPayment()
	{
		$paymentTotal = 0;
		foreach($this->payments as $payment)
		{
			$paymentTotal += $payment->amount;
		}
		
		return $paymentTotal;
	}
}
