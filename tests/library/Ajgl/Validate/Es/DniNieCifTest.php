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
 * @package    Ajgl\Validate
 * @subpackage Es\Tests
 * @copyright  Copyright (C) 2009-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/gpl-3.0.html GPLv3
 */
namespace Ajgl\Validate\Es;

/**
 * @category   Ajgl
 * @package    Ajgl\Validate
 * @subpackage Es\Tests
 * @copyright  Copyright (C) 2009-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @copyright  Copyright (C) 2005-2008 David Vidal Serra
 * @license    http://www.fsf.org/licensing/licenses/gpl-3.0.html GPLv3
 * @see        http://www.bulma.net/impresion.phtml?nIdNoticia=2248
 */
class DniNieCifTest
    extends \PHPUnit_Framework_TestCase
{

    protected $validDnis = array(
        '44055333Y',
        '84085859K',
        '21873322T',
        '68412892J',
        '73779716V',
        '88819264A',
        '50623719Y',
        '02288983T',
        '64932327S',
        '81532270F'
    );

    protected $validNies = array(
        'Y7313897A',
        'Z6141300Y',
        'X7972230Q',
        'Z3607453T',
        'X8248943Q',
        'Z7950724C',
        'Z2249875C',
        'Z8532138V',
        'Y5742304T',
        'Y0739675D'
    );

    protected $validCifs = array(
        'D31055031',
        'S4045121C',
        'F83338459',
        'A67078832',
        'S0740107H',
        'N4513511H',
        'A55161202',
        'S2592092G',
        'S9281814E',
        'M07415680'
    );

    public function testDniNieCif() {
        $v = new DniNieCif();
        foreach ($this->validDnis as $dni) {
            $this->assertTrue($v->isValid($dni));
        }
        foreach ($this->validNies as $nie) {
            $this->assertTrue($v->isValid($nie));
        }
        foreach ($this->validCifs as $cif) {
            $this->assertTrue($v->isValid($cif));
        }
    }

    public function testDisallowDni() {
        $v = new DniNieCif(false, true, true);
        foreach ($this->validDnis as $dni) {
            $this->assertFalse($v->isValid($dni));
        }
        foreach ($this->validNies as $nie) {
            $this->assertTrue($v->isValid($nie));
        }
        foreach ($this->validCifs as $cif) {
            $this->assertTrue($v->isValid($cif));
        }
    }

    public function testDisallowNie() {
        $v = new DniNieCif(true, false, true);
        foreach ($this->validDnis as $dni) {
            $this->assertTrue($v->isValid($dni));
        }
        foreach ($this->validNies as $nie) {
            $this->assertFalse($v->isValid($nie));
        }
        foreach ($this->validCifs as $cif) {
            $this->assertTrue($v->isValid($cif));
        }
    }

    public function testDisallowCif() {
        $v = new DniNieCif(true, true, false);
        foreach ($this->validDnis as $dni) {
            $this->assertTrue($v->isValid($dni));
        }
        foreach ($this->validNies as $nie) {
            $this->assertTrue($v->isValid($nie));
        }
        foreach ($this->validCifs as $cif) {
            $this->assertFalse($v->isValid($cif));
        }
    }
}
