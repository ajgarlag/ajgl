<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
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
 * @package    Ajgl\Controller
 * @subpackage Action\Helper\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 */
namespace Ajgl\Controller\Action\Helper;

/**
 * @category   Ajgl
 * @package    Ajgl\Controller
 * @subpackage Action\Helper\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 */
class RefererTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend_Registry
     */
    protected $previousRegistry;

    /**
     * @var Referer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Referer();
    }

    public function testGetSessionNamespace()
    {
        $sessionNamespace = $this->object->getSessionNamespace();
        $this->assertTrue($sessionNamespace instanceof \Zend_Session_Namespace);
        $this->assertEquals(
            'Ajgl\Controller\Action\Helper\Referer',
            $sessionNamespace->getNamespace()
        );
    }

    public function testSetSessionNamespace()
    {
        $sessionNamespace = new \Zend_Session_Namespace(uniqid('foo_'));
        $this->assertSame($this->object, $this->object->setSessionNamespace($sessionNamespace));
        $this->assertSame($sessionNamespace, $this->object->getSessionNamespace());
    }

    public function testSetOptions()
    {
        $sessionNamespace = new \Zend_Session_Namespace(uniqid('foo_'));
        $options = array(
            'sessionNamespace' => $sessionNamespace
        );
        $this->assertSame($this->object, $this->object->setOptions($options));
        $this->assertSame($sessionNamespace, $this->object->getSessionNamespace());
    }

    /**
     * @todo Implement testPreDispatch().
     */
    public function testPreDispatch()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGoToReferer().
     */
    public function testGoToReferer()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
