<?php


namespace Modules\User\Domain;

use ApplicationBase\Infra\Vo\Email;
use ApplicationBase\Infra\Exceptions\{InvalidValueException};
use ApplicationBase\Infra\Enums\EntityStatusEnum;

class User
{
	use Actions;
	
	private ?Email $email;
	
	private ?EntityStatusEnum $status;
	
	/**
	 * @param int|null                     $id
	 * @param string|null                  $name
	 * @param string|null                  $password
	 * @param string|Email|null            $email
	 * @param null|string|EntityStatusEnum $status
	 *
	 * @throws InvalidValueException
	 */
	public function __construct(
		private ?int               $id = null,
		private ?string            $name = null,
		private ?string            $password = null,
		string|Email|null          $email = null,
		string|EntityStatusEnum|null $status = null
	) {
		$this->setEmail($email);

		if (is_string($status)){
			$this->status = EntityStatusEnum::tryFrom($status) ?? throw new InvalidValueException("Invalid value supplied for user status.");
		}else{
			$this->status = $status;
		}
	}

	/**
	 * @return EntityStatusEnum
	 */
	public function getStatus(): EntityStatusEnum
	{
		return $this->status;
	}
	
	/**
	 * @param EntityStatusEnum $status
	 *
	 * @return User
	 */
	public function setStatus(EntityStatusEnum $status): User
	{
		$this->status = $status;
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}
	
	/**
	 * @param string|Email|null $email
	 *
	 * @return User
	 * @throws InvalidValueException
	 */
	public function setEmail(string|Email|null $email): User
	{
		if (is_string($email)) {
			$this->email = new Email($email);
		} else {
			$this->email = $email;
		}
		
		return $this;
	}
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
	
	/**
	 * @param int|null $id
	 *
	 * @return User
	 */
	public function setId(?int $id): User
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @param string $name
	 *
	 * @return User
	 */
	public function setName(string $name): User
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}
	
	/**
	 * @param string $password
	 *
	 * @return User
	 */
	public function setPassword(string $password): User
	{
		$this->password = $password;
		return $this;
	}
	
}