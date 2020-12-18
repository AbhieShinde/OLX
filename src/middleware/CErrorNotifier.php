<?php
namespace Olx\middleware;

use Olx\controllers\Tools\CEmailConsumerController;

class CErrorNotifier extends CMiddleware {

    public function __invoke( $objException, $boolIsPhpError = false ) {
        
        $strSubject = $boolIsPhpError ? 'PHP Error' : 'Slim Application Error';
        $strHtml    = '<p>The application could not run because of the following error:</p>';
        $strHtml   .= '<h2>Details</h2>';
        $strHtml   .= $this->renderHtmlExceptionOrError( $objException );

        while ( $objException = $objException->getPrevious() ) {
            $strHtml .= '<h2>Previous exception</h2>';
            $strHtml .= $this->renderHtmlExceptionOrError( $objException );
        }

        $strHtmlBody = sprintf(
            "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
            "<style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana," .
            "sans-serif;}strong{display:inline-block;width:65px;}</style></head>" .
            "<body>%s</body></html>",
            $strHtml
        );

        $objEmailConsumer = new CEmailConsumerController( 'shindea890@gmail.com', $strSubject, $strHtmlBody );
        $objEmailConsumer->send();
    }

    public function renderHtmlExceptionOrError( $objException ) {
        
        $strHtml = sprintf( '<div><strong>Type:</strong> %s</div>', get_class( $objException ) );

        if ( ( $strErrorCode = $objException->getCode() ) ) {
            $strHtml .= sprintf( '<div><strong>Code:</strong> %s</div>', $strErrorCode );
        }

        if ( ( $strMessage = $objException->getMessage() ) ) {
            $strHtml .= sprintf( '<div><strong>Message:</strong> %s</div>', htmlentities( $strMessage ) );
        }

        if ( ( $strFileName = $objException->getFile() ) ) {
            $strHtml .= sprintf( '<div><strong>File:</strong> %s</div>', $strFileName );
        }

        if ( ( $intLineNumber = $objException->getLine())) {
            $strHtml .= sprintf( '<div><strong>Line:</strong> %s</div>', $intLineNumber );
        }

        if ( ( $strStackTrace = $objException->getTraceAsString() ) ) {
            $strHtml .= '<h2>Stack Trace</h2>';
            $strHtml .= sprintf( '<pre>%s</pre>', htmlentities( $strStackTrace ) );
        }

        return $strHtml;
    }
}