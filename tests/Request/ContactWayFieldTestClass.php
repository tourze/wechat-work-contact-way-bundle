<?php

namespace WechatWorkContactWayBundle\Tests\Request;

use WechatWorkBundle\Request\AgentAware;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Request\ContactWayField;

/**
 * 测试用的具体类，使用ContactWayField trait
 *
 * @internal
 */
class ContactWayFieldTestClass
{
    use ContactWayField;
    use AgentAware;

    public static function createFromObject(ContactWay $object): self
    {
        $request = new self();
        $request->populateFromObject($object);

        return $request;
    }

    /**
     * 公开getFieldJson方法用于测试
     *
     * @return array<string, mixed>
     */
    public function getFieldJson(): array
    {
        $json = $this->buildBasicJson();
        $json = $this->addOptionalFieldsToJson($json);
        $json = $this->addUserFieldsToJson($json);

        return $this->addTempFieldsToJson($json);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildBasicJson(): array
    {
        return [
            'type' => $this->getType(),
            'scene' => $this->getScene(),
            'skip_verify' => $this->isSkipVerify(),
            'is_temp' => $this->isTemp(),
            'is_exclusive' => $this->isExclusive(),
        ];
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addOptionalFieldsToJson(array $json): array
    {
        if (isset($this->style) && null !== $this->getStyle()) {
            $json['style'] = $this->getStyle();
        }
        if (isset($this->state) && null !== $this->getState()) {
            $json['state'] = $this->getState();
        }
        if (isset($this->remark) && null !== $this->getRemark()) {
            $json['remark'] = $this->getRemark();
        }

        return $json;
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addUserFieldsToJson(array $json): array
    {
        if (1 === $this->getType()) {
            $json = $this->addSingleUserField($json);
        }

        if (2 === $this->getType()) {
            $json = $this->addMultiUserFields($json);
        }

        return $json;
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addSingleUserField(array $json): array
    {
        $user = $this->getUser();
        if (isset($this->user) && null !== $user && [] !== $user) {
            $json['user'] = $user;
        }

        return $json;
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addMultiUserFields(array $json): array
    {
        $user = $this->getUser();
        if (isset($this->user) && null !== $user && [] !== $user) {
            $json['user'] = $user;
        }
        $party = $this->getParty();
        if (isset($this->party) && null !== $party && [] !== $party) {
            $json['party'] = $party;
        }

        return $json;
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addTempFieldsToJson(array $json): array
    {
        if (!$this->isTemp()) {
            return $json;
        }

        $json = $this->addTempExpirationFields($json);

        return $this->addTempConfigFields($json);
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addTempExpirationFields(array $json): array
    {
        if (isset($this->expiresIn) && null !== $this->getExpiresIn()) {
            $json['expires_in'] = $this->getExpiresIn();
        }
        if (isset($this->chatExpiresIn) && null !== $this->getChatExpiresIn()) {
            $json['chat_expires_in'] = $this->getChatExpiresIn();
        }

        return $json;
    }

    /**
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function addTempConfigFields(array $json): array
    {
        if (isset($this->unionId) && null !== $this->getUnionId()) {
            $json['unionid'] = $this->getUnionId();
        }
        if (isset($this->conclusions) && null !== $this->getConclusions()) {
            $json['conclusions'] = $this->getConclusions();
        }

        return $json;
    }
}
