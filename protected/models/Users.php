<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $dateCreated
 * @property string $dateLastModified
 * @property integer $userTypeId
 *
 * The followings are the available model relations:
 * @property Orders[] $orders
 * @property Orderstatushistory[] $orderstatushistories
 * @property Payments[] $payments
 * @property Uporders[] $uporders
 * @property Uporderstatushistory[] $uporderstatushistories
 * @property Usertypes $userType
 * @property Members[] $members
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userTypeId', 'required'),
			array('userTypeId', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>10),
			array('password', 'length', 'max'=>45),
			array('dateCreated, dateLastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, dateCreated, dateLastModified, userTypeId', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY, 'Orders', 'userId'),
			'orderstatushistories' => array(self::HAS_MANY, 'Orderstatushistory', 'userId'),
			'payments' => array(self::HAS_MANY, 'Payments', 'userId'),
			'uporders' => array(self::HAS_MANY, 'Uporders', 'userId'),
			'uporderstatushistories' => array(self::HAS_MANY, 'Uporderstatushistory', 'userId'),
			'userType' => array(self::BELONGS_TO, 'Usertypes', 'userTypeId'),
			'members' => array(self::MANY_MANY, 'Members', 'usersmembers(userId, memberId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'dateCreated' => 'Date Created',
			'dateLastModified' => 'Date Last Modified',
			'userTypeId' => 'User Type',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('dateCreated',$this->dateCreated,true);
		$criteria->compare('dateLastModified',$this->dateLastModified,true);
		$criteria->compare('userTypeId',$this->userTypeId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
