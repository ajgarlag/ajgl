<?php
/**
 * AJ Global Libraries
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
 * @copyright  Copyright (C) 2009-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/gpl-3.0.html GPLv3
 */

/**
 * Validate Spanish fiscal Ids
 *
 * @category   Ajgl
 * @package    Ajgl_Validate
 * @copyright  Copyright (C) 2009-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @copyright  Copyright (c) 2005-2008 David Vidal Serra
 * @license    http://www.fsf.org/licensing/licenses/gpl-3.0.html GPLv3
 * @see        http://www.bulma.net/impresion.phtml?nIdNoticia=2248
 */
class Ajgl_Validate_NifNieCif extends Zend_Validate_Abstract
{

    const MSG_INVALIDFORMAT = 'msgInvalidFormat';
    const MSG_UNKNOWNERROR = 'msgUnknownError';
    const MSG_NIFNOTALLOWED = 'msgNifNotAllowed';
    const MSG_NIENOTALLOWED = 'msgNieNotAllowed';
    const MSG_CIFNOTALLOWED = 'msgCifNotAllowed';
    const MSG_NIFINVALIDCHECKSUM = 'msgNifInvalidChecksum';
    const MSG_NIEINVALIDCHECKSUM = 'msgNieInvalidChecksum';
    const MSG_CIFINVALIDCHECKSUM = 'msgCifInvalidChecksum';

    protected $_messageTemplates = array(
        self::MSG_INVALIDFORMAT => "'%value%' has not a valid format",
        self::MSG_UNKNOWNERROR  => "Unknown error",
        self::MSG_NIFNOTALLOWED => "NIF values are not allowed",
        self::MSG_NIENOTALLOWED => "NIE values are not allowed",
        self::MSG_CIFNOTALLOWED => "CIF values are not allowed",
        self::MSG_NIFINVALIDCHECKSUM => "NIF checksum invalid",
        self::MSG_NIEINVALIDCHECKSUM => "NIE checksum invalid",
        self::MSG_CIFINVALIDCHECKSUM => "CIF checksum invalid",
    );

    private $_allowNif;
    private $_allowNie;
    private $_allowCif;

    public function __construct($allowNif = true, $allowNie = true, $allowCif = true)
    {
        $this->_allowNif = $allowNif;
        $this->_allowNie = $allowNie;
        $this->_allowCif = $allowCif;
    }

    public function isValid($value)
    {
        $this->_setValue($value);

        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $value)) {
            $this->_error(self::MSG_INVALIDFORMAT);
            return false;
        }

        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $value)) {
            if ($this->_allowNif) {
                if ($value[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($value, 0, 8) % 23, 1)) {
                    return true;
                } else {
                    $this->_error(self::MSG_NIFINVALIDCHECKSUM);
                    return false;
                }
            } else {
                $this->_error(self::MSG_NIFNOTALLOWED);
                return false;
            }
        }

        $sum = $value[2] + $value[4] + $value[6];
        for ($i = 1; $i < 8; $i += 2) {
            $sum += substr((2 * $value[$i]), 0, 1) + substr((2 * $value[$i]), 1, 1);
        }
        $n = 10 - substr($sum, strlen($sum) - 1, 1);
        if (preg_match('/^[KLM]{1}/', $value)) {
            if ($this->_allowNif) {
                if ($value[8] == chr(64 + $n)) {
                    return true;
                } else {
                    $this->_error(self::MSG_NIFINVALIDCHECKSUM);
                    return false;
                }
            } else {
                $this->_error(self::MSG_NIFNOTALLOWED);
                return false;
            }
        }

        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $value)) {
            if ($this->_allowCif) {
                if ($value[8] == chr(64 + $n) || $value[8] == substr($n, strlen($n) - 1, 1)) {
                    return true;
                } else {
                    $this->_error(self::MSG_CIFINVALIDCHECKSUM);
                    return false;
                }
            } else {
                $this->_error(self::MSG_CIFNOTALLOWED);
                return false;
            }
        }

        if (preg_match('/^[T]{1}/', $value)) {
            if ($this->_allowNie) {
                if ($value[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $value)) {
                    return true;
                } else {
                    $this->_error(self::MSG_NIEINVALIDCHECKSUM);
                    return false;
                }
            } else {
                $this->_error(self::MSG_NIENOTALLOWED);
                return false;
            }
        }

        if (preg_match('/^[XYZ]{1}/', $value)) {
            if ($this->_allowNie) {
                if ($value[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X',
                                                                                          'Y',
                                                                                          'Z'),
                                                                                    array('0',
                                                                                          '1',
                                                                                          '2'),
                                                                                    $value), 0, 8)
                                                                        % 23,
                                                                        1)) {
                    return true;
                } else {
                    $this->_error(self::MSG_NIEINVALIDCHECKSUM);
                    return false;
                }
            } else {
                $this->_error(self::MSG_NIENOTALLOWED);
                return false;
            }
        }

        $this->_error(self::MSG_UNKNOWNERROR);
        return false;
    }
}