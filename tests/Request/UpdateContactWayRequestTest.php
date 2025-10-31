<?php

namespace WechatWorkContactWayBundle\Tests\Request;

use HttpClientBundle\Request\ApiRequest;
use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use WechatWorkContactWayBundle\Request\UpdateContactWayRequest;

/**
 * UpdateContactWayRequest 测试
 *
 * @internal
 */
#[CoversClass(UpdateContactWayRequest::class)]
final class UpdateContactWayRequestTest extends RequestTestCase
{
    public function testInheritance(): void
    {
        // 测试继承关系
        $request = new UpdateContactWayRequest();
        $this->assertInstanceOf(ApiRequest::class, $request);
    }

    public function testRequestPath(): void
    {
        // 测试请求路径
        $request = new UpdateContactWayRequest();
        $this->assertStringContainsString('externalcontact/update_contact_way', $request->getRequestPath());
    }

    public function testConfigIdSetterAndGetter(): void
    {
        // 测试配置ID设置和获取
        $request = new UpdateContactWayRequest();
        $configId = 'config_12345';

        $request->setConfigId($configId);
        $this->assertSame($configId, $request->getConfigId());
    }

    public function testContactWayFields(): void
    {
        // 测试ContactWayField字段
        $request = new UpdateContactWayRequest();

        // 测试基本字段
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['user001']);
        $request->setSkipVerify(true);
        $request->setState('update_test');

        $this->assertSame(1, $request->getType());
        $this->assertSame(2, $request->getScene());
        $this->assertSame(['user001'], $request->getUser());
        $this->assertTrue($request->isSkipVerify());
        $this->assertSame('update_test', $request->getState());
    }

    public function testRequestOptions(): void
    {
        // 测试获取请求选项
        $request = new UpdateContactWayRequest();
        $configId = 'update_config_001';

        $request->setConfigId($configId);
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['user001']);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertArrayHasKey('config_id', $json);
        $this->assertArrayHasKey('type', $json);
        $this->assertArrayHasKey('scene', $json);
        $this->assertSame($configId, $json['config_id'] ?? null);
    }

    public function testBusinessScenarioUpdateSalesContactWay(): void
    {
        // 测试业务场景：更新销售联系方式
        $request = new UpdateContactWayRequest();
        $salesConfigId = 'sales_config_001';

        $request->setConfigId($salesConfigId);
        $request->setType(1); // 单人
        $request->setScene(2); // 二维码
        $request->setUser(['sales_manager_001']);
        $request->setSkipVerify(false); // 需要验证
        $request->setState('updated_sales_channel');
        $request->setRemark('更新后的销售渠道');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];

        $this->assertSame($salesConfigId, $json['config_id'] ?? null);
        $this->assertSame(1, $json['type'] ?? null);
        $this->assertSame(2, $json['scene'] ?? null);
        $this->assertSame(['sales_manager_001'], $json['user'] ?? null);
        $this->assertFalse($json['skip_verify'] ?? null);
        $this->assertSame('updated_sales_channel', $json['state'] ?? null);
        $this->assertSame('更新后的销售渠道', $json['remark'] ?? null);

        // 验证API路径正确
        $this->assertStringContainsString('externalcontact/update_contact_way', $request->getRequestPath());
    }

    public function testBusinessScenarioUpdateMultiUserContactWay(): void
    {
        // 测试业务场景：更新多人联系方式
        $request = new UpdateContactWayRequest();
        $multiConfigId = 'multi_config_002';

        $request->setConfigId($multiConfigId);
        $request->setType(2); // 多人
        $request->setScene(1); // 小程序
        $request->setStyle(1);
        $request->setParty(['100', '200']); // 部门
        $request->setSkipVerify(true);
        $request->setState('updated_multi_channel');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];

        $this->assertSame($multiConfigId, $json['config_id'] ?? null);
        $this->assertSame(2, $json['type'] ?? null);
        $this->assertSame(1, $json['scene'] ?? null);
        $this->assertSame(1, $json['style'] ?? null);
        $this->assertSame(['100', '200'], $json['party'] ?? null);
        $this->assertTrue($json['skip_verify'] ?? null);
    }

    public function testBusinessScenarioUpdateTemporaryContactWay(): void
    {
        // 测试业务场景：更新临时联系方式
        $request = new UpdateContactWayRequest();
        $tempConfigId = 'temp_config_003';

        $request->setConfigId($tempConfigId);
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['temp_service_001']);
        $request->setTemp(true); // 临时会话
        $request->setExpiresIn(172800); // 2天
        $request->setChatExpiresIn(86400); // 1天
        $request->setUnionId('temp_union_updated');
        $request->setConclusions([
            ['text' => ['content' => '更新后的结束语']],
        ]);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];

        $this->assertSame($tempConfigId, $json['config_id'] ?? null);
        $this->assertTrue($json['is_temp'] ?? null);
        $this->assertSame(172800, $json['expires_in'] ?? null);
        $this->assertSame(86400, $json['chat_expires_in'] ?? null);
        $this->assertSame('temp_union_updated', $json['unionid'] ?? null);
        $this->assertArrayHasKey('conclusions', $json);
    }

    public function testConfigIdWithTraitFields(): void
    {
        // 测试配置ID与trait字段结合
        $request = new UpdateContactWayRequest();
        $configId = 'combined_config_test';

        $request->setConfigId($configId);
        $request->setType(1);
        $request->setScene(2);
        $request->setExclusive(true);
        $request->setRemark('组合测试');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        // 验证config_id包含在json中
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertArrayHasKey('config_id', $json);
        $this->assertSame($configId, $json['config_id'] ?? null);

        // 验证trait字段也存在
        $this->assertArrayHasKey('type', $json);
        $this->assertArrayHasKey('scene', $json);
        $this->assertArrayHasKey('is_exclusive', $json);
        $this->assertArrayHasKey('remark', $json);
    }

    public function testMultipleSetOperations(): void
    {
        // 测试多次设置值
        $request = new UpdateContactWayRequest();

        $request->setConfigId('first_config');
        $request->setConfigId('second_config');
        $this->assertSame('second_config', $request->getConfigId());

        $request->setType(1);
        $request->setType(2);
        $this->assertSame(2, $request->getType());

        $request->setState('first_state');
        $request->setState('second_state');
        $this->assertSame('second_state', $request->getState());
    }

    public function testIdempotentMethodCalls(): void
    {
        // 测试方法调用是幂等的
        $request = new UpdateContactWayRequest();
        $configId = 'idempotent_config';

        $request->setConfigId($configId);
        $request->setType(1);
        $request->setScene(2);

        // 多次调用应该返回相同结果
        $this->assertSame($configId, $request->getConfigId());
        $this->assertSame($configId, $request->getConfigId());

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
        $request = new UpdateContactWayRequest();
        $originalConfigId = 'original_config';

        $request->setConfigId($originalConfigId);
        $request->setType(1);
        $request->setState('original_state');

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();

        // 修改返回的数组不应影响原始数据
        $this->assertNotNull($options1);
        $this->assertArrayHasKey('json', $options1);
        $this->assertIsArray($options1['json'] ?? null);
        $json1 = $options1['json'] ?? [];
        $json1['config_id'] = 'modified_config';
        $json1['type'] = 2;
        $json1['state'] = 'modified_state';
        $json1['new_field'] = 'new_value';

        $this->assertSame($originalConfigId, $request->getConfigId());
        $this->assertSame(1, $request->getType());
        $this->assertSame('original_state', $request->getState());

        $this->assertNotNull($options2);
        $this->assertIsArray($options2);
        $this->assertArrayHasKey('json', $options2);
        $this->assertIsArray($options2['json'] ?? null);
        $json2 = $options2['json'] ?? [];
        $this->assertSame($originalConfigId, $json2['config_id'] ?? null);
        $this->assertSame(1, $json2['type'] ?? null);
        $this->assertSame('original_state', $json2['state'] ?? null);
        $this->assertArrayNotHasKey('new_field', $json2);
    }

    public function testApiEndpointCorrectness(): void
    {
        // 测试API端点正确性
        $request = new UpdateContactWayRequest();
        $path = $request->getRequestPath();

        $this->assertStringContainsString('externalcontact', $path);
        $this->assertStringContainsString('update_contact_way', $path);
        $this->assertStringStartsWith('/', $path);
        $this->assertStringEndsWith('/update_contact_way', $path);
    }

    public function testJsonRequestFormat(): void
    {
        // 测试JSON请求格式
        $request = new UpdateContactWayRequest();
        $request->setConfigId('json_test_config');
        $request->setType(1);
        $request->setScene(2);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证使用json而不是query格式
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayNotHasKey('query', $options);
        $this->assertArrayNotHasKey('body', $options);
        $this->assertArrayNotHasKey('form_params', $options);
    }

    public function testRequestDataIntegrity(): void
    {
        // 测试请求数据完整性
        $request = new UpdateContactWayRequest();
        $configId = 'integrity_test_config';

        $request->setConfigId($configId);
        $request->setType(2);
        $request->setScene(1);
        $request->setParty(['100']);
        $request->setState('integrity_test');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证请求数据结构完整性
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($configId, $json['config_id'] ?? null);
        $this->assertSame(2, $json['type'] ?? null);
        $this->assertSame(1, $json['scene'] ?? null);
        $this->assertSame(['100'], $json['party'] ?? null);
        $this->assertSame('integrity_test', $json['state'] ?? null);

        // 验证只包含设置的字段
        $this->assertCount(1, $options);
    }

    public function testConfigIdSpecialCharacters(): void
    {
        // 测试配置ID特殊字符
        $request = new UpdateContactWayRequest();
        $specialConfigId = 'update-config_with.special@chars_123';

        $request->setConfigId($specialConfigId);
        $request->setType(1);
        $request->setScene(2);

        $this->assertSame($specialConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($specialConfigId, $json['config_id'] ?? null);
    }

    public function testEmptyStringConfigId(): void
    {
        // 测试空字符串配置ID
        $request = new UpdateContactWayRequest();
        $request->setConfigId('');
        $request->setType(1);
        $request->setScene(2);

        $this->assertSame('', $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame('', $json['config_id'] ?? null);
    }

    public function testConfigIdPersistence(): void
    {
        // 测试配置ID持久性
        $request = new UpdateContactWayRequest();
        $configId = 'persistence_test_config';

        $request->setConfigId($configId);
        $request->setType(1);
        $request->setScene(2);

        // 多次获取应保持一致
        $this->assertSame($configId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($configId, $json['config_id'] ?? null);

        // 再次获取选项应保持一致
        $optionsAgain = $request->getRequestOptions();
        $this->assertNotNull($optionsAgain);
        $this->assertIsArray($optionsAgain);
        $this->assertArrayHasKey('json', $optionsAgain);
        $this->assertIsArray($optionsAgain['json'] ?? null);
        $jsonAgain = $optionsAgain['json'] ?? [];
        $this->assertSame($configId, $jsonAgain['config_id'] ?? null);
    }
}
