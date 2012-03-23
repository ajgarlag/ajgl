<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   Ajgl
 * @package    Ajgl\Form
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
namespace Ajgl\Form;

/**
 * @category   Ajgl
 * @package    Ajgl\Form
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
class MultiPageTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MultiPage
     */
    protected $form;

    protected function setUp()
    {
        $this->form = new MultiPage();
        $this->form->addElement(
            new \Zend_Form_Element_Textarea('textarea', array('value' => 'textareaValue', 'required' => true))
        );
        $this->form->addElement(
            new \Zend_Form_Element_Text(
                'text',
                array(
                    'value' => 'textValue',
                    'validators' => array(
                        array('stringLength', false, array(6, 20))
                    )
                )
            )
        );
        $this->form->addElement(
            new \Zend_Form_Element_Hidden('hidden', array('value' => 'hiddenValue'))
        );
        $this->form->addDisplayGroup(array('textarea', 'hidden'),'displayGroup');
        $this->form->addSubForm(
            new \Zend_Form_SubForm(
                array(
                    'elements' => array(
                        'username' => array(
                            'text',
                            array(
                                'value' => 'usernameValue',
                                'validators' => array(
                                    'alnum',
                                    array('regex', false, array('/^[a-z]/i')),
                                    array('stringLength', false, array(6, 20))
                                ),
                                'required' => true,
                                'filters'  => array('StringToLower')
                            )
                        ),
                        'password' => array(
                            'password',
                            array(
                                'value' => 'passwordValue',
                                'validators' => array(
                                    array('stringLength', false, array(6))
                                ),
                                'required' => true,
                            )
                        )
                    )
                )
             ),
            'subform1'
        );
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testGetSessionNamespace()
    {
        $this->assertTrue($this->form->getSessionNamespace() instanceof \Zend_Session_Namespace);
    }

    public function testSetSessionNamespace()
    {
        $sn = new \Zend_Session_Namespace(get_class($this));
        $f = $this->form->setSessionNamespace($sn);
        $this->assertSame($this->form, $f);
        $this->assertSame($sn, $this->form->getSessionNamespace());
    }

    public function testGetNextButton()
    {
        $next = $this->form->getNextButton();
        $this->assertTrue($next instanceof \Zend_Form_Element_Submit);
        $this->assertNull($next->getValue());
        $this->assertTrue($next->getIgnore());
    }

    public function testSetNextButton()
    {
        $next = new \Zend_Form_Element_Submit('nextTest');
        $next->setIgnore(false);
        $f = $this->form->setNextButton($next);
        $this->assertSame($this->form, $f);
        $this->assertSame($next, $this->form->getNextButton());
        $this->assertTrue($next->getIgnore());
    }

    public function testGetPotentialPage()
    {
        $expected = array('text', 'displayGroup', 'subform1');
        $array = $this->form->getPotentialPages();
        $this->assertEquals($expected, $array);
    }

    public function testGetStoredPageIsEmpty()
    {
        $this->assertEquals(0, count($this->form->getStoredPages()));
    }

    public function testGetPageWithElement()
    {
        $sf = $this->form->getPage('text');
        $this->assertTrue($sf instanceof \Zend_Form_SubForm);
        $this->assertEquals(2, count($sf));
        $text = $sf->current();
        $this->assertTrue($text instanceof \Zend_Form_Element_Text);
        $this->assertSame($this->form->text, $text);
        $sf->next();
        $next = $sf->{$sf->key()};
        $this->assertTrue($next instanceof \Zend_Form_Element_Submit);
        $this->assertEquals('Next', $next->getLabel());
    }

    public function testGetPageWithDisplayGroup()
    {
        $sf = $this->form->getPage('displayGroup');
        $this->assertTrue($sf instanceof \Zend_Form_SubForm);
        $this->assertEquals(2, count($sf));
        $dg = $sf->current();
        $this->assertTrue($dg instanceof \Zend_Form_DisplayGroup);
        $this->assertSame($this->form->displayGroup, $dg);
        $this->assertEquals(2, count($sf->{$sf->getName()}));
        $this->assertEquals(2, count($sf));
        $sf->next();
        $next = $sf->{$sf->key()};
        $this->assertTrue($next instanceof \Zend_Form_Element_Submit);
        $this->assertEquals('Next', $next->getLabel());
    }

    public function testGetPageWithSubForm()
    {
        $sf = $this->form->getPage('subform1');
        $this->assertTrue($sf instanceof \Zend_Form_SubForm);
        $this->assertEquals(2, count($sf));
        $sf->next();
        $sf->next();
        $this->assertFalse($sf->valid());
    }

    public function testPageIsValid()
    {
        $data = array('text' => array('text' => 'Text'));
        $this->assertFalse($this->form->pageIsValid($data));
        $data = array('text' => array('text' => 'Long text'));
        $this->assertTrue($this->form->pageIsValid($data));
    }


    public function testPageIsValidStoresValidValues()
    {
        $data = array('text' => array('text' => 'Long text'));
        $this->assertTrue($this->form->pageIsValid($data));
        $stored = array();
        foreach ($this->form->getSessionNamespace() as $key => $value) {
            $stored[$key] = $value;
        }
    }

    public function testPageIsValidDoNotStoresInvalidValues()
    {
        $data = array('text' => array('text' => 'Text'));
        $this->assertFalse($this->form->pageIsValid($data));
        foreach ($this->form->getSessionNamespace() as $key => $value) {
            $this->fail("SessionNamespace not empty");
        }
    }

    public function testGetNextPage()
    {
        $sf = $this->form->getNextPage();
        $this->assertEquals('text', $sf->getName());
        $data = array('text' => array('text' => 'Text'));
        $this->assertFalse($this->form->pageIsValid($data));
        $sf = $this->form->getNextPage();
        $this->assertEquals('text', $sf->getName());
        $data = array('text' => array('text' => 'Long text'));
        $this->assertTrue($this->form->pageIsValid($data));
        $sf = $this->form->getNextPage();
        $this->assertEquals('displayGroup', $sf->getName());
        $data = array('displayGroup' => array('textarea' => 'Text area text'));
        $this->assertTrue($this->form->pageIsValid($data));
        $sf = $this->form->getNextPage();
        $this->assertEquals('subform1', $sf->getName());
        $data = array('subform1' => array('username' => 'Usernamevalue', 'password' => 'Password Value'));
        $this->assertTrue($this->form->pageIsValid($data));
        $this->assertNull($this->form->getNextPage());
    }

    public function testAreStoredValuesValid()
    {
        $this->assertFalse($this->form->areStoredValuesValid());

        $sf = $this->form->getNextPage();
        $data = array('text' => array('text' => 'Long text'));
        $this->assertTrue($this->form->pageIsValid($data));
        $this->assertFalse($this->form->areStoredValuesValid());

        $sf = $this->form->getNextPage();
        $data = array('displayGroup' => array('hidden' => 'Hidden text'));
        $this->assertFalse($this->form->pageIsValid($data));
        $this->assertFalse($this->form->areStoredValuesValid());

        $data = array('displayGroup' => array('textarea' => 'Text area text'));
        $this->assertTrue($this->form->pageIsValid($data));
        $this->assertFalse($this->form->areStoredValuesValid());

        $sf = $this->form->getNextPage();
        $data = array('subform1' => array('username' => 'Username Value', 'password' => 'Password Value'));
        $this->assertFalse($this->form->pageIsValid($data));
        $this->assertFalse($this->form->areStoredValuesValid());

        $data = array('subform1' => array('username' => 'Usernamevalue', 'password' => 'Password Value'));
        $this->assertTrue($this->form->pageIsValid($data));
        $this->assertTrue($this->form->areStoredValuesValid());
    }
}
