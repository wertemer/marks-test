<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models
 *
 * @ORM\Table(name="Models", indexes={@ORM\Index(name="f_mark", columns={"f_mark"})})
 * @ORM\Entity
 */
class Models
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_left_drive", type="boolean", nullable=true, options={"default"="1"})
     */
    private $isLeftDrive = true;

    /**
     * @var \Marks
     *
     * @ORM\ManyToOne(targetEntity="Marks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="f_mark", referencedColumnName="id")
     * })
     */
    private $fMark;

    public function getId(): ?int
    {
        return $this->id;
    }
	public function setId(int $id): self
    {
		$this->id=$id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsLeftDrive(): ?bool
    {
        return $this->isLeftDrive;
    }

    public function setIsLeftDrive(?bool $isLeftDrive): self
    {
        $this->isLeftDrive = $isLeftDrive;

        return $this;
    }

    public function getFMark(): ?Marks
    {
        return $this->fMark;
    }

    public function setFMark(?Marks $fMark): self
    {
        $this->fMark = $fMark;

        return $this;
    }


}
