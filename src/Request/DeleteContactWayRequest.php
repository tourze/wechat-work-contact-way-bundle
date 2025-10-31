<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use WechatWorkBundle\Request\AgentAware;

/**
 * 删除「联系我」方式
 *
 * @see https://developer.work.weixin.qq.com/document/path/92228
 */
class DeleteContactWayRequest extends ApiRequest
{
    use AgentAware;

    private string $configId;

    private const API_PATH = 'cgi-bin/externalcontact/del_contact_way';

    public function getRequestPath(): string
    {
        return '/' . self::API_PATH;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'config_id' => $this->getConfigId(),
            ],
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
