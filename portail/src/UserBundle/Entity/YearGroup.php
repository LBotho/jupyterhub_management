<?php
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="yearGroup")
 */
class YearGroup {
    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="yearGroup")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="SubGroup", mappedBy="yearGroup")
     */
    private $subGroups;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->assignments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return YearGroup
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
     * Set label
     *
     * @param string $label
     *
     * @return YearGroup
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return YearGroup
     */
    public function addUser(\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \UserBundle\Entity\User $user
     */
    public function removeUser(\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add subGroup
     *
     * @param \UserBundle\Entity\SubGroup $subGroup
     *
     * @return YearGroup
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
