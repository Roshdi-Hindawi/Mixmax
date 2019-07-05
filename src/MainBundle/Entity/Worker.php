<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Worker
 *
 * @ORM\Table(name="worker")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\WorkerRepository")
 */
class Worker
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
    * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Facility", cascade={"persist"})
    * @ORM\JoinColumn(name="facility_id", referencedColumnName="id", nullable=false)
    */
    private $facility;

    /**
     * @ORM\OneToOne(targetEntity="MainBundle\Entity\Media", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=20, nullable=true)
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="post", type="string", length=100, nullable=true)
     */
    private $post;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="salary", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $salary;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Worker
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Worker
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Worker
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set post
     *
     * @param string $post
     *
     * @return Worker
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set salary
     *
     * @param string $salary
     *
     * @return Worker
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return string
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set facility
     *
     * @param \MainBundle\Entity\Facility $facility
     *
     * @return Worker
     */
    public function setFacility(\MainBundle\Entity\Facility $facility)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return \MainBundle\Entity\Facility
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Set image
     *
     * @param \MainBundle\Entity\Media $image
     *
     * @return Worker
     */
    public function setImage(\MainBundle\Entity\Media $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \MainBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Worker
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Worker
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Worker
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }
}
