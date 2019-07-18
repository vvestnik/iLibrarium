<?php

/**
 * Class for array of images
 * extension of array()
 * is used to control, that array contaions only Image class
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Images extends \ArrayObject {
    public function offsetSet($key, $val) {
        if ($val instanceof Image) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be an Image');
    }
}
