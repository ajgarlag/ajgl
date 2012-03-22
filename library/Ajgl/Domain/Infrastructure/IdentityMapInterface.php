<?php
/**
 * Ajgl PHP Libraries
 * Copyright (c) 2009-2010 Compact Software International SA (http://www.Ajglnet.es)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
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
 * Identity Map interface
 *
 * @category   Ajgl
 * @package    Ajgl\Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
interface IdentityMapInterface
{
    /**
     * Adds an entity to the identity map
     * @param EntityInterface $entity
     * @return IdentityMapInterface
     */
    public function add(EntityInterface $entity);

    /**
     * Removes an entity from the identity map
     * @param EntityInterface $entity
     * @return IdentityMapInterface
     */
    public function remove(EntityInterface $entity);

    /**
     * Checks if the given exists in the identity map
     * @param EntityInterface $entity
     * @return boolean
     */
    public function exists(EntityInterface $entity);

    /**
     * Checks if the entity identified by classname and ID exists
     *
     * @param string $classname
     * @param mixed $id
     * @return boolean
     */
    public function hasEntity($classname, $id);

    /**
     * Returns the entity identified by classname and ID
     *
     * @param string $classname
     * @param mixed $id
     * @return EntityInterface
     */
    public function getEntity($classname, $id);
}