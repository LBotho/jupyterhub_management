<?php
namespace NotebooksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Notebook
 *
 * @ORM\Table(name="notebook")
 * @ORM\Entity(repositoryClass="NotebooksBundle\Repository\NotebookRepository")
 */
class Notebook
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
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="\NbgraderBundle\Entity\Assignment", mappedBy="notebook")
     */
    private $assignments;

    public function __construct() {
        $this->assignments = new ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return Notebook
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
     * Add assignment
     *
     * @param \NbgraderBundle\Entity\Assignment $assignment
     *
     * @return Notebook
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
