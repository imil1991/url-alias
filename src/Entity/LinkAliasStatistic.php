<?php declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class LinkAliasStatistic
{
    use DateCreatedTrait {
        DateCreatedTrait::__construct as private __dtConstruct;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;
    /**
     * @var LinkAlias
     * @ORM\ManyToOne(targetEntity="App\Entity\LinkAlias", inversedBy="statistic")
     */
    private $linkAlias;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $userIp;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $userAgent;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $expired;

    /**
     * LinkAliasStatistic constructor.
     */
    public function __construct()
    {
        $this->__dtConstruct();
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
     * @return LinkAlias
     */
    public function getLinkAlias(): LinkAlias
    {
        return $this->linkAlias;
    }

    /**
     * @param LinkAlias $linkAlias
     *
     * @return $this;
     */
    public function setLinkAlias(LinkAlias $linkAlias): self
    {
        $this->linkAlias = $linkAlias;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     *
     * @return $this;
     */
    public function setUserIp(string $userIp): self
    {
        $this->userIp = $userIp;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     *
     * @return $this;
     */
    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired;
    }

    /**
     * @param bool $expired
     *
     * @return $this;
     */
    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;

        return $this;
    }

    public function fromRequest(Request $request): self
    {
        $this->setUserAgent($request->headers->get('User-Agent'))
             ->setUserIp($request->getClientIp());

        return $this;
    }

    public function fromLinkAlias(LinkAlias $linkAlias): self
    {
        $this->setLinkAlias($linkAlias)
             ->setExpired($linkAlias->isExpired());

        return $this;
    }
}