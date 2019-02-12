<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PollQuestionResponseType
 *
 * @ORM\Table(name="poll_question_response_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollQuestionResponseTypeRepository")
 */
class PollQuestionResponseType
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
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollQuestion", mappedBy="responseType")
     */
    private $questions;


    /**
     * PollQuestionResponseType constructor.
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * @return PollQuestionResponseType
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
     * Set PollQuestions
     *
     * @param ArrayCollection|null $questions
     *
     * @return PollQuestionResponseType
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
     * @return PollQuestionResponseType
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
     * @return PollQuestionResponseType
     */
    public function removeQuestion($question)
    {
        $this->questions->removeElement($question);

        return $this;
    }
}
