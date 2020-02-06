<?php


namespace app\modules\vocabulary;


use yii\db\ActiveQuery;

interface HierarchyInterface
{
    /**
     * Returns the first parent
     * @return ActiveQuery
     */
    public function getParent();

    /**
     * Returns all rising parents as one-dimensional array
     * @return array
     */
    public function getParents(): array;

    /**
     * Returns all parents IDs as one-dimensional array
     * @return array
     */
    public function getParentIds(): array;

    /**
     * Returns all the own children
     * @return ActiveQuery
     */
    public function getOwnChildren();

    /**
     * Returns an array of IDs of nested descendants
     * @return array
     */
    public function getNestedTree(): array;

    /**
     * Returns all offspring as one-dimensional array
     * @return array
     */
    public function getAllOffspring(): array;
}