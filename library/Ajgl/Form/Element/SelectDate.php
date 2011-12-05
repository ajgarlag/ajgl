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
 * @subpackage Element
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Form
 * @subpackage Element
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Form_Element_SelectDate
    extends Zend_Form_Element_Text
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formSelectDate';

    /**
     * @var integer
     */
    protected $_day;

    /**
     * @var integer
     */
    protected $_month;

    /**
     * @var integer
     */
    protected $_year;

    /**
     * @var type
     */
    protected $_format = Zend_Date::DATE_SHORT;

    /**
     * Initializes the element
     */
    public function init()
    {
        $this->addValidator('Date');
    }

    /**
     * @param integer $value
     * @return Ajgl_Form_Element_SelectDate
     */
    public function setDay($value)
    {
        $this->_day = (integer)$value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getDay()
    {
        return $this->_day;
    }

    /**
     * @param integer $value
     * @return Ajgl_Form_Element_SelectDate
     */
    public function setMonth($value)
    {
        $this->_month = (integer)$value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getMonth()
    {
        return $this->_month;
    }

    /**
     * @param integer $value
     * @return Ajgl_Form_Element_SelectDate
     */
    public function setYear($value)
    {
        $this->_year = (integer)$value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->_year;
    }

    /**
     * @param string $format
     * @return Ajgl_Form_Element_SelectDate
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @param mixed null|string|Zend_Locale $locale
     * @return Ajgl_Form_Element_SelectDate
     */
    public function setLocale($locale)
    {
        $this->_locale = $locale;
        return $this;
    }

    /**
     * @return mixed null|string|Zend_Locale
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     *
     * @param mixed integer|array|string|Zend_Date|DateTime $value
     * @return Ajgl_Form_Element_SelectDate
     */
    public function setValue($value)
    {
        if (is_int($value)) {
            $this->setDay(date('j', $value))
                ->setMonth(date('n', $value))
                ->setYear(date('Y', $value));
        } elseif (is_array($value)
            && (isset($value['day'])
            && isset($value['month'])
            && isset($value['year']))) {
            $this->setDay($value['day'])
                ->setMonth($value['month'])
                ->setYear($value['year']);
        } elseif (is_string($value) && Zend_Date::isDate($value, $this->getFormat(), $this->getLocale())) {
            $date = new Zend_Date($value, $this->getFormat(), $this->getLocale());
            $this->setDay($date->get(Zend_Date::DAY_SHORT))
                ->setMonth($date->get(Zend_Date::MONTH_SHORT))
                ->setYear($date->get(Zend_Date::YEAR));
        } elseif (is_object($value) && $value instanceof Zend_Date) {
            $this->setDay($value->get(Zend_Date::DAY_SHORT))
                ->setMonth($value->get(Zend_Date::MONTH_SHORT))
                ->setYear($value->get(Zend_Date::YEAR));
        } elseif (is_object($value) && $value instanceof DateTime) {
            $dateString = $value->format('j-n-Y');
            $dateArray = array_combine(array('day', 'month', 'year'), explode('-', $dateString));
            $this->setDay($dateArray['day'])
                ->setMonth($dateArray['month'])
                ->setYear($dateArray['year']);
        } else {
            throw new Exception('Invalid date value provided');
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->getValueAsArray();
    }

    /**
     * @return array
     */
    public function getValueAsArray()
    {
        return array(
            'day' => $this->getDay(),
            'month' => $this->getMonth(),
            'year' => $this->getYear()
        );
    }

    /**
     * @return Zend_Date
     */
    public function getValueAsZendDate()
    {
        return new Zend_Date($this->getValueAsArray());
    }

    /**
     * @param Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null) {

        if (null !== $view) {
            $this->setView($view);
        }

        if ($this->getView() instanceof Zend_View_Abstract) {
            $this->getView()->addHelperPath('Ajgl/View/Helper', 'Ajgl_View_Helper');
        }

        return parent::render($view);
    }
}