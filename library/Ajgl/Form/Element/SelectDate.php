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
 * @package    Ajgl\Form
 * @subpackage Element
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Form\Element;

use Ajgl\Form\Exception;

/**
 * @category   Ajgl
 * @package    Ajgl\Form
 * @subpackage Element
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class SelectDate
    extends \Zend_Form_Element_Text
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formSelectDate';

    /**
     * @var integer
     */
    protected $day;

    /**
     * @var integer
     */
    protected $month;

    /**
     * @var integer
     */
    protected $year;

    /**
     * @var type
     */
    protected $format = \Zend_Date::DATE_SHORT;

    /**
     *
     * @var null|string|\Zend_Locale
     */
    protected $locale;

    /**
     * Initializes the element
     */
    public function init()
    {
        $this->addValidator('Date');
    }

    /**
     * @param integer $value
     * @return SelectDate
     */
    public function setDay($value)
    {
        $this->day = (integer)$value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param integer $value
     * @return SelectDate
     */
    public function setMonth($value)
    {
        $this->month = (integer)$value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param integer $value
     * @return SelectDate
     */
    public function setYear($value)
    {
        $this->year = (integer)$value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $format
     * @return SelectDate
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed null|string|\Zend_Locale $locale
     * @return SelectDate
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return mixed null|string|\Zend_Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     *
     * @param mixed array|string|\Zend_Date|\DateTime $value
     * @return SelectDate
     */
    public function setValue($value)
    {
        if (is_array($value)
            && (isset($value['day'])
            && isset($value['month'])
            && isset($value['year']))) {
            $this->setDay($value['day'])
                ->setMonth($value['month'])
                ->setYear($value['year']);
        } elseif (is_string($value) && \Zend_Date::isDate($value, $this->getFormat(), $this->getLocale())) {
            $date = new \Zend_Date($value, $this->getFormat(), $this->getLocale());
            $this->setDay($date->get(\Zend_Date::DAY_SHORT))
                ->setMonth($date->get(\Zend_Date::MONTH_SHORT))
                ->setYear($date->get(\Zend_Date::YEAR));
        } elseif (is_object($value) && $value instanceof \Zend_Date) {
            $this->setDay($value->get(\Zend_Date::DAY_SHORT))
                ->setMonth($value->get(\Zend_Date::MONTH_SHORT))
                ->setYear($value->get(\Zend_Date::YEAR));
        } elseif (is_object($value) && $value instanceof \DateTime) {
            $dateString = $value->format('j-n-Y');
            $dateArray = array_combine(array('day', 'month', 'year'), explode('-', $dateString));
            $this->setDay($dateArray['day'])
                ->setMonth($dateArray['month'])
                ->setYear($dateArray['year']);
        } elseif ($value === null) {
            $this->setDay($value)->setMonth($value)->setYear($value);
        } else {
            throw new Exception\InvalidArgumentException('Invalid date value provided');
        }

        return $this;
    }

    /**
     * @return mixed array|null
     */
    public function getValue()
    {
        return $this->getValueAsArray();
    }

    /**
     * @return mixed array|null
     */
    public function getValueAsArray()
    {
        if (empty($this->day) && empty($this->month) && empty($this->year)) {
            return null;
        } else {
            return array(
                'day' => $this->getDay(),
                'month' => $this->getMonth(),
                'year' => $this->getYear()
            );
        }
    }

    /**
     * @return mixed \Zend_Date|null
     */
    public function getValueAsZendDate()
    {
        $dateArray = $this->getValueAsArray();
        if ($dateArray === null) {
            return null;
        } else {
            return new \Zend_Date($dateArray);
        }
    }

    /**
     * @param \Zend_View_Interface $view
     * @return string
     */
    public function render(\Zend_View_Interface $view = null)
    {

        if (null !== $view) {
            $this->setView($view);
        }

        if ($this->getView() instanceof \Zend_View_Abstract) {
            $this->getView()->addHelperPath('Ajgl/View/Helper', 'Ajgl\View\Helper');
        }

        return parent::render($view);
    }
}