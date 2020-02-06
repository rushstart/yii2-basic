<?php


namespace app\modules\node;


use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\validators\UniqueValidator;
use yii\validators\Validator;

class RelationServantBehavior extends Behavior
{
    /**
     * The attribute name of the relation
     * @var string
     */
    public $attribute;

    /** @var ActiveRecord */
    public $owner;

    /**
     * {@inheritdoc}
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->registerRules();
    }

    /**
     * Registers the rules in the owner model
     */
    protected function registerRules()
    {
        $this->owner->validators[$this->attribute] = Validator::createValidator('safe', $this->owner, $this->attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    /**
     * Event handler
     */
    public function afterValidate()
    {
        $attribute = $this->attribute;
        if ($this->isMultiple()) {
            foreach ($this->owner->$attribute as $weight => $relation) {
                if ($relation instanceof ActiveRecord) {
                    $this->validate($relation, $weight);
                }
            }
        } else {
            $relation = $this->owner->$attribute;
            if ($relation instanceof ActiveRecord) {
                $this->validate($this->owner->$attribute);
            }

        }
    }

    /**
     * Event handler
     */
    public function afterSave()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $attribute = $this->attribute;
            $relations = $this->isMultiple() ? $this->owner->$attribute : [$this->owner->$attribute];
            $this->owner->unlinkAll($attribute, true);
            foreach ($relations as $relation) {
                if ($relation instanceof ActiveRecord) {
                    $relation->setIsNewRecord(true);
                    $this->owner->link($attribute, $relation);
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::$app->session->addFlash('error', $e->getMessage());
        }
    }

    /**
     * Validates the servant model
     * @param ActiveRecord $relation
     * @param null $weight
     */
    protected function validate(ActiveRecord $relation, $weight = null)
    {
        if (!$relation->validate()) {

            $this->skipOwnServant($relation);
            foreach ($this->getLink() as $servantAttr => $pass) {
                $relation->clearErrors($servantAttr);
            }
            foreach ($relation->errors as $attribute => $attributeError) {
                if ($weight === null) {
                    $this->owner->addError("{$this->attribute}[{$attribute}]", $attributeError);
                } else {
                    $this->owner->addError("{$this->attribute}[{$weight}][{$attribute}]", $attributeError);
                }
            }
        }
    }

    /**
     * Skip the unique model if it belong to this model
     * @param ActiveRecord $relation
     */
    protected function skipOwnServant(ActiveRecord $relation)
    {
        foreach ($relation->getActiveValidators() as $validator) {
            if ($validator instanceof UniqueValidator && $this->isUniqueServant($relation, $validator->targetAttribute)) {
                $this->clearErrors($relation, $validator->targetAttribute);
            }
        }
    }

    /**
     * Removes errors from attributes.
     * @param ActiveRecord $relation
     * @param $targetAttributes
     */
    protected function clearErrors(ActiveRecord $relation, $targetAttributes)
    {
        if (is_string($targetAttributes)) {
            $relation->clearErrors($targetAttributes);
        }
        if (is_array($targetAttributes)) {
            foreach ($targetAttributes as $targetAttribute) {
                $this->clearErrors($relation, $targetAttribute);
            }
        }
    }

    /**
     * @param ActiveRecord $relation
     * @param $targetAttributes
     * @return bool
     */
    protected function isUniqueServant(ActiveRecord $relation, $targetAttributes)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->getModelClass();
        if (!$this->owner->isNewRecord) {
            $query = $modelClass::find();
            foreach ($this->getLink() as $servantAttr => $ownerAttr) {
                $query->andWhere(['not', [$servantAttr => $this->owner->$ownerAttr]]);
            }
            foreach ($targetAttributes as $targetAttribute) {
                $query->andWhere([$targetAttribute => $relation->$targetAttribute]);
            }
            return !$query->one();
        }
        return false;
    }

    /**
     * @return array
     */
    protected function getLink()
    {
        $getter = 'get' . $this->attribute;
        return $this->owner->$getter()->link;
    }

    /**
     * @return ActiveRecord|string
     */
    protected function getModelClass()
    {
        $getter = 'get' . $this->attribute;
        return $this->owner->$getter()->modelClass;
    }

    /**
     * Checks the relative for multiplicity
     * @return bool
     */
    protected function isMultiple()
    {
        $getter = 'get' . $this->attribute;
        return $this->owner->$getter()->multiple;
    }

    /**
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        if ($name === $this->attribute && $this->isMultiple()) {
            $values = array_filter((array)$value);
            $this->owner->populateRelation($this->attribute, array_map(function ($data, $idx) {
                return $this->createModel($data, $idx);
            }, $values, array_keys($values)));
        } elseif ($name === $this->attribute) {
            $this->owner->populateRelation($this->attribute, $this->createModel($value));
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * Creates the new model
     * @param $value
     * @param int $weight
     * @return ActiveRecord|null
     */
    protected function createModel($value, $weight = 0)
    {
        if ($value === null) {
            return null;
        }

        $modelClass = $this->getModelClass();
        /** @var ActiveRecord $model */
        $model = new $modelClass;
        $model->load($value, '');
        $model->setAttribute('weight', $weight);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $name === $this->attribute || parent::canSetProperty($name, $checkVars);
    }

}