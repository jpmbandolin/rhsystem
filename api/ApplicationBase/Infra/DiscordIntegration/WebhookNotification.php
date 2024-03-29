<?php

namespace ApplicationBase\Infra\DiscordIntegration;

use JsonException;
use function curl_exec;
use function curl_init;
use function curl_close;
use function curl_setopt_array;

class WebhookNotification
{
	/**
	 * @var Embed[]
	 */
	private array $embeds;
	
	private ?string $author;
	
	public function __construct(
		private readonly string $webhookAddress,
		private readonly string $username, Embed ...$embeds)
	{
		$this->embeds = $embeds;
	}
	
	/**
	 * @param null|string $author
	 *
	 * @return WebhookNotification
	 */
	public function setAuthor(?string $author): WebhookNotification
	{
		$this->author = $author;
		return $this;
	}
	
	/**
	 * @return void
	 * @throws JsonException
	 */
	public function send(): void
	{
		$curl = curl_init();
		
		curl_setopt_array(
			$curl, [
				     CURLOPT_URL => $this->webhookAddress, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '', CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_POSTFIELDS => json_encode($this->getBuildedPayload(), JSON_THROW_ON_ERROR), CURLOPT_HTTPHEADER => [
					     'Content-Type: application/json',
				     ],
			     ]
		);
		
		curl_exec($curl);
		curl_close($curl);
	}
	
	/**
	 * @return array
	 */
	private function getBuildedPayload(): array
	{
		$payload = ["username" => $this->username, "embeds" => []];

		foreach ($this->embeds as $index => $embed) {
			$payload["embeds"][$index] = [
				"title" => $embed->getTitle(), "type" => $embed->getType(), "description" => $embed->getDescription(), "color" => $embed->getColor(),
			];
			
			if ($index === 0 && isset($this->author)){
				$payload["embeds"][$index]["author"] = ["name"=>$this->author];
			}
		}

		return $payload;
	}
}