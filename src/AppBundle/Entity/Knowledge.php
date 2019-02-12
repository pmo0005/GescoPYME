<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Knowledge
 *
 * @ORM\Table(name="knowledge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\KnowledgeRepository")
 */
class Knowledge
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\KnowledgeType")
     */
    private $type;

    /**
     * @var Company|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="knowledges")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @var int
     *
     * @ORM\Column(name="total_users", type="integer")
     */
    private $totalUsers;

    /**
     * @var KnowledgeGeneralLevel|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\KnowledgeGeneralLevel")
     */
    private $currentLevel;

    /**
     * @var KnowledgeGeneralLevel|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\KnowledgeGeneralLevel")
     */
    private $desiredLevel;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="knowledges")
     */
    private $products;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Action", inversedBy="knowledges")
     * @ORM\JoinTable(name="knowledge_action")
     */
    private $actions;

    /**
     * Knowledge constructor.
     */
    public function __construct()
    {
        // $this->products = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    /**
     * toString method.
     *
     * @return string
     */
    public function __toString() {
        $name = $this->name;
        return isset($name) ? $name : "Casdfasdfasd";
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
     * @return Knowledge
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
     * @return Knowledge
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
     * @param KnowledgeType|null $type
     *
     * @return Knowledge
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return KnowledgeType|null
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
     * @return Knowledge
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
     * Set totalUsers.
     *
     * @param int $totalUsers
     *
     * @return Knowledge
     */
    public function setTotalUsers($totalUsers)
    {
        $this->totalUsers = $totalUsers;

        return $this;
    }

    /**
     * Get totalUsers.
     *
     * @return int
     */
    public function getTotalUsers()
    {
        return $this->totalUsers;
    }

    /**
     * Set currentLevel.
     *
     * @param KnowledgeGeneralLevel|null $currentLevel
     *
     * @return Knowledge
     */
    public function setCurrentLevel($currentLevel = null)
    {
        $this->currentLevel = $currentLevel;

        return $this;
    }

    /**
     * Get currentLevel.
     *
     * @return KnowledgeGeneralLevel|null
     */
    public function getCurrentLevel()
    {
        return $this->currentLevel;
    }

    /**
     * Set desiredLevel.
     *
     * @param KnowledgeGeneralLevel|null $desiredLevel
     *
     * @return Knowledge
     */
    public function setDesiredLevel($desiredLevel = null)
    {
        $this->desiredLevel = $desiredLevel;

        return $this;
    }

    /**
     * Get desiredLevel.
     *
     * @return KnowledgeGeneralLevel|null
     */
    public function getDesiredLevel()
    {
        return $this->desiredLevel;
    }

    /**
     * Set Products
     *
     * @param ArrayCollection|null $products
     *
     * @return Knowledge
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get Products
     *
     * @return ArrayCollection|null
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add Product
     *
     * @param Product $product
     *
     * @return Knowledge
     */
    public function addProduct($product)
    {
        if ($this->products->contains($product)) {
            return $this;
        }

        $this->products->add($product);
        $product->addKnowledge($this);

        return $this;
    }

    /**
     * Remove Product
     *
     * @param Product $product
     *
     * @return Knowledge
     */
    public function removeProduct($product)
    {
        if (!$this->products->contains($product)) {
            return $this;
        }

        $this->products->removeElement($product);
        $product->removeKnowledge($this);

        return $this;
    }

    /**
     * Set Actions
     *
     * @param ArrayCollection|null $actions
     *
     * @return Knowledge
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Get Actions
     *
     * @return ArrayCollection|null
     */
    public function getActions()
    {
        return $this->actions->toArray();
    }

    /**
     * Add Action
     *
     * @param Action $action
     *
     * @return Knowledge
     */
    public function addAction($action)
    {
        if ($this->actions && $this->actions->contains($action)) {
            return $this;
        }

        $this->actions->add($action);
        $action->addKnowledge($this);

        return $this;
    }

    /**
     * Remove Action
     *
     * @param Action $action
     *
     * @return Knowledge
     */
    public function removeAction($action)
    {
        if (!$this->actions->contains($action)) {
            return $this;
        }

        $this->actions->removeElement($action);
        $action->removeKnowledge($this);

        return $this;
    }
}
