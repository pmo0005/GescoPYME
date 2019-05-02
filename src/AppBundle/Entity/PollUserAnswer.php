<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PollUserAnswer
 *
 * @ORM\Table(name="poll_user_answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollUserAnswerRepository")
 */
class PollUserAnswer
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
     * @var PollRegistry|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollRegistry", inversedBy="userAnswers", cascade={"persist"})
     * @ORM\JoinColumn(name="registry_id", referencedColumnName="id")
     */
    private $registry;

    /**
     * @var PollQuestionAnswer|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollQuestionAnswer", cascade={"persist"})
     * @ORM\JoinColumn(name="poll_questions_answer_id", referencedColumnName="id", nullable=true)
     */
    private $answer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="questionTextCopy", type="text", nullable=true)
     */
    private $questionTextCopy;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answerTextCopy", type="text", nullable=true)
     */
    private $answerTextCopy;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answerValueCopy", type="text", nullable=true)
     */
    private $answerValueCopy;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        $question = isset($this->questionTextCopy) ? $this->questionTextCopy : $this->answer->getQuestion()->getText();
        $answer = isset($this->answerTextCopy) ? $this->answerTextCopy : $this->answer->getText();
        $value = isset($this->answerValueCopy) ? $this->answerValueCopy : $this->answer->getValue();

        return $question . ": " . $answer . " (" . $value .  ")";
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
     * Set registry.
     *
     * @param PollRegistry|null $registry
     *
     * @return PollUserAnswer
     */
    public function setRegistry($registry = null)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * Get registry.
     *
     * @return PollRegistry|null
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Set answer.
     *
     * @param PollQuestionAnswer|null $answer
     *
     * @return PollUserAnswer
     */
    public function setAnswer($answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer.
     *
     * @return PollQuestionAnswer|null
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param null|string $questionTextCopy
     *
     * @return PollUserAnswer
     */
    public function setQuestionTextCopy($questionTextCopy)
    {
        $this->questionTextCopy = $questionTextCopy;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getQuestionTextCopy()
    {
        return $this->questionTextCopy;
    }

    /**
     * Set text.
     *
     * @param string|null $answerTextCopy
     *
     * @return PollUserAnswer
     */
    public function setAnswerTextCopy($answerTextCopy = null)
    {
        $this->answerTextCopy = $answerTextCopy;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getAnswerTextCopy()
    {
        return $this->answerTextCopy;
    }

    /**
     * Set value.
     *
     * @param string|null $answerValueCopy
     *
     * @return PollUserAnswer
     */
    public function setAnswerValueCopy($answerValueCopy = null)
    {
        $this->answerValueCopy = $answerValueCopy;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string|null
     */
    public function getAnswerValueCopy()
    {
        return $this->answerValueCopy;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return PollUserAnswer
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
}
