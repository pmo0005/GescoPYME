<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PollRegistry
 *
 * @ORM\Table(name="poll_registry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollRegistryRepository")
 */
class PollRegistry
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
     * @ORM\Column(name="user", type="string", length=255, nullable=true)
     */
    private $user;

    /**
     * @var Product|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="polls")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollUserAnswer", mappedBy="registry", cascade={"persist"})
     */
    private $userAnswers;

    /**
     * @var string|null
     *
     * @ORM\Column(name="observations", type="text", nullable=true)
     */
    private $observations;


    /**
     * PollRegistry constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->userAnswers = new ArrayCollection();
    }

    public function __toString()
    {
        return "" . $this->id;
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
     * Set user.
     *
     * @param string|null $user
     *
     * @return PollRegistry
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product.
     *
     * @param Product|null $product
     *
     * @return PollRegistry
     */
    public function setProduct($product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return PollRegistry
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set UserAnswers
     *
     * @param ArrayCollection|null $userAnswers
     *
     * @return PollRegistry
     */
    public function setUserAnswers($userAnswers)
    {
        $this->userAnswers = $userAnswers;

        return $this;
    }

    /**
     * Get UserAnswer
     *
     * @return ArrayCollection|null
     */
    public function getUserAnswers()
    {
        return $this->userAnswers;
    }

    /**
     * Add UserAnswer
     *
     * @param PollUserAnswer $userAnswer
     *
     * @return PollRegistry
     */
    public function addUserAnswer ($userAnswer)
    {
        $this->userAnswers->add($userAnswer);

        return $this;
    }

    /**
     * Remove Answer
     *
     * @param PollUserAnswer $userAnswer
     *
     * @return PollRegistry
     */
    public function removeUserAnswer($userAnswer)
    {
        $this->userAnswers->removeElement($userAnswer);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param null|string $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }
}
