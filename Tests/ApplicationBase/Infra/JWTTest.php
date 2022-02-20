<?php

namespace ApplicationBase\Infra;

use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
	public function setUp(): void
	{
		global $ENV;
		$ENV = [
			'JWT'=>[
				'expires_at' => 1000,
				'key'=>'12345'
			]
		];

		parent::setUp();
	}

	/**
	 * @throws Exceptions\UnauthenticatedException
	 */
	public function testJWTClass(): void
	{
		$jwt = JWT::generateJWT(['test'=>true]);
		$this->assertIsString($jwt);

		$decodedJWT = JWT::getJWTPayload($jwt);
		$this->assertIsArray($decodedJWT);
		$this->assertIsBool($decodedJWT['test']);
		$this->assertEquals(true, $decodedJWT['test']);
	}
}
