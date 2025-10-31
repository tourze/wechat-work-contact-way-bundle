<?php

namespace WechatWorkContactWayBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

/**
 * @implements PlainArrayInterface<string, mixed>
 * @see https://developer.work.weixin.qq.com/document/path/95724#%E8%8E%B7%E5%8F%96%E4%BC%81%E4%B8%9A%E5%B7%B2%E9%85%8D%E7%BD%AE%E7%9A%84%E3%80%8C%E8%81%94%E7%B3%BB%E6%88%91%E3%80%8D%E6%96%B9%E5%BC%8F
 * @see https://developer.work.weixin.qq.com/document/path/92228#%E8%8E%B7%E5%8F%96%E4%BC%81%E4%B8%9A%E5%B7%B2%E9%85%8D%E7%BD%AE%E7%9A%84%E3%80%8C%E8%81%94%E7%B3%BB%E6%88%91%E3%80%8D%E6%96%B9%E5%BC%8F
 */
#[ORM\Entity(repositoryClass: ContactWayRepository::class)]
#[ORM\Table(name: 'wechat_work_external_contact_contact_way', options: ['comment' => '客户联系[联系我]'])]
class ContactWay implements PlainArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;
    use IpTraceableAware;

    #[ORM\ManyToOne(targetEntity: CorpInterface::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpInterface $corp = null;

    #[ORM\ManyToOne(targetEntity: AgentInterface::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AgentInterface $agent = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    #[ORM\Column(length: 128, unique: true, nullable: false, options: ['comment' => 'ConfigID'])]
    private ?string $configId = null;

    #[Assert\NotNull(message: 'Type cannot be null')]
    #[Assert\Choice(choices: [1, 2], message: 'Type must be 1 or 2')]
    #[ORM\Column(options: ['comment' => '联系方式类型'])]
    private ?int $type = null;

    #[Assert\NotNull(message: 'Scene cannot be null')]
    #[Assert\Range(min: 1, max: 2, notInRangeMessage: 'Scene must be between 1 and 2')]
    #[ORM\Column(options: ['comment' => '场景'])]
    private ?int $scene = null;

    #[Assert\Range(min: 1, max: 2, notInRangeMessage: 'Style must be between 1 and 2')]
    #[ORM\Column(nullable: true, options: ['comment' => '小程序控件样式'])]
    private ?int $style = null;

    #[Assert\Length(max: 30)]
    #[ORM\Column(length: 30, nullable: true, options: ['comment' => '备注信息'])]
    private ?string $remark = null;

    #[Assert\Type(type: 'bool', message: 'Skip verify must be boolean')]
    #[ORM\Column(nullable: true, options: ['comment' => '添加时无需验证'])]
    private ?bool $skipVerify = true;

    #[Assert\Length(max: 30)]
    #[IndexColumn]
    #[ORM\Column(length: 30, nullable: true, options: ['comment' => '渠道参数'])]
    private ?string $state = null;

    /**
     * @var array<string>|null
     */
    #[Assert\Type(type: 'array', message: 'User must be array')]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '使用userID'])]
    private ?array $user = null;

    /**
     * @var array<string>|null
     */
    #[Assert\Type(type: 'array', message: 'Party must be array')]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '使用部门id'])]
    private ?array $party = null;

    #[Assert\Type(type: 'bool', message: 'Temp must be boolean')]
    #[ORM\Column(nullable: true, options: ['comment' => '是否临时会话'])]
    private ?bool $temp = false;

    #[Assert\PositiveOrZero]
    #[ORM\Column(nullable: true, options: ['comment' => '临时会话二维码有效期'])]
    private ?int $expiresIn = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(nullable: true, options: ['comment' => '临时会话有效期'])]
    private ?int $chatExpiresIn = null;

    #[Assert\Length(max: 128)]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '临时会话unionid'])]
    private ?string $unionId = null;

    #[Assert\Type(type: 'bool', message: 'Exclusive must be boolean')]
    #[ORM\Column(nullable: true, options: ['comment' => '是否开启同一外部企业客户只能添加同一个员工'])]
    private ?bool $exclusive = false;

    /**
     * @var array<mixed>|null
     */
    #[Assert\Type(type: 'array', message: 'Conclusions must be array')]
    #[ORM\Column(nullable: true, options: ['comment' => '结束语'])]
    private ?array $conclusions = null;

    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '二维码链接'])]
    private ?string $qrCode = null;

    public function getCorp(): ?CorpInterface
    {
        return $this->corp;
    }

    public function setCorp(?CorpInterface $corp): void
    {
        $this->corp = $corp;
    }

    public function getAgent(): ?AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(?AgentInterface $agent): void
    {
        $this->agent = $agent;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getScene(): ?int
    {
        return $this->scene;
    }

    public function setScene(int $scene): void
    {
        $this->scene = $scene;
    }

    public function getStyle(): ?int
    {
        return $this->style;
    }

    public function setStyle(?int $style): void
    {
        $this->style = $style;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function isSkipVerify(): ?bool
    {
        return $this->skipVerify;
    }

    public function setSkipVerify(?bool $skipVerify): void
    {
        $this->skipVerify = $skipVerify;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return array<string>|null
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * @param array<string>|null $user
     */
    public function setUser(?array $user): void
    {
        $this->user = $user;
    }

    /**
     * @return array<string>|null
     */
    public function getParty(): ?array
    {
        return $this->party;
    }

    /**
     * @param array<string>|null $party
     */
    public function setParty(?array $party): void
    {
        $this->party = $party;
    }

    public function isTemp(): ?bool
    {
        return $this->temp;
    }

    public function setTemp(?bool $temp): void
    {
        $this->temp = $temp;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    public function getChatExpiresIn(): ?int
    {
        return $this->chatExpiresIn;
    }

    public function setChatExpiresIn(?int $chatExpiresIn): void
    {
        $this->chatExpiresIn = $chatExpiresIn;
    }

    public function getUnionId(): ?string
    {
        return $this->unionId;
    }

    public function setUnionId(?string $unionId): void
    {
        $this->unionId = $unionId;
    }

    public function isExclusive(): ?bool
    {
        return $this->exclusive;
    }

    public function setExclusive(?bool $exclusive): void
    {
        $this->exclusive = $exclusive;
    }

    /**
     * @return array<mixed>|null
     */
    public function getConclusions(): ?array
    {
        return $this->conclusions;
    }

    /**
     * @param array<mixed>|null $conclusions
     */
    public function setConclusions(?array $conclusions): void
    {
        $this->conclusions = $conclusions;
    }

    public function getConfigId(): ?string
    {
        return $this->configId;
    }

    public function setConfigId(?string $configId): void
    {
        $this->configId = $configId;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): void
    {
        $this->qrCode = $qrCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        return [
            'id' => $this->getId(),
            'configId' => $this->getConfigId(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
