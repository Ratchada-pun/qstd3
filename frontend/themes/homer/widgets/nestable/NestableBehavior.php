<?php
namespace homer\widgets\nestable;

use homer\behaviors\NestedSetsBehavior;

class NestableBehavior extends NestedSetsBehavior
{
    /**
    * Wrapper function to be able to use the protected method of the NestedSetsBehavior
    *
    * @param integer $value
    * @param integer $depth
    */
    public function nodeMove($value, $depth) {
        $this->node = $this->owner;
        parent::moveNode($value, $depth);
    }
}