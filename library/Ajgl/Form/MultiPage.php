<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
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
 * @package    Ajgl\Form
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Form;

use Ajgl\Form\Exception;

/**
 * @category   Ajgl
 * @package    Ajgl\Form
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class MultiPage
    extends \Zend_Form
{
    /**
     * @var \Zend_Session_Namespace
     */
    protected $sessionNamespace;

    /**
     * @var \Zend_Form_Element_Submit
     */
    protected $nextButton;

    /**
     * @return \Zend_Session_Namespace
     */
    public function getSessionNamespace()
    {
        if ($this->sessionNamespace === null) {
            $this->sessionNamespace = new \Zend_Session_Namespace('MPF_' . md5(get_class($this)));
        }

        return $this->sessionNamespace;
    }

    /**
     * @param \Zend_Session_Namespace $sessionNamespace
     * @return MultiPage
     */
    public function setSessionNamespace(\Zend_Session_Namespace $sessionNamespace)
    {
        $this->sessionNamespace = $sessionNamespace;
        return $this;
    }

    /**
     * @return \Zend_Form_Element_Submit
     */
    public function getNextButton()
    {
        if ($this->nextButton === null) {
            $this->nextButton = new \Zend_Form_Element_Submit(
                'next_'. md5(get_class($this)),
                array(
                    'label'    => 'Next',
                    'required' => false,
                    'ignore'   => true,
                )
            );
        }
        return $this->nextButton;
    }

    /**
     * @param \Zend_Form_Element_Submit $button
     * @return MultiPage
     */
    public function setNextButton(\Zend_Form_Element_Submit $button)
    {
        $button->setIgnore(true);
        $this->nextButton = $button;
        return $this;
    }


    /**
     * Prepare an item for display as a form
     *
     * @param  string $spec
     * @return \Zend_Form_SubForm
     */
    public function getPage($spec)
    {
        if (!is_string($spec)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument passed to ' . __FUNCTION__ . '()'
            );
        }

        if ($item = $this->{$spec}) {
            if ($item instanceof \Zend_Form_Element) {
                $subForm = $this->elementToSubFormPage($item);
            } elseif ($item instanceof \Zend_Form_DisplayGroup) {
                $subForm = $this->displayGroupToSubFormPage($item);
            } elseif ($item instanceof \Zend_Form_SubForm) {
                $subForm = $item;
            } else {
                throw new Exception\InvalidArgumentException(
                    "Unknown spec class: '" . get_class($item) . "'"
                );
            }

            $prepareMethod = 'preparePage' . ucfirst($spec);
            if (method_exists($this, $prepareMethod)) {
                call_user_func(array($this, $prepareMethod), $subForm);
            }

            if (!$subForm->getName()) {
                $subForm->setName($spec);
            }

            $this->setPageDecorators($subForm)
                ->setPageAction($subForm)
                ->setPageMethod($subForm);

            if (!$this->isLastSpec($spec)) {
                $this->setPageNextButton($subForm);
            }

            return $subForm;

        } else {
            throw new Exception\InvalidArgumentException(
                "Invalid spec name: '$spec'"
            );
        }




    }

    /**
     * @param \Zend_Form_Element $element
     * @return \Zend_Form_SubForm
     */
    protected function elementToSubFormPage(\Zend_Form_Element $element)
    {
        $subForm = new \Zend_Form_SubForm();
        $subForm->addElement($element);
        return $subForm;
    }

    /**
     * @param \Zend_Form_DisplayGroup $group
     * @return \Zend_Form_SubForm
     */
    protected function displayGroupToSubFormPage(\Zend_Form_DisplayGroup $group)
    {
        $subForm = new \Zend_Form_SubForm();
        foreach ($group as $element) {
            $subForm->addElement($element);
        }
        $subForm->addDisplayGroups(array($group));
        return $subForm;
    }

    /**
     * Add form decorators to an individual sub form
     *
     * @param  \Zend_Form_SubForm $subForm
     * @return Multipage
     */
    protected function setPageDecorators(\Zend_Form_SubForm $subForm)
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
     * @param \Zend_Form_SubForm $subForm
     * @return Multipage
     */
    protected function setPageNextButton(\Zend_Form_SubForm $subForm)
    {
        $subForm->addElement($this->getNextButton());
        return $this;
    }

    /**
     * @param \Zend_Form_SubForm $subForm
     * @return Multipage
     */
    protected function setPageAction(\Zend_Form_SubForm $subForm)
    {
        $subForm->setAction($this->getAction());
        return $this;
    }

    /**
     * @param \Zend_Form_SubForm $subForm
     * @return Multipage
     */
    protected function setPageMethod(\Zend_Form_SubForm $subForm)
    {
        $subForm->setMethod($this->getMethod());
        return $this;
    }

    /**
     * @param string $spec
     * @return boolean
     */
    protected function isFirstSpec($spec)
    {
        $specs = $this->getPotentialPages();
        if (!in_array($spec, $specs)) {
            throw new Exception\InvalidArgumentException(
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
    protected function isLastSpec($spec)
    {
        $specs = $this->getPotentialPages();
        if (!in_array($spec, $specs)) {
            throw new Exception\InvalidArgumentException(
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
     * @return mixed \Zend_Form_Subform|null
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
            throw new Exception\InvalidArgumentException("Invalid data array count: '".count($data)."'");
        }
        $spec = current(array_keys($data));

        if ($item = $this->{$spec}) {
            $subForm = $this->getPage($spec);
            if ($subForm->isValid($data)) {
                $this->getSessionNamespace()->$spec = $subForm->getValues();
                return true;
            }
        } else {
            throw new Exception\InvalidArgumentException(
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
            if ($item instanceof \Zend_Form_Element) {
                $data[$item->getName()] = current($value);
            } elseif ($item instanceof \Zend_Form_DisplayGroup) {
                foreach ($value as $k => $v) {
                    $data[$k] = $v;
                }
            } elseif ($item instanceof \Zend_Form_SubForm) {
                $data[$item->getName()] = $value;
            } else {
                throw new Exception\RuntimeException(
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
