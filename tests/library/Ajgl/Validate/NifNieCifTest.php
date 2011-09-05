<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   Ajgl
 * @package    Ajgl_Validate
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2009-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/gpl-3.0.html GPLv3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Validate
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2009-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @copyright  Copyright (C) 2005-2008 David Vidal Serra
 * @license    http://www.fsf.org/licensing/licenses/gpl-3.0.html GPLv3
 * @see        http://www.bulma.net/impresion.phtml?nIdNoticia=2248
 */
class Ajgl_Validate_NifNieCifTest extends PHPUnit_Framework_TestCase
{

    public function testNifNieCif() {
        $v = new Ajgl_Validate_NifNieCif();
        $this->assertTrue($v->isValid('00000000T'));
        $this->assertTrue($v->isValid('X0686095M'));
        $this->assertTrue($v->isValid('A58579764'));
    }

    public function testDisallowNif() {
        $v = new Ajgl_Validate_NifNieCif(false, true, true);
        $this->assertFalse($v->isValid('00000000T'));
        $this->assertTrue($v->isValid('X0686095M'));
        $this->assertTrue($v->isValid('A58579764'));
    }

    public function testDisallowNie() {
        $v = new Ajgl_Validate_NifNieCif(true, false, true);
        $this->assertTrue($v->isValid('00000000T'));
        $this->assertFalse($v->isValid('X0686095M'));
        $this->assertTrue($v->isValid('A58579764'));
    }

    public function testDisallowCif() {
        $v = new Ajgl_Validate_NifNieCif(true, true, false);
        $this->assertTrue($v->isValid('00000000T'));
        $this->assertTrue($v->isValid('X0686095M'));
        $this->assertFalse($v->isValid('A58579764'));
    }
}
