<?php

/**
 * Class for array of roles
 * extension of array()
 * is used to control, that array contaions only Role class
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class UserRoles extends \ArrayObject {
    public function offsetSet($key, $val) {
        if ($val instanceof Role) {
            return parent::offsetSet($key, $val);
        }
        throw new \InvalidArgumentException('Value must be an Role');
    }
}
