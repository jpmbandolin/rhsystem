<?php

namespace Modules\User\Application\Create;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
    #[NotNull(message: "The name field should not be null.")]
    #[Length(min: 3, max: 150, minMessage: "The name field should have at least 3 characters", maxMessage: "The name field should have 150 characters at most.")]
	public ?string $name = null;
    #[NotNull(message: "The password field should not be null.")]
    #[NotCompromisedPassword(message: "Your password has been compromised according to haveibeenpwned.com, you should not use it.")]
    #[Length(
        min: 8,
        max: 60,
        minMessage: "The password field should have at least 8 characters",
        maxMessage: "The password field should have 60 characters at most"
    )]
	public ?string $password = null;
    #[NotNull(message: "The password confirmation field should not be null.")]
	public ?string $confirmPassword = null;
    #[NotNull(message: "The email field shouldn't be null.")]
    #[Email(message: "The email field should be a valid email.")]
    #[Length(max: 150, maxMessage: "The email should have 150 characters at most.")]
	public ?string $email = null;
}