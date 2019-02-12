<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
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
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cif", type="string", length=20, nullable=true, unique=true)
     */
    private $cif;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cnae")
     * @ORM\JoinColumn(name="cnae_id", referencedColumnName="id", nullable=true)
     */
    private $cnae;

    /**
     * @var string
     *
     * @ORM\Column(name="social_denomination", type="string", length=255)
     */
    private $socialDenomination;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=255)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=255)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="contact_person_id", referencedColumnName="id", nullable=true)
     */
    private $contactPerson;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255)
     */
    private $web;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text")
     */
    private $observations;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="company")
     */
    private $products;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Knowledge", mappedBy="company")
     */
    private $knowledges;

    /**
     * @var ArrayCollection|null
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PollQuestion", mappedBy="company")
     */
    private $questions;


    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->knowledges = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    /**
     * Company toString method.
     *
     * @return string
     */
    public function __toString() {
        $name = $this->name;
        return (isset($name)) ? $this->name : "General Company";
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
     * @return Company
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
     * @return Company
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
     * Set email.
     *
     * @param string|null $email
     *
     * @return Company
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nif.
     *
     * @param string|null $nif
     *
     * @return Company
     */
    public function setNif($nif = null)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif.
     *
     * @return string|null
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set cnae.
     *
     * @param string|null $cnae
     *
     * @return Company
     */
    public function setCnae($cnae = null)
    {
        $this->cnae = $cnae;

        return $this;
    }

    /**
     * Get cnae.
     *
     * @return string|null
     */
    public function getCnae()
    {
        return $this->cnae;
    }

    /**
     * Set Products
     *
     * @param ArrayCollection|null $products
     *
     * @return Company
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get products
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
     * @return Company
     */
    public function addProduct ($product)
    {
        $this->products->add($product);

        return $this;
    }

    /**
     * Remove Product
     *
     * @param Product $product
     *
     * @return Company
     */
    public function removeProduct($product)
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * Set Knowledges
     *
     * @param ArrayCollection|null $knowledges
     *
     * @return Company
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
     * @return Company
     */
    public function addKnowledge ($knowledge)
    {
        $this->knowledges->add($knowledge);

        return $this;
    }

    /**
     * Remove Knowledge
     *
     * @param Knowledge $knowledge
     *
     * @return Company
     */
    public function removeKnowledge($knowledge)
    {
        $this->knowledges->removeElement($knowledge);

        return $this;
    }

    /**
     * Set PollQuestions
     *
     * @param ArrayCollection|null $questions
     *
     * @return Company
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
     * @return Company
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
     * @return Company
     */
    public function removeQuestion($question)
    {
        $this->questions->removeElement($question);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCif()
    {
        return $this->cif;
    }

    /**
     * @param null|string $cif
     */
    public function setCif($cif)
    {
        $this->cif = $cif;
    }

    /**
     * @return string
     */
    public function getSocialDenomination()
    {
        return $this->socialDenomination;
    }

    /**
     * @param string $socialDenomination
     */
    public function setSocialDenomination($socialDenomination)
    {
        $this->socialDenomination = $socialDenomination;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return User
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * @param User $contactPerson
     */
    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }

    /**
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param string $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param string $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }
}
