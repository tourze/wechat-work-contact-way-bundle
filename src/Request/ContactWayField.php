<?php

namespace WechatWorkContactWayBundle\Request;

use WechatWorkContactWayBundle\Entity\ContactWay;

trait ContactWayField
{
    /**
     * @var int 联系方式类型,1-单人, 2-多人
     */
    private int $type;

    /**
     * @var int 场景，1-在小程序中联系，2-通过二维码联系
     */
    private int $scene;

    /**
     * @var int|null 在小程序中联系时使用的控件样式，详见附表
     */
    private ?int $style = null;

    /**
     * @var array<string>|null 使用该联系方式的用户userID列表，在type为1时为必填，且只能有一个
     */
    private ?array $user = null;

    /**
     * @var bool 外部客户添加时是否无需验证，默认为true
     */
    private bool $skipVerify = true;

    /**
     * @var string|null 企业自定义的state参数，用于区分不同的添加渠道，在调用"获取外部联系人详情"时会返回该参数值，不超过30个字符
     */
    private ?string $state = null;

    /**
     * @var array<string>|null 使用该联系方式的部门id列表，只在type为2时有效
     */
    private ?array $party = null;

    /**
     * @var bool 是否临时会话模式，true表示使用临时会话模式，默认为false
     */
    private bool $temp = false;

    /**
     * @var int|null 临时会话二维码有效期，以秒为单位。该参数仅在is_temp为true时有效，默认7天，最多为14天
     */
    private ?int $expiresIn = null;

    /**
     * @var int|null 临时会话有效期，以秒为单位。该参数仅在is_temp为true时有效，默认为添加好友后24小时，最多为14天
     */
    private ?int $chatExpiresIn = null;

    /**
     * @var string|null 可进行临时会话的客户unionid，该参数仅在is_temp为true时有效，如不指定则不进行限制
     */
    private ?string $unionId = null;

    /**
     * @var bool 是否开启同一外部企业客户只能添加同一个员工，默认为否，开启后，同一个企业的客户会优先添加到同一个跟进人
     */
    private bool $exclusive = false;

    /**
     * @var array<mixed>|null 结束语，会话结束时自动发送给客户，可参考"结束语定义"，仅在is_temp为true时有效
     */
    private ?array $conclusions = null;

    /**
     * @var string|null 联系方式的备注信息，不超过30个字符，将覆盖之前的备注
     */
    private ?string $remark = null;

    protected function populateFromObject(ContactWay $object): void
    {
        $this->setType($object->getType());
        $this->setScene($object->getScene());
        $this->setSkipVerify($object->isSkipVerify() ?? true);
        $this->setTemp($object->isTemp() ?? false);
        $this->setExclusive($object->isExclusive() ?? false);
        $this->setStyle($object->getStyle());
        $this->setState($object->getState());
        $this->setUser($object->getUser());
        $this->setParty($object->getParty());
        $this->setExpiresIn($object->getExpiresIn());
        $this->setChatExpiresIn($object->getChatExpiresIn());
        $this->setUnionId($object->getUnionId());
        $this->setConclusions($object->getConclusions());
        $this->setAgent($object->getAgent());
        $this->setRemark($object->getRemark());
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(?int $type): void
    {
        if (null !== $type) {
            $this->type = $type;
        }
    }

    public function getScene(): int
    {
        return $this->scene;
    }

    public function setScene(?int $scene): void
    {
        if (null !== $scene) {
            $this->scene = $scene;
        }
    }

    public function getStyle(): ?int
    {
        return $this->style;
    }

    public function setStyle(?int $style): void
    {
        $this->style = $style;
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

    public function isSkipVerify(): bool
    {
        return $this->skipVerify;
    }

    public function setSkipVerify(bool $skipVerify): void
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

    public function isTemp(): bool
    {
        return $this->temp;
    }

    public function setTemp(bool $temp): void
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

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    public function setExclusive(bool $exclusive): void
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

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFieldJson(): array
    {
        $json = [];

        $this->addBasicFields($json);
        $this->addOptionalFields($json);
        $this->addUserFields($json);
        $this->addTempFields($json);

        return $json;
    }

    /**
     * @param array<string, mixed> $json
     */
    private function addBasicFields(array &$json): void
    {
        if (isset($this->type)) {
            $json['type'] = $this->getType();
        }
        if (isset($this->scene)) {
            $json['scene'] = $this->getScene();
        }

        $json['skip_verify'] = $this->isSkipVerify();
        $json['is_temp'] = $this->isTemp();
        $json['is_exclusive'] = $this->isExclusive();
    }

    /**
     * @param array<string, mixed> $json
     */
    private function addOptionalFields(array &$json): void
    {
        if (null !== $this->style) {
            $json['style'] = $this->getStyle();
        }
        if (null !== $this->getState()) {
            $json['state'] = $this->getState();
        }
        if (null !== $this->getRemark()) {
            $json['remark'] = $this->getRemark();
        }
    }

    /**
     * @param array<string, mixed> $json
     */
    private function addUserFields(array &$json): void
    {
        if (!isset($this->type)) {
            return;
        }

        if (1 === $this->getType()) {
            $this->addSingleUserField($json);
        } elseif (2 === $this->getType()) {
            $this->addMultipleUserFields($json);
        }
    }

    /**
     * @param array<string, mixed> $json
     */
    private function addSingleUserField(array &$json): void
    {
        if ($this->isValidUserArray($this->user)) {
            $json['user'] = $this->getUser();
        }
    }

    /**
     * @param array<string, mixed> $json
     */
    private function addMultipleUserFields(array &$json): void
    {
        if ($this->isValidUserArray($this->user)) {
            $json['user'] = $this->getUser();
        }
        if ($this->isValidUserArray($this->getParty())) {
            $json['party'] = $this->getParty();
        }
    }

    /**
     * @param array<string>|null $userArray
     */
    private function isValidUserArray(?array $userArray): bool
    {
        return null !== $userArray && [] !== $userArray;
    }

    /**
     * @param array<string, mixed> $json
     */
    private function addTempFields(array &$json): void
    {
        if (!$this->isTemp()) {
            return;
        }

        if (null !== $this->getExpiresIn()) {
            $json['expires_in'] = $this->getExpiresIn();
        }
        if (null !== $this->getChatExpiresIn()) {
            $json['chat_expires_in'] = $this->getChatExpiresIn();
        }
        if (null !== $this->getUnionId()) {
            $json['unionid'] = $this->getUnionId();
        }
        if (null !== $this->getConclusions()) {
            $json['conclusions'] = $this->getConclusions();
        }
    }
}
