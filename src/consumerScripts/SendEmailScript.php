<?php
require __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Olx\controllers\Tools\CEmailConsumerController as EmailConsumer;

try {
    $dotenv = Dotenv\Dotenv::create( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' );
    $dotenv->load();
} catch (\Throwable $th) {
    //throw $th;
}

$objConnection = new AMQPStreamConnection( getenv('AMQP_HOST'), getenv('AMQP_PORT'), getenv('AMQP_USERNAME'), getenv('AMQP_PASSWORD'), getenv('AMQP_VHOST') );
$objChannel    = $objConnection->channel();

list( $strQueueName, $intMessageCount, $intConsumerCount ) = $objChannel->queue_declare( EmailConsumer::QUEUE, false, true, false, false );

if( $objChannel instanceof \PhpAmqpLib\Channel\AMQPChannel ) {
    echo " [*] Consumer Script Started for '" . $strQueueName . "' queue. To exit press CTRL+C\n";
}

$callback = function ( $objRequest ) {
    echo ' [x] Received ' . $objRequest->body . "\n";

    try {

        $arrEmailData = explode( '%%', $objRequest->body );

        $strUsername = getenv( 'EMAIL' );
        $strPassword = getenv( 'PASSWORD' );

        $objTransport = ( new Swift_SmtpTransport( 'smtp.gmail.com', 587, 'TLS' ) )
          ->setUsername( $strUsername )
          ->setPassword( $strPassword );

        $objMailer = new Swift_Mailer( $objTransport );

        $objMessage = ( new Swift_Message( $arrEmailData[1] ) )
            ->setFrom( ['admin@abhieshindeolx.in' => 'system@abhieshindeolx.in'] )
            ->setTo( [ $arrEmailData[0] ] )
            ->addPart( $arrEmailData[2], 'text/html' );

        if ( array_key_exists( 3, $arrEmailData ) && is_string( $arrEmailData[3] ) ) {

            $resAttachment = Swift_Attachment::fromPath( $arrEmailData[3] );
            $resAttachment->setFilename( 'Details.txt' );

            $objMessage->attach( $resAttachment );
        }

        $objMailer->send( $objMessage );

        echo " [SUCCESS] Email has been sent to '$arrEmailData[0]'\n";

        $objRequest->delivery_info['channel']->basic_ack($objRequest->delivery_info['delivery_tag']);

        exec( "rm -r /var/www/SVPM-Baramati/XentoOlx/public/data/*" );

    } catch ( \Exception $objException ) {

        echo  " [ERROR] Unable to send email because of following error(s)\n" . $objException->getMessage() . "\n";
    }

};

$objChannel->basic_qos( null, 1, null );

$objChannel->basic_consume( EmailConsumer::QUEUE, '', false, false, false, false, $callback );

while( 0 < $intMessageCount-- ) {
    $objChannel->wait();
}

echo ' [*] No messages left in \'' . $strQueueName . '\' queue to process. Closing the consumer script.';

$objChannel->close();
$objConnection->close();