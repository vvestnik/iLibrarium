<?php

/**
 * Class for array of genres
 * extension of array()
 * is used to control, that array contaions only Genre class
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class BookGenres extends \ArrayObject {
    public function offsetSet($key, $val) {
        if ($val instanceof Genre) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be a Genre');
    }
}
