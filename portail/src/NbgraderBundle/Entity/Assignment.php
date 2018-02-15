<?php

namespace NbgraderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Assignment
 *
 * @ORM\Table(name="assignment")
 * @ORM\Entity(repositoryClass="NbgraderBundle\Repository\AssignmentRepository")
 */
class Assignment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lesson;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="\NotebooksBundle\Entity\Notebook", inversedBy="assignments")
     * @ORM\JoinColumn(name="notebook", referencedColumnName="id")
     */
    private $notebook;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\YearGroup")
     * @ORM\JoinColumn(name="yearGroup_id", referencedColumnName="id")
     */
    private $yearGroup;

    /**
     * @ORM\ManyToMany(targetEntity="\UserBundle\Entity\SubGroup", inversedBy="assignments")
     * @ORM\JoinTable(name="assignments_subGroups")
     */
    private $subGroups;

    public function __construct()
    {
        $this->yearGroups = new ArrayCollection();
        $this->subGroups = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lesson
     *
     * @param string $lesson
     *
     * @return Assignment
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return string
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Assignment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set notebook
     *
     * @param \NotebooksBundle\Entity\Notebook $notebook
     *
     * @return Assignment
     */
    public function setNotebook(\NotebooksBundle\Entity\Notebook $notebook = null)
    {
        $this->notebook = $notebook;

        return $this;
    }

    /**
     * Get notebook
     *
     * @return \NotebooksBundle\Entity\Notebook
     */
    public function getNotebook()
    {
        return $this->notebook;
    }

    /**
     * Set yearGroup
     *
     * @param \UserBundle\Entity\YearGroup $yearGroup
     *
     * @return Assignment
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
     * @return Assignment
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
