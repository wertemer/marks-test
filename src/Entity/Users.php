<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="Users", indexes={@ORM\Index(name="f_rol", columns={"f_rol"})})
 * @ORM\Entity
 */
class Users
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
     * @ORM\Column(name="login", type="string", length=10, nullable=false)
     */
    private $login;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="paswd", type="string", length=12, nullable=false)
     */
    private $paswd;

    /**
     * @var \Roles
     *
     * @ORM\ManyToOne(targetEntity="Roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="f_rol", referencedColumnName="id")
     * })
     */
    private $fRol;

    public function getId(): ?int
    {
        return $this->id;
    }

	public function setId(int $id): self
	{
		$this->id=$id;
		
		return $this;
	}

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPaswd(): ?string
    {
        return $this->paswd;
    }

    public function setPaswd(string $paswd): self
    {
        $this->paswd = $paswd;

        return $this;
    }

    public function getFRol(): ?Roles
    {
        return $this->fRol;
    }

    public function setFRol(?Roles $fRol): self
    {
        $this->fRol = $fRol;

        return $this;
    }


}
