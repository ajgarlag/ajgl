<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
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
 * @subpackage Es
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Validate\Es;

/**
 * Validate Spanish fiscal Ids
 *
 * @category   Ajgl
 * @package    Ajgl\Validate
 * @subpackage Es
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 * @see        http://es.wikibooks.org/wiki/Algoritmo_para_obtener_la_letra_del_NIF#PHP
 * @see        http://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal#Rutinas_de_c.C3.A1lculo
 */
class DniNieCif
    extends \Zend_Validate_Abstract
{
    const CHECKSUM_DNI = 'TRWAGMYFPDXBNJZSQVHLCKE';
    const CHECKSUM_CIF = 'JABCDEFGHIJ';

    const PATTERN_GLOBAL = '/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/';
    const PATTERN_DNI = '/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/';
    const PATTERN_NIE = '/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/';
    const PATTERN_NIEWITHOUCHECKSUM = '/^[T][0-9]{8}$/';
    const PATTERN_CIF = '/^[ABCDEFGHJKLMNPRQSUVW][0-9]{7}[0-9ABCDEFGHIJ]/';
    /*
      $numero = "12345678"; //asignación del número de DNI

    function letra_dni($dni)
    {
        return substr("TRWAGMYFPDXBNJZSQVHLCKE",strtr($dni,"XYZ","012")%23,1);
    }

    echo 'El DNI del DNI "'.$numero.'" es "'.$numero.letra_dni($numero).'"';
    */
    const MSG_INVALIDFORMAT = 'msgInvalidFormat';
    const MSG_UNKNOWNFORMAT = 'msgUnknownFormat';
    const MSG_DNINOTALLOWED = 'msgDniNotAllowed';
    const MSG_NIENOTALLOWED = 'msgNieNotAllowed';
    const MSG_CIFNOTALLOWED = 'msgCifNotAllowed';
    const MSG_DNIINVALIDCHECKSUM = 'msgDniInvalidChecksum';
    const MSG_NIEINVALIDCHECKSUM = 'msgNieInvalidChecksum';
    const MSG_CIFINVALIDCHECKSUM = 'msgCifInvalidChecksum';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::MSG_INVALIDFORMAT => "'%value%' is not in a valid format",
        self::MSG_UNKNOWNFORMAT  => "Unknown format",
        self::MSG_DNINOTALLOWED => "DNI values are not allowed",
        self::MSG_NIENOTALLOWED => "NIE values are not allowed",
        self::MSG_CIFNOTALLOWED => "CIF values are not allowed",
        self::MSG_DNIINVALIDCHECKSUM => "DNI checksum invalid",
        self::MSG_NIEINVALIDCHECKSUM => "NIE checksum invalid",
        self::MSG_CIFINVALIDCHECKSUM => "CIF checksum invalid",
    );

    /**
     * @var boolean
     */
    private $allowDni;

    /**
     * @var boolean
     */
    private $allowNie;

    /**
     * @var boolean
     */
    private $allowCif;

    /**
     * Class constructor
     *
     * @param boolean $allowDni
     * @param boolean $allowNie
     * @param boolean $allowCif
     */
    public function __construct($allowDni = true, $allowNie = true, $allowCif = true)
    {
        $this->allowDni = $allowDni;
        $this->allowNie = $allowNie;
        $this->allowCif = $allowCif;
        $this->_messageTemplates = $this->messageTemplates;
    }

    /**
     * @param  string  $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue((string)$value);

        if (!preg_match(self::PATTERN_GLOBAL, $value)) {
            $this->_error(self::MSG_INVALIDFORMAT);

            return false;
        }

        if (preg_match(self::PATTERN_DNI, $value)) {
            if (!$this->allowDni) {
                $this->_error(self::MSG_DNINOTALLOWED);

                return false;
            }
            $checksumDni = self::CHECKSUM_DNI;
            if ($checksumDni[substr($value, 0, -1) % 23] == substr($value, -1)) {
                return true;
            }
            $this->_error(self::MSG_DNIINVALIDCHECKSUM);

            return false;
        }

        if (preg_match(self::PATTERN_NIE, $value)) {
            if (!$this->allowNie) {
                $this->_error(self::MSG_NIENOTALLOWED);

                return false;
            }
            $checksumDni = self::CHECKSUM_DNI;
            if ($checksumDni[substr(strtr($value, 'XYZ', '012'), 0, -1) % 23] == substr($value, -1)) {
                return true;
            }
            $this->_error(self::MSG_NIEINVALIDCHECKSUM);

            return false;
        }

        if (preg_match(self::PATTERN_NIEWITHOUCHECKSUM, $value)) {
            if (!$this->allowNie) {
                $this->_error(self::MSG_NIENOTALLOWED);

                return false;
            }

            return true;
        }

        if (preg_match(self::PATTERN_CIF, $value)) {
            if (!$this->allowCif) {
                $this->_error(self::MSG_CIFNOTALLOWED);

                return false;
            }
            $type = substr($value, 0, 1);
            $identifier = substr($value, 1, 7);
            $checksum = substr($value, -1);
            $evens = 0;
            $odds = 0;

            for ($i = 1; $i < 6; $i = $i + 2) {
                $evens = $evens + $identifier[$i];
            }

            for ($i = 0; $i < 7; $i = $i + 2) {
                $partialOdd = 2 * $identifier[$i];
                $partialOdd = ($partialOdd > 9)?$partialOdd - 9:$partialOdd;
                $odds = $odds + $partialOdd;
            }

            $control = (10 - (($odds+$evens) % 10));
            $control = ($control == 10)?0:$control;
            $controlLetter = substr(self::CHECKSUM_CIF, $control, 1);

            switch ($type) {
                case 'K':
                case 'P':
                case 'Q':
                case 'S':
                    if ($controlLetter != $checksum) {
                        $this->_error(self::MSG_CIFINVALIDCHECKSUM);

                        return false;
                    }
                    break;
                case 'A':
                case 'B':
                case 'E':
                case 'H':
                    if ($control != $checksum) {
                        $this->_error(self::MSG_CIFINVALIDCHECKSUM);

                        return false;
                    }
                    break;
                default:
                    if ($control != $checksum && $controlLetter != $checksum) {
                        $this->_error(self::MSG_CIFINVALIDCHECKSUM);

                        return false;
                    }
                    break;
            }

            return true;
        }

        $this->_error(self::MSG_UNKNOWNFORMAT);

        return false;
    }
}
