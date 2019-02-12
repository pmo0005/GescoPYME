<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Action
 *
 * @ORM\Table(name="action")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActionRepository")
 */
class Action
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var KnowledgeType|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ActionType")
     */
    private $type;

    /**
     * @var Company|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Knowledge", mappedBy="actions")
     */
    private $knowledges;


    /**
     * Action constructor.
     */
    public function __construct()
    {
        // $this->knowledges = new ArrayCollection();
    }

    /**
     * toString method.
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Action
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Action
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type.
     *
     * @param ActionType|null $type
     *
     * @return Action
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return ActionType|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set company.
     *
     * @param Company|null $company
     *
     * @return Action
     */
    public function setCompany($company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set Knowledges
     *
     * @param ArrayCollection|null $knowledges
     *
     * @return Action
     */
    public function setKnowledges($knowledges)
    {
        $this->knowledges = $knowledges;

        return $this;
    }

    /**
     * Get Knowledges
     *
     * @return ArrayCollection|null
     */
    public function getKnowledges()
    {
        return $this->knowledges;
    }

    /**
     * Add Knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Action
     */
    public function addKnowledge ($knowledge)
    {
        if ($this->knowledges->contains($knowledge)) {
            return $this;
        }

        $this->knowledges->add($knowledge);
        $knowledge->addAction($this);

        return $this;
    }

    /**
     * Remove Knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Action
     */
    public function removeKnowledge($knowledge)
    {
        if (!$this->knowledges->contains($knowledge)) {
            return $this;
        }

        $this->knowledges->removeElement($knowledge);
        $knowledge->removeAction($this);

        return $this;
    }
}
