<?php
class Ajgl_View_Helper_FormTextDate 
    extends Zend_View_Helper_FormText
{
    /**
     * Generates a 'text' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formTextDate($name, $value = null, $attribs = null)
    {
        if ($value instanceof Zend_Date) {
            $value = $value->toString(Zend_Date::DATE_SHORT);
        }
        return parent::formText($name, $value, $attribs);
    }
}
