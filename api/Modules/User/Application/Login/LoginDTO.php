<?php


namespace Modules\User\Application\Login;


use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class LoginDTO extends DTOAbstract
{
    #[Length(
        min: 5,
        max: 180,
        minMessage: "The login field should have at least 5 characters",
        maxMessage: "The login field should have 180 characters at most"
    )]
    #[NotNull(message: "The login field must not be null")]
	public ?string $login = null;

    #[Length(
        min: 8,
        max: 60,
        minMessage: "The password field should have at least 8 characters",
        maxMessage: "The password field should have 60 characters at most")]
    #[NotNull(message: "The password field should not be null")]
	public ?string $password = null;
}