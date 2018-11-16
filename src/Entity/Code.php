<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodeRepository")
 */
class Code
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=10, unique=true)
	 */
	private $code;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;

	/**
	 * Code constructor.
	 */
	public function __construct()
	{
		$this->date = new \DateTime();
	}

	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 * @return Code
	 */
	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return \DateTimeInterface|null
	 */
	public function getDate(): ?\DateTimeInterface
	{
		return $this->date;
	}

	/**
	 * @param \DateTimeInterface $date
	 * @return Code
	 */
	public function setDate(\DateTimeInterface $date): self
	{
		$this->date = $date;

		return $this;
	}
}
