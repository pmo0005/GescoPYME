<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PollQuestion
 *
 * @ORM\Table(name="poll_question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollQuestionRepository")
 */
class PollQuestion
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
     * @var PollQuestionType|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollQuestionType", inversedBy="questions")
     * @ORM\JoinColumn(name="poll_questions_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var PollQuestionResponseType|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollQuestionResponseType", inversedBy="questions")
     * @ORM\JoinColumn(name="poll_question_response_type_id", referencedColumnName="id")
     */
    private $responseType;

    /**
     * @var Product|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="questions")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var Company|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="questions")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @var string|null
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollQuestionAnswer", mappedBy="question", cascade={"persist"})
     */
    private $answers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    public function __construct()
    {
        $this->enabled = false;
        $this->answers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->text;
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
     * Set type.
     *
     * @param PollQuestionType|null $type
     *
     * @return PollQuestion
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return PollQuestionType|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set responseType.
     *
     * @param PollQuestionResponseType|null $responseType
     *
     * @return PollQuestion
     */
    public function setResponseType($responseType = null)
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * Get responseType.
     *
     * @return PollQuestionResponseType|null
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * Set product.
     *
     * @param Product|null $product
     *
     * @return PollQuestion
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
     * Set company.
     *
     * @param Company|null $company
     *
     * @return PollQuestion
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
     * Set text.
     *
     * @param string|null $text
     *
     * @return PollQuestion
     */
    public function setText($text = null)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set Answers
     *
     * @param ArrayCollection|null $answers
     *
     * @return PollQuestion
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * Get Answers
     *
     * @return ArrayCollection|null
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Add Answer
     *
     * @param PollQuestionAnswer $answer
     *
     * @return PollQuestion
     */
    public function addAnswer ($answer)
    {
        $answer->setQuestion($this);
        $this->answers->add($answer);

        return $this;
    }

    /**
     * Remove Answer
     *
     * @param PollQuestionAnswer $answer
     *
     * @return PollQuestion
     */
    public function removeAnswer($answer)
    {
        $this->answers->removeElement($answer);

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param $enabled
     *
     * @return PollQuestion
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }
}
