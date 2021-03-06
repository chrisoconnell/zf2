<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace Zend\Form\Annotation;

use Zend\Form\Exception;

/**
 * @package    Zend_Form
 * @subpackage Annotation
 */
abstract class AbstractArrayAnnotation
{
    /**
     * @var array
     */
    protected $value;

    /**
     * Receive and process the contents of an annotation
     *
     * @param  array $data
     * @return void
     * @throws Exception\DomainException if a 'value' key is missing, or its value is not an array
     */
    public function __construct(array $data)
    {
        if (!isset($data['value']) || !is_array($data['value'])) {
            throw new Exception\DomainException(sprintf(
                '%s expects the annotation to define an array; received "%s"',
                get_class($this),
                gettype($data['value'])
            ));
        }
        $this->value = $data['value'];
    }
}
