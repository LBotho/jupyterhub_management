<?php
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="subgroup")
 */
class SubGroup {
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
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="subGroups")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="YearGroup", inversedBy="subGroups")
     * @ORM\JoinColumn(name="yearGroup", referencedColumnName="id")
     */
    private $yearGroup;

    /**
     * @ORM\ManyToMany(targetEntity="\NbgraderBundle\Entity\Assignment", mappedBy="subGroups")
     */
     private $assignments;

    public function __construct() {
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
     * @return SubGroup
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
     * @return SubGroup
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
     * @return SubGroup
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
     * Set yearGroup
     *
     * @param \UserBundle\Entity\YearGroup $yearGroup
     *
     * @return SubGroup
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
     * Add assignment
     *
     * @param \NbgraderBundle\Entity\Assignment $assignment
     *
     * @return SubGroup
     */
    public function addAssignment(\NbgraderBundle\Entity\Assignment $assignment)
    {
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \NbgraderBundle\Entity\Assignment $assignment
     */
    public function removeAssignment(\NbgraderBundle\Entity\Assignment $assignment)
    {
        $this->assignments->removeElement($assignment);
    }

    /**
     * Get assignments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignments()
    {
        return $this->assignments;
    }
}
