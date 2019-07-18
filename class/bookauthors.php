<?php

/**
 * Class for array of authors
 * extension of array()
 * is used to control, that array contaions only Author class
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class BookAuthors extends \ArrayObject {
    public function offsetSet($key, $val) {
        if ($val instanceof Author) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be an Author');
    }
}
