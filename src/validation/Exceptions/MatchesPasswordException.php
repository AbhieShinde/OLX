<?php
namespace Olx\validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class MatchesPasswordException extends ValidationException  {
    
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Wrong Password',
        ],
    ];
}