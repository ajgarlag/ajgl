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
 * @package    Ajgl_Form
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Plugin to render the messages registered with the flashMessenger action helper
 * @category   Ajgl
 * @package    Ajgl_Form
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Form_MultiPage
    extends Zend_Form
{
    /**
     * @var Zend_Session_Namespace
     */
    protected $_sessionNamespace;
    
    /**
     * @var Zend_Form_Element_Submit
     */
    protected $_nextButton;

    /**
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace()
    {
        if ($this->_sessionNamespace === null) {
            $this->_sessionNamespace = new Zend_Session_Namespace('MPF_' . md5(get_class($this)));
        }
        
        return $this->_sessionNamespace;
    }

    /**
     * @param Zend_Session_Namespace $sessionNamespace
     * @return Ajgl_Form_MultiPage 
     */
    public function setSessionNamespace(Zend_Session_Namespace $sessionNamespace)
    {
        $this->_sessionNamespace = $sessionNamespace;
        return $this;
    }
    
    /**
     * @return Zend_Form_Element_Submit
     */
    public function getNextButton()
    {
        if ($this->_nextButton === null) {
            $this->_nextButton = new Zend_Form_Element_Submit(
                'next_'. md5(get_class($this)),
                array(
                    'label'    => 'Next',
                    'required' => false,
                    'ignore'   => true,
                )
            );
        }
        return $this->_nextButton;
    }

    /**
     * @param Zend_Form_Element_Submit $button
     * @return Ajgl_Form_MultiPage 
     */
    public function setNextButton(Zend_Form_Element_Submit $button)
    {
        $button->setIgnore(true);
        $this->_nextButton = $button;
        return $this;
    }


    /**
     * Prepare an item for display as a form
     *
     * @param  string $spec
     * @return Zend_Form_SubForm
     */
    public function getPage($spec)
    {
        if (!is_string($spec)) {
            throw new Exception(
                'Invalid argument passed to ' . __FUNCTION__ . '()'
            );
        }

        if ($item = $this->{$spec}) {
            if ($item instanceof Zend_Form_Element) {
                $subForm = $this->_elementToSubFormPage($item);
            } elseif ($item instanceof Zend_Form_DisplayGroup) {
                $subForm = $this->_displayGroupToSubFormPage($item);
            } elseif ($item instanceof Zend_Form_SubForm) {
                $subForm = $item;
            } else {
                throw new Exception(
                    "Unknown spec class: '" . get_class($item) . "'"
                );
            }
            
            $prepareMethod = '_preparePage' . ucfirst($spec);
            if (method_exists($this, $prepareMethod)) {
                call_user_func(array($this, $prepareMethod), $subForm);
            }
        
            if (!$subForm->getName()) {
                $subForm->setName($spec);
            }
            
            $this->_setPageDecorators($subForm)
                ->_setPageAction($subForm)
                ->_setPageMethod($subForm);

            if (!$this->_isLastSpec($spec)) {
                $this->_setPageNextButton($subForm);
            }
            
            return $subForm;
            
        } else {
            throw new Exception(
                "Invalid spec name: '$spec'"
            );
        }
        
        


    }

    /**
     * @param Zend_Form_Element $element
     * @return Zend_Form_SubForm 
     */
    protected function _elementToSubFormPage(Zend_Form_Element $element)
    {
        $subForm = new Zend_Form_SubForm();
        $subForm->addElement($element);
        return $subForm;
    }

    /**
     * @param Zend_Form_DisplayGroup $group
     * @return Zend_Form_SubForm 
     */
    protected function _displayGroupToSubFormPage(Zend_Form_DisplayGroup $group)
    {
        $subForm = new Zend_Form_SubForm();
        foreach ($group as $element) {
            $subForm->addElement($element);
        }
        $subForm->addDisplayGroups(array($group));
        return $subForm;
    }
    
    /**
     * Add form decorators to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return Ajgl_Form_MultiPage
     */
    protected function _setPageDecorators(Zend_Form_SubForm $subForm)
    {
        $subForm->setDecorators(
            array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
                'Form',
            )
        );
        return $this;
    }
    
    /**
     * @param Zend_Form_SubForm $subForm
     * @return Ajgl_Form_MultiPage 
     */
    protected function _setPageNextButton(Zend_Form_SubForm $subForm)
    {
        $subForm->addElement($this->getNextButton());
        return $this;
    }

    /**
     * @param Zend_Form_SubForm $subForm
     * @return Ajgl_Form_MultiPage 
     */
    protected function _setPageAction(Zend_Form_SubForm $subForm)
    {
        $subForm->setAction($this->getAction());
        return $this;
    }

    /**
     * @param Zend_Form_SubForm $subForm
     * @return Ajgl_Form_MultiPage 
     */
    protected function _setPageMethod(Zend_Form_SubForm $subForm)
    {
        $subForm->setMethod($this->getMethod());
        return $this;
    }
    
    /**
     * @param string $spec 
     * @return boolean
     */
    protected function _isFirstSpec($spec)
    {
        $specs = $this->getPotentialPages();
        if (!in_array($spec, $specs)) {
            throw new Exception(
                "Invalid spec name: '$spec'"
            );
        }
        
        if (array_shift($specs) == $spec) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param string $spec 
     * @return boolean
     */
    protected function _isLastSpec($spec)
    {
        $specs = $this->getPotentialPages();
        if (!in_array($spec, $specs)) {
            throw new Exception(
                "Invalid spec name: '$spec'"
            );
        }
        
        if (array_pop($specs) == $spec) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @return array
     */
    public function getStoredPages()
    {
        $stored = array();
        foreach ($this->getSessionNamespace() as $key => $value) {
            $stored[] = $key;
        }
 
        return $stored;
    }
    
    /**
     *
     * @return array
     */
    public function getPotentialPages()
    {
        $this->_sort();
        return array_keys($this->_order);
    }
    
    /**
     * @return mixed Zend_Form_Subform|null
     */    
    public function getNextPage()
    {
        $storedPages = $this->getStoredPages();
        $potentialPages = $this->getPotentialPages();
        
        foreach ($potentialPages as $page) {
            if (!in_array($page, $storedPages)) {
                return $this->getPage($page);
            }
        }
        
        return null;
    }
 
    /**
     * @param string $spec
     * @param array $data
     * @return boolean
     */
    public function pageIsValid(array $data)
    {
        if (count($data) != 1) {
            throw new Exception("Invalid data array count: '".count($data)."'");
        }
        $spec = current(array_keys($data));
        
        if ($item = $this->{$spec}) {
            $subForm = $this->getPage($spec);
            if ($subForm->isValid($data)) {
                $this->getSessionNamespace()->$spec = $subForm->getValues();
                return true;
            }
        } else {
            throw new Exception(
                "Invalid spec name: '$spec'"
            );
        }
        return false;
    }
    
    /**
     * @return array
     */
    public function getStoredValues()
    {
        $data = array();
        foreach ($this->getSessionNamespace() as $key => $value) {
            $item = $this->{$key};
            $value = current($value);
            if ($item instanceof Zend_Form_Element) {
                $data[$item->getName()] = current($value);
            } elseif ($item instanceof Zend_Form_DisplayGroup) {
                foreach ($value as $k => $v) {
                    $data[$k] = $v;
                }
            } elseif ($item instanceof Zend_Form_SubForm) {
                $data[$item->getName()] = $value;
            } else {
                throw new Exception(
                    "Unknown spec class '" . get_class($item) . "' for page $key"
                );
            }
        }
        return $data;
    }
    
    /**
     * @return boolean
     */
    public function areStoredValuesValid()
    {
        return (
            count($this->getStoredPages()) < count($this->getPotentialPages())
        )? false : parent::isValid($this->getStoredValues());
    }
    
    public function unsetStoredValues()
    {
        $this->getSessionNamespace()->unsetAll();
    }
}
