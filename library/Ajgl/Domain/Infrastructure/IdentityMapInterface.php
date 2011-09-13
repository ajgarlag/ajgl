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
 * @package    Ajgl_Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Identity Map interface
 *
 * @category   Ajgl
 * @package    Ajgl_Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
interface Ajgl_Domain_Infrastructure_IdentityMapInterface
{
    /**
     * Adds an entity to the identity map
     * @param Ajgl_Domain_Infrastructure_EntityInterface $entity
     * @return Ajgl_Domain_Infrastructure_IdentityMapInterface
     */
    public function add(Ajgl_Domain_Infrastructure_EntityInterface $entity);

    /**
     * Removes an entity from the identity map
     * @param Ajgl_Domain_Infrastructure_EntityInterface $entity
     * @return Ajgl_Domain_Infrastructure_IdentityMapInterface
     */
    public function remove(Ajgl_Domain_Infrastructure_EntityInterface $entity);

    /**
     * Checks if the given exists in the identity map
     * @param Ajgl_Domain_Infrastructure_EntityInterface $entity
     * @return boolean
     */
    public function exists(Ajgl_Domain_Infrastructure_EntityInterface $entity);

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
     * @return Ajgl_Domain_Infrastructure_EntityInterface
     */
    public function getEntity($classname, $id);
}