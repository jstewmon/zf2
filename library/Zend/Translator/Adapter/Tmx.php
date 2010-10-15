<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Translate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id$
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Translator\Adapter;
use Zend\Translator\Adapter as TranslationAdapter,
    Zend\Translator,
    Zend\Locale,
    Zend\Translator\Adapter\Exception\InvalidArgumentException,
    Zend\Translator\Adapter\Exception\InvalidFileTypeException;

/**
 * @uses       \Zend\Locale\Locale
 * @uses       \Zend\Translator\Adapter\Adapter
 * @uses       \Zend\Translator\Adapter\Exception\InvalidArgumentException
 * @uses       \Zend\Translator\Adapter\Exception\InvalidFileTypeException
 * @category   Zend
 * @package    Zend_Translate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Tmx extends TranslationAdapter 
{
    // Internal variables
    private $_file    = false;
    private $_useId   = true;
    private $_srclang = null;
    private $_tu      = null;
    private $_tuv     = null;
    private $_seg     = null;
    private $_content = null;
    private $_data    = array();

    /**
     * Load translation data (TMX file reader)
     *
     * @param  string  $filename  TMX file to add, full path must be given for access
     * @param  string  $locale    Locale has no effect for TMX because TMX defines all languages within
     *                            the source file
     * @param  array   $option    OPTIONAL Options to use
     * @throws \Zend\Translator\Adapter\Exception\InvalidArgumentException
     * @throws \Zend\Translator\Adapter\Exception\InvalidFileTypeException
     * @return array
     */
    protected function _loadTranslationData($filename, $locale, array $options = array())
    {
        $this->_data = array();
        if (!is_readable($filename)) {
            throw new InvalidArgumentException('Translation file \'' . $filename . '\' is not readable.');
        }

        if (isset($options['useId'])) {
            $this->_useId = (boolean) $options['useId'];
        }

        $encoding = $this->_findEncoding($filename);
        $this->_file = xml_parser_create($encoding);
        xml_set_object($this->_file, $this);
        xml_parser_set_option($this->_file, XML_OPTION_CASE_FOLDING, 0);
        xml_set_element_handler($this->_file, "_startElement", "_endElement");
        xml_set_character_data_handler($this->_file, "_contentElement");

        if (!xml_parse($this->_file, file_get_contents($filename))) {
            $ex = sprintf('XML error: %s at line %d',
                          xml_error_string(xml_get_error_code($this->_file)),
                          xml_get_current_line_number($this->_file));
            xml_parser_free($this->_file);
            throw new InvalidFileTypeException($ex);
        }

        return $this->_data;
    }

    /**
     * Internal method, called by xml element handler at start
     *
     * @param resource $file   File handler
     * @param string   $name   Elements name
     * @param array    $attrib Attributes for this element
     */
    protected function _startElement($file, $name, $attrib)
    {
        if ($this->_seg !== null) {
            $this->_content .= "<".$name;
            foreach($attrib as $key => $value) {
                $this->_content .= " $key=\"$value\"";
            }
            $this->_content .= ">";
        } else {
            switch(strtolower($name)) {
                case 'header':
                    if (empty($this->_useId) && isset($attrib['srclang'])) {
                        if (Locale\Locale::isLocale($attrib['srclang'])) {
                            $this->_srclang = Locale\Locale::findLocale($attrib['srclang']);
                        } else {
                            if (!$this->_options['disableNotices']) {
                                if ($this->_options['log']) {
                                    $this->_options['log']->notice("The language '{$attrib['srclang']}' can not be set because it does not exist.");
                                } else {
                                    trigger_error("The language '{$attrib['srclang']}' can not be set because it does not exist.", E_USER_NOTICE);
                                }
                            }

                            $this->_srclang = $attrib['srclang'];
                        }
                    }
                    break;
                case 'tu':
                    if (isset($attrib['tuid'])) {
                        $this->_tu = $attrib['tuid'];
                    }
                    break;
                case 'tuv':
                    if (isset($attrib['xml:lang'])) {
                        if (Locale\Locale::isLocale($attrib['xml:lang'])) {
                            $this->_tuv = Locale\Locale::findLocale($attrib['xml:lang']);
                        } else {
                            if (!$this->_options['disableNotices']) {
                                if ($this->_options['log']) {
                                    $this->_options['log']->notice("The language '{$attrib['xml:lang']}' can not be set because it does not exist.");
                                } else {
                                    trigger_error("The language '{$attrib['xml:lang']}' can not be set because it does not exist.", E_USER_NOTICE);
                                }
                            }

                            $this->_tuv = $attrib['xml:lang'];
                        }

                        if (!isset($this->_data[$this->_tuv])) {
                            $this->_data[$this->_tuv] = array();
                        }
                    }
                    break;
                case 'seg':
                    $this->_seg     = true;
                    $this->_content = null;
                    break;
                default:
                    break;
            }
        }
    }


    /**
     * Internal method, called by xml element handler at end
     *
     * @param resource $file   File handler
     * @param string   $name   Elements name
     */
    protected function _endElement($file, $name)
    {
        if (($this->_seg !== null) and ($name !== 'seg')) {
            $this->_content .= "</".$name.">";
        } else {
            switch (strtolower($name)) {
                case 'tu':
                    $this->_tu = null;
                    break;
                case 'tuv':
                    $this->_tuv = null;
                    break;
                case 'seg':
                    $this->_seg = null;
                    if (!empty($this->_srclang) && ($this->_srclang == $this->_tuv)) {
                        $this->_tu = $this->_content;
                    }

                    if (!empty($this->_content) or (!isset($this->_data[$this->_tuv][$this->_tu]))) {
                        $this->_data[$this->_tuv][$this->_tu] = $this->_content;
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Internal method, called by xml element handler for content
     *
     * @param resource $file File handler
     * @param string   $data Elements content
     */
    protected function _contentElement($file, $data)
    {
        if (($this->_seg !== null) and ($this->_tu !== null) and ($this->_tuv !== null)) {
            $this->_content .= $data;
        }
    }


    /**
     * Internal method, detects the encoding of the xml file
     *
     * @param string $name Filename
     * @return string Encoding
     */
    protected function _findEncoding($filename)
    {
        $file = file_get_contents($filename, null, null, 0, 100);
        if (strpos($file, "encoding") !== false) {
            $encoding = substr($file, strpos($file, "encoding") + 9);
            $encoding = substr($encoding, 1, strpos($encoding, $encoding[0], 1) - 1);
            return $encoding;
        }
        return 'UTF-8';
    }

    /**
     * Returns the adapter name
     *
     * @return string
     */
    public function toString()
    {
        return "Tmx";
    }
}
