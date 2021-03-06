<?php

/**
 * This is the model class for table "catalogs".
 *
 * The followings are the available columns in table 'catalogs':
 * @property integer $id
 * @property string $dateReleased
 * @property string $dateCreated
 * @property string $dateLastModified
 * @property integer $_current
 *
 * The followings are the available model relations:
 * @property Products[] $products
 */
class Catalogs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catalogs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'unique'),
			array('_current', 'numerical', 'integerOnly'=>true),
			array('name, dateReleased', 'required'),
			array('id, name, dateReleased, dateCreated, dateLastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dateReleased, dateCreated, dateLastModified, _current', 'safe', 'on'=>'search'),
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
			'products' => array(self::HAS_MANY, 'Products', 'catalogId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dateReleased' => 'Date Released',
			'dateCreated' => 'Date Created',
			'dateLastModified' => 'Date Last Modified',
			'_current' => 'Current',
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
		$criteria->compare('dateReleased',$this->dateReleased,true);
		$criteria->compare('dateCreated',$this->dateCreated,true);
		$criteria->compare('dateLastModified',$this->dateLastModified,true);
		$criteria->compare('_current',$this->_current);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Catalogs the static model class
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
}
