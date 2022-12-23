<?php

namespace ApplicationBase\Infra\DiscordIntegration;

class Embed
{
    private readonly string $type;

	public function __construct(
		private readonly string $title,
	    private readonly string $description,
		private readonly string $color
	){
        $this->type = "rich";
    }
	
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}
	
	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}
	
	/**
	 * @return string
	 */
	public function getColor(): string
	{
		return $this->color;
	}
}