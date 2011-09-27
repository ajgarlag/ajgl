<?php
class Ajgl_View_Helper_FormTextDateTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_View_Helper_FormTextDate
     */
    protected $_helper;
    
    public function setUp()
    {
        $this->_helper = new Ajgl_View_Helper_FormTextDate();
        $this->_helper->setView(new Zend_View());
    }
    
    public function testFormTextDate()
    {
        $date = new Zend_Date();
        $dateShort = $date->toString(Zend_Date::DATE_SHORT);
        $this->assertEquals(
            '<input type="text" name="date" id="date" value="' . $dateShort .'">',
            $this->_helper->formTextDate('date', $date)
        );
    }
}
