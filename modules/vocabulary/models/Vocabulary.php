<?php

namespace app\modules\vocabulary\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "vocabulary".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 *
 * @property VocabularyItem[] $vocabularyItems
 */
class Vocabulary extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vocabulary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
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
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[VocabularyItems]].
     *
     * @return ActiveQuery
     */
    public function getVocabularyItems()
    {
        return $this->hasMany(VocabularyItem::class, ['vocabulary_id' => 'id']);
    }
}
