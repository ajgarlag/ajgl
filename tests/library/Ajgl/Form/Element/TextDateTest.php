<?php
class Ajgl_Form_Element_TextDateTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Form_Element_TextDate
     */
    protected $_element;

    public function setUp() {
        $this->_element = new Ajgl_Form_Element_TextDate('date');
    }

    public function testGetValueAsZendDate()
    {
        $date = '6-10-1979';
        $this->_element->setValue($date);
        $this->assertTrue($this->_element->getValueAsZendDate() instanceof Zend_Date);
        $this->assertEquals('June', $this->_element->getValueAsZendDate()->get(Zend_Date::MONTH_NAME));
        $this->assertEquals('6/10/79', $this->_element->getValueAsZendDate()->get(Zend_Date::DATE_SHORT));
        $this->_element->setValue(null);
        $this->assertNull($this->_element->getValueAsZendDate());
    }

    public function testRender()
    {
        $date = new Zend_Date(1234567890);
        $this->_element->setValue($date);
        $expected = '<dt id="date-label">&#160;</dt>' . "\n"
            . '<dd id="date-element">' . "\n"
            . '<input type="text" name="date" id="date" value="2/13/09"></dd>';
        $this->assertEquals($expected, $this->_element->render(new Zend_View()));
    }
  }