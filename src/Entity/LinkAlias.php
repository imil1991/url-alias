<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Table(uniqueConstraints={
 *        @UniqueConstraint(name="alias_unique",
 *            columns={"alias"})
 *    })
 * @ORM\Entity()
 */
class LinkAlias
{
    const DEFAULT_EXPIRE_PERIOD = '+ 1 day';

    use DateCreatedTrait {
        DateCreatedTrait::__construct as private __dtConstruct;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $originalLink;

    /**
     * @var string
     * @ORM\Column(type="string", length=8)
     */
    private $alias;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $useExpire = true;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expired;

    /**
     * @var Collection|LinkAliasStatistic[]
     * @ORM\OneToMany(targetEntity="App\Entity\LinkAliasStatistic", mappedBy="linkAlias")
     */
    private $statistic;

    public function __construct()
    {
        $this->__dtConstruct();
        $this->setAlias(uniqid());
        $this->setExpired(new \DateTime(self::DEFAULT_EXPIRE_PERIOD));
        $this->setStatistic(new ArrayCollection());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this;
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalLink():? string
    {
        return $this->originalLink;
    }

    /**
     * @param string $originalLink
     *
     * @return $this;
     */
    public function setOriginalLink(string $originalLink): self
    {
        $this->originalLink = $originalLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias():? string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return $this;
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUseExpire(): bool
    {
        return $this->useExpire;
    }

    /**
     * @param bool $useExpire
     *
     * @return $this;
     */
    public function setUseExpire(bool $useExpire): self
    {
        $this->useExpire = $useExpire;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpired():? \DateTime
    {
        return $this->expired;
    }

    /**
     * @param \DateTime $expired
     *
     * @return $this;
     */
    public function setExpired(\DateTime $expired): self
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * @return Collection|LinkAliasStatistic[]
     */
    public function getStatistic(): Collection
    {
        return $this->statistic;
    }

    /**
     * @param Collection $statistic
     *
     * @return $this;
     */
    public function setStatistic(Collection $statistic): self
    {
        $this->statistic = $statistic;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->isUseExpire() && $this->getExpired() < new \DateTime();
    }
}