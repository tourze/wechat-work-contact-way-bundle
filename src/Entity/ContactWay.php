<?php

namespace WechatWorkContactWayBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

/**
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpInterface $corp = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AgentInterface $agent = null;

    #[ORM\Column(length: 128, unique: true, nullable: false, options: ['comment' => 'ConfigID'])]
    private ?string $configId = null;

    #[ORM\Column(options: ['comment' => '联系方式类型'])]
    private ?int $type = null;

    #[ORM\Column(options: ['comment' => '场景'])]
    private ?int $scene = null;

    #[ORM\Column(nullable: true, options: ['comment' => '小程序控件样式'])]
    private ?int $style = null;

    #[ORM\Column(length: 30, nullable: true, options: ['comment' => '备注信息'])]
    private ?string $remark = null;

    #[ORM\Column(nullable: true, options: ['comment' => '添加时无需验证'])]
    private ?bool $skipVerify = true;

    #[IndexColumn]
    #[ORM\Column(length: 30, nullable: true, options: ['comment' => '渠道参数'])]
    private ?string $state = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '使用userID'])]
    private ?array $user = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '使用部门id'])]
    private ?array $party = null;

    #[ORM\Column(nullable: true, options: ['comment' => '是否临时会话'])]
    private ?bool $temp = false;

    #[ORM\Column(nullable: true, options: ['comment' => '临时会话二维码有效期'])]
    private ?int $expiresIn = null;

    #[ORM\Column(nullable: true, options: ['comment' => '临时会话有效期'])]
    private ?int $chatExpiresIn = null;

    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '临时会话unionid'])]
    private ?string $unionId = null;

    #[ORM\Column(nullable: true, options: ['comment' => '是否开启同一外部企业客户只能添加同一个员工'])]
    private ?bool $exclusive = false;

    #[ORM\Column(nullable: true, options: ['comment' => '结束语'])]
    private ?array $conclusions = null;

    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '二维码链接'])]
    private ?string $qrCode = null;

    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;


    public function getCorp(): ?CorpInterface
    {
        return $this->corp;
    }

    public function setCorp(?CorpInterface $corp): static
    {
        $this->corp = $corp;

        return $this;
    }

    public function getAgent(): ?AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(?AgentInterface $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getScene(): ?int
    {
        return $this->scene;
    }

    public function setScene(int $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    public function getStyle(): ?int
    {
        return $this->style;
    }

    public function setStyle(?int $style): static
    {
        $this->style = $style;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): static
    {
        $this->remark = $remark;

        return $this;
    }

    public function isSkipVerify(): ?bool
    {
        return $this->skipVerify;
    }

    public function setSkipVerify(?bool $skipVerify): static
    {
        $this->skipVerify = $skipVerify;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): ?array
    {
        return $this->user;
    }

    public function setUser(?array $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getParty(): ?array
    {
        return $this->party;
    }

    public function setParty(?array $party): static
    {
        $this->party = $party;

        return $this;
    }

    public function isTemp(): ?bool
    {
        return $this->temp;
    }

    public function setTemp(?bool $temp): static
    {
        $this->temp = $temp;

        return $this;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?int $expiresIn): static
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getChatExpiresIn(): ?int
    {
        return $this->chatExpiresIn;
    }

    public function setChatExpiresIn(?int $chatExpiresIn): static
    {
        $this->chatExpiresIn = $chatExpiresIn;

        return $this;
    }

    public function getUnionId(): ?string
    {
        return $this->unionId;
    }

    public function setUnionId(?string $unionId): static
    {
        $this->unionId = $unionId;

        return $this;
    }

    public function isExclusive(): ?bool
    {
        return $this->exclusive;
    }

    public function setExclusive(?bool $exclusive): static
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    public function getConclusions(): ?array
    {
        return $this->conclusions;
    }

    public function setConclusions(?array $conclusions): static
    {
        $this->conclusions = $conclusions;

        return $this;
    }

    public function getConfigId(): ?string
    {
        return $this->configId;
    }

    public function setConfigId(?string $configId): static
    {
        $this->configId = $configId;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): static
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }

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
