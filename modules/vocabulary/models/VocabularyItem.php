<?php

namespace app\modules\vocabulary\models;

use app\modules\vocabulary\HierarchyInterface;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vocabulary_item".
 *
 * @property int $id
 * @property int $vocabulary_id
 * @property string $name
 * @property int|null $parent_id
 * @property int $depth
 * @property int $weight
 * @property int $status
 *
 * @property VocabularyItem $parent The first parent
 * @property VocabularyItem[] $parents All rising parents
 * @property VocabularyItem[] $ownChildren All the own children
 * @property array $nestedIds Returns an array of IDs of nested descendants
 * @property array $parentIds
 */
class VocabularyItem extends ActiveRecord implements HierarchyInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vocabulary_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vocabulary_id', 'name'], 'required'],
            [['vocabulary_id', 'parent_id', 'depth', 'weight', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['vocabulary_id', 'name'], 'unique', 'targetAttribute' => ['vocabulary_id', 'name']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => VocabularyItem::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['vocabulary_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vocabulary::class, 'targetAttribute' => ['vocabulary_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vocabulary_id' => 'Vocabulary ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'depth' => 'Depth',
            'weight' => 'Weight',
            'status' => 'Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getVocabulary()
    {
        return $this->hasOne(Vocabulary::class, ['id' => 'vocabulary_id']);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * @inheritDoc
     */
    public function getParents(): array
    {
        $current = $this->parent;
        $parents = [];
        while ($current) {
            $parents[] = $current;
            $current = $current->parent;
        }
        return array_reverse($parents);
    }

    /**
     * @inheritDoc
     */
    public function getParentIds(): array
    {
        return ArrayHelper::getColumn($this->parents, 'id');
    }

    /**
     * @inheritDoc
     */
    public function getOwnChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    /**
     * @inheritDoc
     */
    public function getNestedTree(): array
    {
        return [
            'id' => $this->id,
            'children' => array_map(function ($child) {
                /** @var $child static */
                return $child->getNestedTree();
            }, $this->ownChildren),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAllOffspring(): array
    {
        $result = [];
        $tree = $this->getNestedTree();
        array_walk_recursive($tree, function ($item) use (&$result) {
            $result[] = $item;
        });
        return $result;
    }
}
