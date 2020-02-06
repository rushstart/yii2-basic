<?php


namespace app\modules\node\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "field_contact".
 *
 * @property int $node_id ID сущности
 * @property int $weight Меньше выше, используется для множественных полей
 * @property string $type Тип контакта
 * @property string $value Очищенное значение
 * @property string|null $formatted Оформленное значение
 * @property string|null $comment Примечание
 *
 */
class FieldContact extends ActiveRecord
{

    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'field_contact';
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            static::TYPE_PHONE => 'Телефон',
            static::TYPE_EMAIL => 'E-mail',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['node_id', 'type', 'value'], 'required'],
            [['node_id', 'weight'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['value', 'formatted', 'comment'], 'string', 'max' => 255],
            [['node_id', 'weight'], 'unique', 'targetAttribute' => ['node_id', 'weight']],
            [['value'], 'unique', 'targetAttribute' => ['value']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'node_id' => 'Node ID',
            'weight' => 'Weight',
            'type' => 'Тип контакта',
            'value' => 'Контакт',
            'formatted' => 'Контакт',
            'comment' => 'Примечание',
        ];
    }

}
