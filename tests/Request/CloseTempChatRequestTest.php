<?php

namespace WechatWorkContactWayBundle\Tests\Request;

use HttpClientBundle\Request\ApiRequest;
use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkContactWayBundle\Request\CloseTempChatRequest;

/**
 * CloseTempChatRequest 测试
 *
 * @internal
 */
#[CoversClass(CloseTempChatRequest::class)]
final class CloseTempChatRequestTest extends RequestTestCase
{
    public function testInheritance(): void
    {
        // 测试继承关系
        $request = new CloseTempChatRequest();
        $this->assertInstanceOf(ApiRequest::class, $request);
    }

    public function testUserIdSetterAndGetter(): void
    {
        // 测试用户ID设置和获取
        $request = new CloseTempChatRequest();
        $userId = 'employee_001';

        $request->setUserId($userId);
        $this->assertSame($userId, $request->getUserId());
    }

    public function testExternalUserIdSetterAndGetter(): void
    {
        // 测试外部用户ID设置和获取
        $request = new CloseTempChatRequest();
        $externalUserId = 'external_user_123';

        $request->setExternalUserId($externalUserId);
        $this->assertSame($externalUserId, $request->getExternalUserId());
    }

    public function testRequestPath(): void
    {
        // 测试请求路径
        $request = new CloseTempChatRequest();
        $this->assertStringContainsString('externalcontact/close_temp_chat', $request->getRequestPath());
    }

    public function testRequestOptions(): void
    {
        // 测试获取请求选项
        $request = new CloseTempChatRequest();
        $userId = 'emp_001';
        $externalUserId = 'ext_user_123';

        $request->setUserId($userId);
        $request->setExternalUserId($externalUserId);

        $expected = [
            'json' => [
                'userid' => $userId,
                'external_userid' => $externalUserId,
            ],
        ];

        $this->assertSame($expected, $request->getRequestOptions());
    }

    public function testRequestOptionsStructure(): void
    {
        // 测试请求选项结构
        $request = new CloseTempChatRequest();
        $request->setUserId('test_user');
        $request->setExternalUserId('test_external');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertArrayHasKey('userid', $json);
        $this->assertArrayHasKey('external_userid', $json);
        $this->assertCount(2, $json);
    }

    public function testBusinessScenarioCloseCustomerTempChat(): void
    {
        // 测试业务场景：关闭客户临时会话
        $request = new CloseTempChatRequest();
        $employeeId = 'sales_manager_01';
        $customerId = 'customer_external_98765';

        $request->setUserId($employeeId);
        $request->setExternalUserId($customerId);

        $this->assertSame($employeeId, $request->getUserId());
        $this->assertSame($customerId, $request->getExternalUserId());

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($employeeId, $json['userid'] ?? null);
        $this->assertSame($customerId, $json['external_userid'] ?? null);

        // 验证API路径正确
        $this->assertStringContainsString('externalcontact/close_temp_chat', $request->getRequestPath());
    }

    public function testBusinessScenarioCloseServiceTempChat(): void
    {
        // 测试业务场景：关闭服务临时会话
        $request = new CloseTempChatRequest();
        $serviceUserId = 'customer_service_team';
        $externalClientId = 'client_external_456';

        $request->setUserId($serviceUserId);
        $request->setExternalUserId($externalClientId);

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($serviceUserId, $json['userid'] ?? null);
        $this->assertSame($externalClientId, $json['external_userid'] ?? null);
    }

    public function testSpecialCharactersInUserIds(): void
    {
        // 测试用户ID中的特殊字符
        $request = new CloseTempChatRequest();
        $specialUserId = 'user-name_with.special@chars';
        $specialExternalId = 'ext_user-123_test@domain';

        $request->setUserId($specialUserId);
        $request->setExternalUserId($specialExternalId);

        $this->assertSame($specialUserId, $request->getUserId());
        $this->assertSame($specialExternalId, $request->getExternalUserId());

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($specialUserId, $json['userid'] ?? null);
        $this->assertSame($specialExternalId, $json['external_userid'] ?? null);
    }

    public function testLongUserIds(): void
    {
        // 测试长用户ID
        $request = new CloseTempChatRequest();
        $longUserId = str_repeat('a', 100);
        $longExternalId = str_repeat('b', 120);

        $request->setUserId($longUserId);
        $request->setExternalUserId($longExternalId);

        $this->assertSame($longUserId, $request->getUserId());
        $this->assertSame($longExternalId, $request->getExternalUserId());
    }

    public function testMultipleSetOperations(): void
    {
        // 测试多次设置值
        $request = new CloseTempChatRequest();

        $firstUserId = 'first_user';
        $firstExternalId = 'first_external';
        $secondUserId = 'second_user';
        $secondExternalId = 'second_external';

        $request->setUserId($firstUserId);
        $request->setExternalUserId($firstExternalId);

        $this->assertSame($firstUserId, $request->getUserId());
        $this->assertSame($firstExternalId, $request->getExternalUserId());

        $request->setUserId($secondUserId);
        $request->setExternalUserId($secondExternalId);

        $this->assertSame($secondUserId, $request->getUserId());
        $this->assertSame($secondExternalId, $request->getExternalUserId());

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($secondUserId, $json['userid'] ?? null);
        $this->assertSame($secondExternalId, $json['external_userid'] ?? null);
    }

    public function testIdempotentMethodCalls(): void
    {
        // 测试方法调用是幂等的
        $request = new CloseTempChatRequest();
        $userId = 'idempotent_user';
        $externalUserId = 'idempotent_external';

        $request->setUserId($userId);
        $request->setExternalUserId($externalUserId);

        // 多次调用应该返回相同结果
        $this->assertSame($userId, $request->getUserId());
        $this->assertSame($userId, $request->getUserId());

        $this->assertSame($externalUserId, $request->getExternalUserId());
        $this->assertSame($externalUserId, $request->getExternalUserId());

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();
        $this->assertSame($options1, $options2);

        $path1 = $request->getRequestPath();
        $path2 = $request->getRequestPath();
        $this->assertSame($path1, $path2);
    }

    public function testImmutableRequestOptions(): void
    {
        // 测试获取请求选项不会修改原始数据
        $request = new CloseTempChatRequest();
        $originalUserId = 'original_user';
        $originalExternalId = 'original_external';

        $request->setUserId($originalUserId);
        $request->setExternalUserId($originalExternalId);

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();

        // 修改返回的数组不应影响原始数据
        $this->assertIsArray($options1['json'] ?? null);
        $options1['json']['userid'] = 'modified_user';
        $options1['json']['external_userid'] = 'modified_external';
        $options1['json']['new_field'] = 'new_value';
        $options1['new_key'] = 'new_value';

        $this->assertSame($originalUserId, $request->getUserId());
        $this->assertSame($originalExternalId, $request->getExternalUserId());

        $this->assertNotNull($options2);
        $json2 = $options2['json'] ?? [];
        $this->assertIsArray($json2);
        $this->assertSame($originalUserId, $json2['userid'] ?? null);
        $this->assertSame($originalExternalId, $json2['external_userid'] ?? null);
        $this->assertArrayNotHasKey('new_field', $json2);
        $this->assertArrayNotHasKey('new_key', $options2);
    }

    public function testUnicodeCharacters(): void
    {
        // 测试Unicode字符
        $request = new CloseTempChatRequest();
        $unicodeUserId = '用户_001_测试';
        $unicodeExternalId = '外部用户_123_🔥';

        $request->setUserId($unicodeUserId);
        $request->setExternalUserId($unicodeExternalId);

        $this->assertSame($unicodeUserId, $request->getUserId());
        $this->assertSame($unicodeExternalId, $request->getExternalUserId());

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($unicodeUserId, $json['userid'] ?? null);
        $this->assertSame($unicodeExternalId, $json['external_userid'] ?? null);
    }

    public function testEmptyStringValues(): void
    {
        // 测试空字符串值
        $request = new CloseTempChatRequest();
        $request->setUserId('');
        $request->setExternalUserId('');

        $this->assertSame('', $request->getUserId());
        $this->assertSame('', $request->getExternalUserId());

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame('', $json['userid'] ?? null);
        $this->assertSame('', $json['external_userid'] ?? null);
    }
}
