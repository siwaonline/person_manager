<?php
namespace Personmanager\PersonManager\ViewHelpers;

class PersonViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * @param $obj  object Object
     * @param $prop string Property
     */
    public function render($obj,$prop) {
        if(is_object($obj)) {
            return $obj->getProperty($prop);
        } elseif(is_array($obj)) {
            if(array_key_exists($prop, $obj)) {
                return $obj[$prop];
            }
        }
        return NULL;
    }
}

?>
