<?php declare(strict_types=1);


namespace App\Entity;

trait DateCreatedTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dtCreated;

    /**
     * DateTimeTrait constructor.
     */
    public function __construct()
    {
        $this->setDtCreated(new \DateTime());
    }

    /**
     * @return \DateTime
     */
    public function getDtCreated(): \DateTime
    {
        return $this->dtCreated;
    }

    /**
     * @param \DateTime $dtCreated
     *
     * @return $this;
     */
    public function setDtCreated(\DateTime $dtCreated): self
    {
        $this->dtCreated = $dtCreated;

        return $this;
    }

}