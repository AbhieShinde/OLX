<?php
namespace Olx\controllers\Tools;

use Olx\controllers\CBaseController;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CEmailConsumerController extends CBaseController {

    const QUEUE = 'emails';

    private $m_strRequest;
    private $m_objConnection;
    private $m_objChannel;

    public function __construct() {

        $this->m_strRequest = implode( '%%', func_get_args() );
        
        $this->m_objConnection = new AMQPStreamConnection( $_ENV['AMQP_HOST'], $_ENV['AMQP_PORT'], $_ENV['AMQP_USERNAME'], $_ENV['AMQP_PASSWORD'] );

        $this->m_objChannel = $this->m_objConnection->channel();

        $this->m_objChannel->queue_declare( self::QUEUE, false, true, false, false );
    }

    public function send() {
    
        $objMessage = new AMQPMessage(
            ( string ) $this->m_strRequest,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $this->m_objChannel->basic_publish( $objMessage, '', self::QUEUE );
    }

    public function __destruct() {

        $this->m_objChannel->close();
        $this->m_objConnection->close();
    }

}