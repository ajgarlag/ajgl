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
 * @package    Ajgl\Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Domain\Infrastructure;

/**
 * Entity interface
 * @category   Ajgl
 * @package    Ajgl\Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
interface EntityInterface
{
    /**
     * Returns true if the entity has identity
     * @return boolean
     */
    public function hasIdentity();

    /**
     * Returns the entity identity
     * @return mixed
     * @throws Exception if no identity
     */
    public function getIdentity();

    /**
     * Returns the entity properties
     * @return array
     */
    public function getProperties();

    /**
     * Returns the root class name
     * @return string
     */
    public function getRootClass();

    /**
     * Returns an associative array of properties and values
     * @return array
     */
    public function toArray();

    /**
     * Loads the properties values from an associative array of values
     * @param array $data
     * @return EntityInterface
     */
    public function fromArray(array $data);
}

