<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PollQuestionAnswer
 *
 * @ORM\Table(name="poll_question_answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollQuestionAnswerRepository")
 */
class PollQuestionAnswer
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
     * @var PollQuestion|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollQuestion", inversedBy="answers", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @var string|null
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var float|null
     *
     * @ORM\Column(name="value", type="float", nullable=true)
     */
    private $value;

    /**
     * String representation.
     *
     * @return null|string
     */
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
     * Set question.
     *
     * @param PollQuestion|null $question
     *
     * @return PollQuestionAnswer
     */
    public function setQuestion($question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question.
     *
     * @return PollQuestion|null
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set text.
     *
     * @param string|null $text
     *
     * @return PollQuestionAnswer
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
     * Set value.
     *
     * @param float|null $value
     *
     * @return PollQuestionAnswer
     */
    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return float|null
     */
    public function getValue()
    {
        return $this->value;
    }
}
