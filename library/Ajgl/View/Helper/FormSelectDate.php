<?php
class Ajgl_View_Helper_FormSelectDate
    extends Zend_View_Helper_FormText
{
    /**
     * @param string|array $name If a string, the element name.  If an
     *  array, all other parameters are ignored, and the array elements
     *  are used in place of added parameters.
     * @param mixed $value The element value.
     * @param array $attribs Attributes for the element tag.
     * @return string The element XHTML.
     */
    public function formSelectDate($name, array $value = null, $attribs = null)
    {
        if (isset($attribs['multiple'])) {
            unset($attribs['multiple']);
        }

        $dayAttribs = $monthAttribs = array_merge($attribs, array('size' => 2));
        $yearAttribs = array_merge($attribs, array('size' => 4));

        $html = '';
        if (!isset($attribs['readonly']) || $attribs['readonly'] == false) {
            $html .= $this->view->formSelect($name.'[day]', $value['day'], $attribs, array_combine(range(1, 31), range(1,31)));
            $html .= $this->view->formSelect($name.'[month]', $value['month'], $attribs, array_combine(range(1, 12), range(1,12)));
        } else {
            $html .= $this->view->formText($name.'[day]', $value['day'], $dayAttribs);
            $html .= $this->view->formText($name.'[month]', $value['month'], $monthAttribs);
        }
        $html .= $this->view->formText($name.'[year]', $value['year'], $yearAttribs);

        return $html;
    }
}
