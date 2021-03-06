<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Console
 */

namespace Zend\Console\Exception;

/**
 * @category   Zend
 * @package    Zend_Console_Getopt
 */
class RuntimeException
    extends \RuntimeException
    implements ExceptionInterface
{
    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '';

    /**
     * Constructor
     *
     * @param string $message
     * @param string $usage
     * @return void
     */
    public function __construct($message, $usage = '')
    {
        $this->usage = $usage;
        parent::__construct($message);
    }

    /**
     * Returns the usage
     *
     * @return string
     */
    public function getUsageMessage()
    {
        return $this->usage;
    }
}
