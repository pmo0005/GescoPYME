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
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * @ORM\Column(name="createdAt", type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollUserAnswer", mappedBy="answer")
     */
    private $userAnswers;


    /**
     * PollRegistry constructor.
     */
    public function __construct()
    {
        $this->userAnswers = new ArrayCollection();
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
     * @param User|null $user
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
     * @return User|null
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
     * @param PollQuestionAnswer $userAnswer
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
}
