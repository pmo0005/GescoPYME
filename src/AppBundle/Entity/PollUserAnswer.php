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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollRegistry", inversedBy="userAnswers")
     * @ORM\JoinColumn(name="registry_id", referencedColumnName="id")
     */
    private $registry;

    /**
     * @var PollQuestionAnswer|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PollQuestionAnswer")
     * @ORM\JoinColumn(name="poll_questions_answer_id", referencedColumnName="id", nullable=true)
     */
    private $answer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createdAt", type="datetime_immutable", nullable=true)
     */
    private $createdAt;


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
     * Set value.
     *
     * @param string|null $value
     *
     * @return PollUserAnswer
     */
    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
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
