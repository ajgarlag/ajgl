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
 * @copyright  Copyright (c) 2009-2010 Compact Software International SA (http://www.Ajglnet.es)
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPLv3
 */
namespace Ajgl\Domain\Infrastructure;

use Ajgl\Domain\Infrastructure\Exception;

/**
 * Identity Map implementation
 *
 * @category   Ajgl
 * @package    Ajgl\Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (c) 2009-2010 Compact Software International SA (http://www.Ajglnet.es)
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPLv3
 */
class IdentityMap
    implements IdentityMapInterface
{
    /**
     * This array holds reference to all registered entities grouped by the
     *  root classname of the entity.
     * @var array
     */
    protected $entitiesMap = array();

    /**
     * Maps the entities identity to the object hash
     * @var array
     */
    protected $idsMap = array();

    /**
     * Adds an entity to the identity map
     * @param EntityInterface $entity
     * @return IdentityMap
     */
    public function add(EntityInterface $entity)
    {
        if (!$entity->hasIdentity()) {
            throw new Exception\InvalidArgumentException('The entity must have identity');
        }
        $id = $this->stringfyId($entity->getIdentity());
        $oid = spl_object_hash($entity);
        $classname = $entity->getRootClass();

        if (!isset($this->idsMap[$classname])) {
            $this->idsMap[$classname] = array();
        }

        if (isset($this->idsMap[$classname]) && isset($this->idsMap[$classname][$id])) {
            if (!array_key_exists($oid, $this->entitiesMap)) {
                throw new Exception\InvalidArgumentException(
                    'Another entity with the same identity exists in the identity map'
                );
            }
        } else {
            $this->idsMap[$classname][$id] = $oid;
            $this->entitiesMap[$oid] = $entity;
        }

        return $this;
    }

    /**
     * Removes an entity from the identity map
     * @param EntityInterface $entity
     * @return IdentityMap
     */
    public function remove(EntityInterface $entity)
    {
        if (!$entity->hasIdentity()) {
            throw new Exception\InvalidArgumentException('The entity must have identity');
        }
        $id = $this->stringfyId($entity->getIdentity());
        $oid = spl_object_hash($entity);
        $classname = $entity->getRootClass();

        if (!isset($this->idsMap[$classname])) {
            $this->idsMap[$classname] = array();
        }

        if (isset($this->idsMap[$classname]) && isset($this->idsMap[$classname][$id])) {
            if (!array_key_exists($oid, $this->entitiesMap)) {
                throw new Exception\InvalidArgumentException(
                    'Another entity with the same identity exists in the identity map'
                );
            }
            unset($this->entitiesMap[$oid]);
            unset($this->idsMap[$classname][$id]);
        }

        return $this;
    }

    /**
     * Checks if the given exists in the identity map
     * @param EntityInterface $entity
     * @return boolean
     */
    public function exists(EntityInterface $entity)
    {
        if (!$entity->hasIdentity()) {
            return false;
        }
        $id = $this->stringfyId($entity->getIdentity());
        $oid = spl_object_hash($entity);
        $classname = $entity->getRootClass();

        if (array_key_exists($oid, $this->entitiesMap)) {
            return true;
        } elseif (isset($this->idsMap[$classname]) && isset($this->idsMap[$classname][$id])) {
            throw new Exception\RuntimeException('Another entity with the same identity exists in the identity map');
        } else {
            return false;
        }
    }

    /**
     * Checks if the entity identified by classname and ID exists
     *
     * @param string $classname
     * @param mixed $id
     * @return boolean
     */
    public function hasEntity($classname, $id)
    {
        $id = $this->stringfyId($id);

        if (isset($this->idsMap[$classname]) && isset($this->idsMap[$classname][$id])) {
            return true;
        }

        return false;
    }

    /**
     * Returns the entity identified by classname and ID
     *
     * The given classname should be the root classname.
     * @param string $classname
     * @param mixed $id
     * @return EntityInterface
     */
    public function getEntity($classname, $id)
    {
        $id = $this->stringfyId($id);

        if (isset($this->idsMap[$classname]) && isset($this->idsMap[$classname][$id])) {
            $oid = $this->idsMap[$classname][$id];
            return $this->entitiesMap[$oid];
        }

        throw new Exception\InvalidArgumentException('The required entity does not exists');
    }

    /**
     * Transforms the id to a plain string that can be used as array key
     * @param mixed $id
     */
    protected function stringfyId($id)
    {
        return md5(json_encode($id));
    }
}