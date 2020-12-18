<?php
namespace Olx\validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class CValidator {

    protected $m_arrstrErrors;

    public function validate( $objRequest, array $arrobjRules )  {
        $arrmixData = $objRequest->getParsedBody();

        foreach ( $arrobjRules as $strInputFieldName => $objRule ) {
            try {
                $objRule->setName( ucfirst( $strInputFieldName ) )->assert( $arrmixData[$strInputFieldName] );
            } catch ( NestedValidationException $e ) {
                $this->m_arrstrErrors[$strInputFieldName] = $e->getMessages();
            }
        }

        $_SESSION['errors'] = $this->m_arrstrErrors;

        return $this;
    }

    public function failed() {
        return !empty( $this->m_arrstrErrors );
    }
}