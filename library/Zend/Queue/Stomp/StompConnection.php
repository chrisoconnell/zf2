<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Queue
 */

namespace Zend\Queue\Stomp;

/**
 * The Stomp client interacts with a Stomp server.
 *
 * @category   Zend
 * @package    Zend_Queue
 * @subpackage Stomp
 */
interface StompConnection
{
    /**
     * @param  string  $scheme ['tcp', 'udp']
     * @param  string  host
     * @param  integer port
     * @param  string  class - create a connection with this class; class must support \Zend\Queue\Stomp\StompConnection
     * @return boolean
     */
    public function open($scheme, $host, $port);

    /**
     * @param  boolean $destructor
     * @return void
     */
    public function close($destructor = false);

    /**
     * Check whether we are connected to the server
     *
     * @return true
     * @throws \Zend\Queue\Exception
     */
    public function ping();

    /**
     * write a frame to the stomp server
     *
     * example: $response = $client->write($frame)->read();
     *
     * @param  \Zend\Queue\Stomp\StompFrame $frame
     * @return $this
     */
    public function write(StompFrame $frame);

    /**
     * tests the socket to see if there is data for us
     */
    public function canRead();

    /**
     * reads in a frame from the socket or returns false.
     *
     * @return \Zend\Queue\Stomp\Frame|false
     * @throws \Zend\Queue\Exception
     */
    public function read();

    /**
     * Set the frame class to be used
     *
     * This must be a \Zend\Queue\Stomp\StompFrame.
     *
     * @param  string $class
     * @return \Zend\Queue\Stomp\StompConnection
     */
    public function setFrameClass($class);

    /**
     * Get the frameClass
     *
     * @return string
     */
    public function getFrameClass();

    /**
     * create an empty frame
     *
     * @return \Zend\Queue\Stomp\StompFrame class
     */
    public function createFrame();
}
