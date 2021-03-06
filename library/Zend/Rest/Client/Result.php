<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Rest
 */

namespace Zend\Rest\Client;

use IteratorAggregate;

/**
 * @category   Zend
 * @package    Zend_Rest
 * @subpackage Client
 */
class Result implements IteratorAggregate
{
    /**
     * @var SimpleXMLElement
     */
    protected $_sxml;

    /**
     * error information
     * @var string
     */
    protected $_errstr;

    /**
     * Constructor
     *
     * @param string $data XML Result
     * @throws \Zend\Rest\Client\Exception\ResultException
     * @return void
     */
    public function __construct($data)
    {
        set_error_handler(array($this, 'handleXmlErrors'));
        $this->_sxml = simplexml_load_string($data);
        restore_error_handler();
        if($this->_sxml === false) {
            if ($this->_errstr === null) {
                $message = "An error occured while parsing the REST response with simplexml.";
            } else {
                $message = "REST Response Error: " . $this->_errstr;
                $this->_errstr = null;
            }
            throw new Exception\ResultException($message);
        }
    }

    /**
     * Temporary error handler for parsing REST responses.
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @param array  $errcontext
     * @return true
     */
    public function handleXmlErrors($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null)
    {
        $this->_errstr = $errstr;
        return true;
    }

    /**
     * Casts a SimpleXMLElement to its appropriate PHP value
     *
     * @param SimpleXMLElement $value
     * @return mixed
     */
    public function toValue(\SimpleXMLElement $value)
    {
        $node = dom_import_simplexml($value);
        return $node->nodeValue;
    }

    /**
     * Get Property Overload
     *
     * @param string $name
     * @return null|SimpleXMLElement|array Null if not found, SimpleXMLElement if only one value found, array of \Zend\Rest\Client\Result objects otherwise
     */
    public function __get($name)
    {
        if (isset($this->_sxml->{$name})) {
            return $this->_sxml->{$name};
        }

        $result = $this->_sxml->xpath("//$name");
        $count  = count($result);

        if ($count == 0) {
            return null;
        } elseif ($count == 1) {
            return $result[0];
        } else {
            return $result;
        }
    }

    /**
     * Cast properties to PHP values
     *
     * For arrays, loops through each element and casts to a value as well.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (null !== ($value = $this->__get($method))) {
            if (!is_array($value)) {
                return $this->toValue($value);
            } else {
                $return = array();
                foreach ($value as $element) {
                    $return[] = $this->toValue($element);
                }
                return $return;
            }
        }

        return null;
    }


    /**
     * Isset Overload
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if (isset($this->_sxml->{$name})) {
            return true;
        }

        $result = $this->_sxml->xpath("//$name");

        if (count($result) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Implement IteratorAggregate::getIterator()
     *
     * @return SimpleXMLIterator
     */
    public function getIterator()
    {
        return $this->_sxml;
    }

    /**
     * Get Request Status
     *
     * @return boolean
     */
    public function getStatus()
    {
        $status = $this->_sxml->xpath('//status/text()');

        $status = strtolower($status[0]);

        if (ctype_alpha($status) && $status == 'success') {
            return true;
        } elseif (ctype_alpha($status) && $status != 'success') {
            return false;
        } else {
            return (bool) $status;
        }
    }

    public function isError()
    {
        $status = $this->getStatus();
        if ($status) {
            return false;
        } else {
            return true;
        }
    }

    public function isSuccess()
    {
        $status = $this->getStatus();
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * toString overload
     *
     * Be sure to only call this when the result is a single value!
     *
     * @return string
     */
    public function __toString()
    {
        if (!$this->getStatus()) {
            $message = $this->_sxml->xpath('//message');
            return (string) $message[0];
        } else {
            $result = $this->_sxml->xpath('//response');
            if (count($result) > 1) {
                return (string) "An error occured.";
            } else {
                return (string) $result[0];
            }
        }
    }
}
