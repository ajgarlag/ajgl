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
 * @package    Ajgl_Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
/**
 * Zend datetime type for doctrine 2
 * @author     Bostjan Oblak <bostjan@muha.cc>
 * @category   Ajgl
 * @package    Ajgl_Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @copyright  Copyright (C) 2010 Bostjan Oblak <bostjan@muha.cc>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 * @see        http://web.archive.org/web/20100908224049/http://bostjan.muha.cc/2010/08/datetime-type-in-doctrine-2/
 */
class ZendDateTime extends \Doctrine\DBAL\Types\DateTimeType {
    /**
     * @var string
     */
    const TYPE = 'zenddatetime';

    /**
     * @return string
     */
    public function getName() {
        return self::TYPE;
    }

    /**
     * @param \Zend_Date|null $value
     * @param AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        if ($value === null) {
            return null;
        }
        \Zend_Date::setOptions(array('format_type' => 'php'));
        return $value->toString($platform->getDateTimeFormatString());
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return \Zend_Date 
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        $value = parent::convertToPHPValue($value, $platform);
        if ($value === null) {
            return null;
        }
        $val = new \Zend_Date($value->format(DATE_RFC822), \Zend_Date::RFC_2822);
        return $val;
    }

}