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
 * @package    Ajgl_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
class Ajgl_Controller_Action_Helper_ServiceLocatorTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Controller_Front
     */
    protected $front;

    /**
     * @var Zend_Application_Bootstrap_Bootstrap
     */
    protected $btMock;

    /**
     * @var Ajgl_Controller_Action_Helper_ServiceLocator
     */
    protected $helper;

    /**
     * @var Bisna\Service\ServiceLocator
     */
    protected $sl;

    public function setUp()
    {
        $this->front = Zend_Controller_Front::getInstance();
        $this->front->resetInstance();
        $this->front->setRequest(new Zend_Controller_Request_Http());
        $this->btMock = $this->getMock('Zend_Application_Bootstrap_Bootstrap', array(), array(), '', false);
        $this->front->setParam('bootstrap', $this->btMock);
        $this->sl = $this->getMock('Bisna\Service\ServiceLocator', array('getService'), array(), '', false);
        $this->helper = new Ajgl_Controller_Action_Helper_ServiceLocator();
    }

    public function testGetServiceLocator()
    {
        $this->btMock->expects($this->once())->method('getResource')->with($this->equalTo('servicelocator'))->will($this->returnValue($this->sl));
        $sl1 = $this->helper->getServiceLocator();
        $this->assertSame($this->sl, $sl1);
        $sl2 = $this->helper->getServiceLocator();
        $this->assertSame($this->sl, $sl2);
    }

    public function testDirect()
    {
        $serviceMock = $this->getMock('Bisna\Service\Service', array(), array(), '', false);
        $this->sl->expects($this->once())->method('getService')->with($this->equalTo('servicename'))->will($this->returnValue($serviceMock));
        $this->btMock->expects($this->once())->method('getResource')->with($this->equalTo('servicelocator'))->will($this->returnValue($this->sl));
        $s = $this->helper->direct('servicename');
        $this->assertSame($serviceMock, $s);
    }
}