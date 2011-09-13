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
 * @copyright  Copyright (c) 2009-2010 Compact Software International SA (http://www.Ajglnet.es)
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPLv3
 */

/**
 * Identity Map implementation
 *
 * @category   Ajgl
 * @package    Ajgl_Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (c) 2009-2010 Compact Software International SA (http://www.Ajglnet.es)
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPLv3
 */
class Ajgl_Domain_Infrastructure_IdentityMap
    implements Ajgl_Domain_Infrastructure_IdentityMapInterface
{
    /**
     * This array holds reference to all registered entities grouped by the
     *  root classname of the entity.
     * @var array
     */
    protected $_entitiesMap = array();

    /**
     * Maps the entities identity to the object hash
     * @var array
     */
    protected $_idsMap = array();
    
    /**
     * Adds an entity to the identity map
     * @param Ajgl_Domain_Infrastructure_EntityInterface $entity
     * @return Ajgl_Domain_Infrastructure_IdentityMap
     */
    public function add(Ajgl_Domain_Infrastructure_EntityInterface $entity)
    {
        if (!$entity->hasIdentity()) {
            throw new Exception('The entity must have identity');
        }
        $id = $this->_stringfyId($entity->getIdentity());
        $oid = spl_object_hash($entity);
        $classname = $entity->getRootClass();

        if (!isset($this->_idsMap[$classname])) {
            $this->_idsMap[$classname] = array();
        }

        if (isset($this->_idsMap[$classname]) && isset($this->_idsMap[$classname][$id])) {
            if (!array_key_exists($oid, $this->_entitiesMap)) {
                throw new Exception(
                    'Another entity with the same identity exists in the identity map'
                );
            }
        } else {
            $this->_idsMap[$classname][$id] = $oid;
            $this->_entitiesMap[$oid] = $entity;
        }
        
        return $this;
    }

    /**
     * Removes an entity from the identity map
     * @param Ajgl_Domain_Infrastructure_EntityInterface $entity
     * @return Ajgl_Domain_Infrastructure_IdentityMap
     */
    public function remove(Ajgl_Domain_Infrastructure_EntityInterface $entity)
    {
        if (!$entity->hasIdentity()) {
            throw new Exception('The entity must have identity');
        }
        $id = $this->_stringfyId($entity->getIdentity());
        $oid = spl_object_hash($entity);
        $classname = $entity->getRootClass();

        if (!isset($this->_idsMap[$classname])) {
            $this->_idsMap[$classname] = array();
        }

        if (isset($this->_idsMap[$classname]) && isset($this->_idsMap[$classname][$id])) {
            if (!array_key_exists($oid, $this->_entitiesMap)) {
                throw new Exception(
                    'Another entity with the same identity exists in the identity map'
                );
            }
            unset($this->_entitiesMap[$oid]);
            unset($this->_idsMap[$classname][$id]);
        }
        
        return $this;
    }

    /**
     * Checks if the given exists in the identity map
     * @param Ajgl_Domain_Infrastructure_EntityInterface $entity
     * @return boolean
     */
    public function exists(Ajgl_Domain_Infrastructure_EntityInterface $entity)
    {
        if (!$entity->hasIdentity()) {
            return false;
        }
        $id = $this->_stringfyId($entity->getIdentity());
        $oid = spl_object_hash($entity);
        $classname = $entity->getRootClass();
        
        if (array_key_exists($oid, $this->_entitiesMap)) {
            return true;
        } elseif (isset($this->_idsMap[$classname]) && isset($this->_idsMap[$classname][$id])) {
            throw new Exception('Another entity with the same identity exists in the identity map');
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
        $id = $this->_stringfyId($id);
        
        if (isset($this->_idsMap[$classname]) && isset($this->_idsMap[$classname][$id])) {
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
     * @return Ajgl_Domain_Infrastructure_EntityInterface
     */
    public function getEntity($classname, $id)
    {
        $id = $this->_stringfyId($id);
        
        if (isset($this->_idsMap[$classname]) && isset($this->_idsMap[$classname][$id])) {
            $oid = $this->_idsMap[$classname][$id];
            return $this->_entitiesMap[$oid];
        }
  
        throw new Exception('The required entity does not exists');
    }
    
    /**
     * Transforms the id to a plain string that can be used as array key
     * @param mixed $id 
     */
    protected function _stringfyId($id)
    {
        return md5(json_encode($id));
    }
}