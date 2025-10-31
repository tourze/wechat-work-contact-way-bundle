<?php

namespace WechatWorkContactWayBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use WechatWorkBundle\Request\AgentAware;
use WechatWorkContactWayBundle\Entity\ContactWay;

/**
 * 配置客户联系「联系我」方式
 *
 * @see https://developer.work.weixin.qq.com/document/path/92228
 */
class AddContactWayRequest extends ApiRequest
{
    use AgentAware;
    use ContactWayField;

    private const API_PATH = 'cgi-bin/externalcontact/add_contact_way';

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

        return [
            'json' => $json,
        ];
    }
}
