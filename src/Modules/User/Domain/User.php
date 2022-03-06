<?php


namespace Modules\User\Domain;

use ApplicationBase\Infra\Enums\PermissionEnum;
use ApplicationBase\Infra\Exceptions\{DatabaseException, InvalidValueException};
use ApplicationBase\Infra\JWT;
use ApplicationBase\Infra\Vo\Email;
use Modules\User\Infra\UserRepository;

class User
{
	private ?Email $email;
	/**
	 * @var PermissionEnum[]
	 */
	private array $permissions = [];

	/**
	 * @param int|null $id
	 * @param string|null $name
	 * @param string|null $password
	 * @param string|Email|null $email
	 * @throws InvalidValueException
	 */
	public function __construct(
		private ?int $id = null,
		private ?string $name = null,
		private ?string $password = null,
		string|Email|null $email = null
	){
		$this->setEmail($email);
	}

	/**
	 * @return PermissionEnum[]
	 * @throws DatabaseException
	 */
	public function getPermissions():array{
		if (!count($this->permissions)){
			$this->permissions = UserRepository::getPermissions($this);
		}

		return $this->permissions;
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
	 * @return User
	 * @throws InvalidValueException
	 */
	public function setEmail(string|Email|null $email): User
	{
		if (is_string($email)){
			$this->email = new Email($email);
		}else{
			$this->email = $email;
		}

		return $this;
	}

	/**
	 * @return $this
	 * @throws DatabaseException
	 */
	public function save():self{
		return $this->setId(UserRepository::save($this));
	}

	/**
	 * @param int $id
	 * @return static|null
	 * @throws DatabaseException
	 */
	public static function getById(int $id): ?self{
		return UserRepository::getById($id);
	}

	/**
	 * @param string $login
	 * @return static|null
	 * @throws DatabaseException
	 */
	public static function getByLogin(string $login): ?self{
		return UserRepository::getByLogin($login);
	}

	/**
	 * @return User[]
	 * @throws DatabaseException
	 */
	public static function getAll(): array{
		return UserRepository::getAll();
	}

	/**
	 * @return string
	 * @throws DatabaseException
	 */
	public function getJWT():string{
		return JWT::generateJWT([
			"id"            => $this->getId(),
			"name"          => $this->getName(),
			"email"         => $this->getEmail(),
			"permissions"   => array_map(static function (PermissionEnum $enum){
				return $enum->value;
			}, $this->getPermissions())
		]);
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
	 * @return User
	 */
	public function setPassword(string $password): User
	{
		$this->password = $password;
		return $this;
	}

}