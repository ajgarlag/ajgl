<?php
/**
 * AJ Global Libraries
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

/**
 * Plugin to render the messages registered with the flashMessenger action helper
 * @category   Ajgl
 * @package    Ajgl_Domain
 * @subpackage Infrastructure
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
abstract class Ajgl_Domain_Infrastructure_EntityAbstract
{
    /**
     * Name of entity properties
     * @var array
     */
    protected $_properties;
    
    public function getProperties()
    {
        if (!is_array($this->_properties)) {
            $this->_properties = array();
            foreach (get_object_vars($this) as $property => $value) {
                if ($this->_propertyNameIsValid($property)) {
                    $this->_properties[] = $property;
                }
            }
            sort($this->_properties);
        }
        return $this->_properties;
    }
    
    /**
     * Magic method to set a object property
     * @param string $name Name of the property
     * @param mixed $value Value of the property
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_validatePropertyName($name);
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        } else {
            if (!in_array($name, $this->getProperties())) {
               throw new Exception('Invalid property "' . $name . '"');
            }
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * Magic method to get an object property
     * @param string $name Name of the property
     * @return mixed Value of the property
     */
    public function __get($name)
    {
        $this->_validatePropertyName($name);
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            if (!in_array($name, $this->getProperties())) {
               throw new Exception('Invalid property "' . $name . '"');
            }
            return $this->$name;
        }
    }

    /**
     * Magic method to check if a property is set
     * @param string $name Name of the property
     * @return bool
     */
    public function __isset($name)
    {
        $this->_validatePropertyName($name);
        return isset($this->$name);
    }

    /**
     * Magic method to unset a property
     * @param string $name Name of the property
     * @return void
     */
    public function __unset($name)
    {
        $this->_validatePropertyName($name);
        if (isset($this->$name)) {
            $this->$name = null;
        }
    }

    /**
     * Magic method to intercepts method calls
     * @param string $method Method name
     * @param array  $arguments Array of given arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {

        //Is this a get or a set
        $prefix = strtolower(substr($method, 0, 3));

        //What is the get/set class attribute
        $property = substr($method, 3);
        $property[0] = strtolower($property[0]);

        $argc = count($arguments);

        switch ($prefix) {
            case 'get':
                if ($argc != 0) {
                    throw new Exception("Calling a getter with $arc arguments. None allowed");
                }
                return $this->__get($property);
                break;
            case 'set':
                if ($argc != 1) {
                    throw new Exception(
                        "Calling a setter with $arc arguments. Only one argument allowed"
                    );
                }
                $this->__set($property, current($arguments));
                return $this;
                break;
            default:
                throw new Exception("Calling a non get/set method that does not exist: $method");
                break;
        }
    }
    
    /**
     * Validate the property name
     * @param string $name
     * @return void
     * @throws Exception
     */
    protected function _validatePropertyName($name)
    {
        if (!$this->_propertyNameIsValid($name)) {
            throw new Exception("Invalid property name: '$name' given");
        }
    }
    
    /**
     * Checks if the given property name is valid
     * @param string $name
     * @return boolean
     */
    protected function _propertyNameIsValid($name)
    {
        if (is_string($name) && strlen($name) > 0 && preg_match('/[a-z]{1,1}/', $name[0])) {
            return true;
        }
        return false;
    }
    
    /**
     * Converts the object graph to an associative array
     * @return array
     */
    public function toArray()
    {
        $data = array();

        foreach ($this->getProperties() as $property) {
            $method = 'get' . ucfirst($property);
            $data[$property] = $this->$method();
        }
        return $data;
    }
    
    /**
     * Gets an associative array representing the object graph and load it into objects
     * @param array $data
     * @return Ajgl_Domain_Infrastructure_EntityAbstract
     */
    public function fromArray(array $data)
    {
        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);
            $this->$method($value);
        }
    }
}
