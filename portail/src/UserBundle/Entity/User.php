<?php
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User {
    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $mail;

    /**
     * @ORM\ManyToOne(targetEntity="YearGroup", inversedBy="users")
     * @ORM\JoinColumn(name="yearGroup", referencedColumnName="id")
     */
    private $yearGroup;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="SubGroup", inversedBy="users")
     * @ORM\JoinTable(name="users_subGroups")
     */
    private $subGroups;

    public function __construct() {
        $this->subGroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set yearGroup
     *
     * @param \UserBundle\Entity\YearGroup $yearGroup
     *
     * @return User
     */
    public function setYearGroup(\UserBundle\Entity\YearGroup $yearGroup = null)
    {
        $this->yearGroup = $yearGroup;

        return $this;
    }

    /**
     * Get yearGroup
     *
     * @return \UserBundle\Entity\YearGroup
     */
    public function getYearGroup()
    {
        return $this->yearGroup;
    }

    /**
     * Add subGroup
     *
     * @param \UserBundle\Entity\SubGroup $subGroup
     *
     * @return User
     */
    public function addSubGroup(\UserBundle\Entity\SubGroup $subGroup)
    {
        $this->subGroups[] = $subGroup;

        return $this;
    }

    /**
     * Remove subGroup
     *
     * @param \UserBundle\Entity\SubGroup $subGroup
     */
    public function removeSubGroup(\UserBundle\Entity\SubGroup $subGroup)
    {
        $this->subGroups->removeElement($subGroup);
    }

    /**
     * Get subGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubGroups()
    {
        return $this->subGroups;
    }
}
