<?php


namespace app\modules\node\models;


use app\modules\node\behaviors\RelationServantBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "node".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property FieldContact[] $contacts
 */
class Node extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'node';
    }

    public function behaviors()
    {
        return [
            'contacts' => [
                'class' => RelationServantBehavior::class,
                'attribute' => 'contacts',
            ],
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[FieldContacts]].
     *
     * @return ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(FieldContact::class, ['node_id' => 'id']);
    }
}
