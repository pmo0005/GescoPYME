<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ref_code", type="string", length=255)
     */
    private $refCode;

    /**
     * @var ProductType|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var Company|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="products")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Knowledge", inversedBy="products")
     * @ORM\JoinTable(name="product_knowledge")
     */
    private $knowledges;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollQuestion", mappedBy="product")
     */
    private $questions;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollRegistry", mappedBy="product")
     */
    private $polls;


    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->knowledges = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->polls = new ArrayCollection();
    }

    /**
     * toString method.
     *
     * @return string
     */
    public function __toString() {
        return $this->name || "";
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
     * @return Product
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
     * @return Product
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
     * Set refCode.
     *
     * @param string|null $refCode
     *
     * @return Product
     */
    public function setRefCode($refCode = null)
    {
        $this->refCode = $refCode;

        return $this;
    }

    /**
     * Get refCode.
     *
     * @return string|null
     */
    public function getRefCode()
    {
        return $this->refCode;
    }

    /**
     * Set type.
     *
     * @param int|null $type
     *
     * @return Product
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int|null
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
     * @return Product
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
     * @return Product
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
        return $this->knowledges->toArray();
    }

    /**
     * Add Knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Product
     */
    public function addKnowledge ($knowledge)
    {
        if ($this->knowledges->contains($knowledge)) {
            return $this;
        }

        $this->knowledges->add($knowledge);
        $knowledge->addProduct($this);

        return $this;
    }

    /**
     * Remove Knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Product
     */
    public function removeKnowledge($knowledge)
    {
        if (!$this->knowledges->contains($knowledge)) {
            return $this;
        }

        $this->knowledges->removeElement($knowledge);
        $knowledge->removeProduct($this);

        return $this;
    }

    /**
     * Set PollQuestions
     *
     * @param ArrayCollection|null $questions
     *
     * @return Product
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get PollQuestions
     *
     * @return ArrayCollection|null
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add PollQuestion
     *
     * @param PollQuestion $question
     *
     * @return Product
     */
    public function addQuestion ($question)
    {
        $this->questions->add($question);

        return $this;
    }

    /**
     * Remove PollQuestion
     *
     * @param PollQuestion $question
     *
     * @return Product
     */
    public function removeQuestion($question)
    {
        $this->questions->removeElement($question);

        return $this;
    }

    /**
     * Set Polls
     *
     * @param ArrayCollection|null $polls
     *
     * @return Product
     */
    public function setPolls($polls)
    {
        $this->polls = $polls;

        return $this;
    }

    /**
     * Get Polls
     *
     * @return ArrayCollection|null
     */
    public function getPolls()
    {
        return $this->polls;
    }

    /**
     * Add Poll
     *
     * @param PollRegistry $poll
     *
     * @return Product
     */
    public function addPoll ($poll)
    {
        $this->polls->add($poll);

        return $this;
    }

    /**
     * Remove Poll
     *
     * @param PollRegistry $poll
     *
     * @return Product
     */
    public function removePoll($poll)
    {
        $this->polls->removeElement($poll);

        return $this;
    }
}
