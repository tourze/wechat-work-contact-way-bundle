<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use WechatWorkBundle\Request\AgentAware;
use WechatWorkContactWayBundle\Entity\ContactWay;

/**
 * 更新企业已配置的「联系我」方式
 *
 * @see https://developer.work.weixin.qq.com/document/path/92228
 */
class UpdateContactWayRequest extends ApiRequest
{
    use AgentAware;
    use ContactWayField;

    /**
     * @var string 企业联系方式的配置id
     */
    private string $configId;

    private const API_PATH = 'cgi-bin/externalcontact/update_contact_way';

    public static function createFromObject(ContactWay $object): self
    {
        $request = new self();
        $request->populateFromObject($object);

        return $request;
    }

    public function getRequestPath(): string
    {
        return '/' . self::API_PATH;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $json = $this->getFieldJson();
        $json['config_id'] = $this->getConfigId();

        return [
            'json' => $json,
        ];
    }

    public function getConfigId(): string
    {
        return $this->configId;
    }

    public function setConfigId(string $configId): void
    {
        $this->configId = $configId;
    }
}
