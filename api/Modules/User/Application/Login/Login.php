<?php


namespace Modules\User\Application\Login;

use ApplicationBase\Infra\{Abstracts\ControllerAbstract, Slim\Authenticator};
use ApplicationBase\Infra\Exceptions\{DatabaseException, UnauthenticatedException, NotFoundException};
use Modules\User\Domain\User;
use Psr\Http\Message\ResponseInterface;

class Login extends ControllerAbstract
{
	/**
	 * @param LoginDTO $dto
	 * @return ResponseInterface
	 * @throws DatabaseException
	 * @throws NotFoundException
	 * @throws UnauthenticatedException
	 */
	public function run(LoginDTO $dto): ResponseInterface{
		$user = User::getByLogin($dto->login);

		if ($user === null){
			throw new NotFoundException('The requested user was not found');
		}

		if (!password_verify($dto->password, $user->getPassword())){
			throw new UnauthenticatedException('Invalid Password');
		}

		global $ENV;
		$jwt = $user->getJWT();

		if (!is_null($whiteList = Authenticator::getWhiteList())){
			$whiteList::addToWhiteList($jwt, true, $ENV['JWT']['expires_at']);
		}

		return $this->replyRequest(body: [
			"token"=>$user->getJWT()
		]);
	}
}