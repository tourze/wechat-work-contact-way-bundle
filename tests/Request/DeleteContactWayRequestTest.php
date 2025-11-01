<?php

namespace WechatWorkContactWayBundle\Tests\Request;

use HttpClientBundle\Request\ApiRequest;
use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkContactWayBundle\Request\DeleteContactWayRequest;

/**
 * DeleteContactWayRequest 测试
 *
 * @internal
 */
#[CoversClass(DeleteContactWayRequest::class)]
final class DeleteContactWayRequestTest extends RequestTestCase
{
    public function testInheritance(): void
    {
        // 测试继承关系
        $request = new DeleteContactWayRequest();
        $this->assertInstanceOf(ApiRequest::class, $request);
    }

    public function testRequestPath(): void
    {
        // 测试请求路径
        $request = new DeleteContactWayRequest();
        $this->assertStringContainsString('externalcontact/del_contact_way', $request->getRequestPath());
    }

    public function testConfigIdSetterAndGetter(): void
    {
        // 测试配置ID设置和获取
        $request = new DeleteContactWayRequest();
        $configId = 'config_12345';

        $request->setConfigId($configId);
        $this->assertSame($configId, $request->getConfigId());
    }

    public function testRequestOptions(): void
    {
        // 测试获取请求选项
        $request = new DeleteContactWayRequest();
        $configId = 'delete_config_001';

        $request->setConfigId($configId);

        $expected = [
            'json' => [
                'config_id' => $configId,
            ],
        ];

        $this->assertSame($expected, $request->getRequestOptions());
    }

    public function testRequestOptionsStructure(): void
    {
        // 测试请求选项结构
        $request = new DeleteContactWayRequest();
        $request->setConfigId('test_config');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertArrayHasKey('config_id', $json);
        $this->assertCount(1, $json);
    }

    public function testBusinessScenarioDeleteSalesContactWay(): void
    {
        // 测试业务场景：删除销售联系方式配置
        $request = new DeleteContactWayRequest();
        $salesConfigId = 'sales_qr_config_001';

        $request->setConfigId($salesConfigId);

        $this->assertSame($salesConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($salesConfigId, $json['config_id'] ?? null);

        // 验证API路径正确
        $this->assertStringContainsString('externalcontact/del_contact_way', $request->getRequestPath());
    }

    public function testBusinessScenarioDeleteExpiredContactWay(): void
    {
        // 测试业务场景：删除过期联系方式
        $request = new DeleteContactWayRequest();
        $expiredConfigId = 'expired_config_002';

        $request->setConfigId($expiredConfigId);

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($expiredConfigId, $json['config_id'] ?? null);
    }

    public function testBusinessScenarioCleanupUnusedContactWay(): void
    {
        // 测试业务场景：清理未使用的联系方式
        $request = new DeleteContactWayRequest();
        $unusedConfigId = 'unused_config_003';

        $request->setConfigId($unusedConfigId);

        $this->assertSame($unusedConfigId, $request->getConfigId());

        // 验证API路径符合删除要求
        $this->assertStringContainsString('del_contact_way', $request->getRequestPath());
    }

    public function testConfigIdSpecialCharacters(): void
    {
        // 测试配置ID特殊字符
        $request = new DeleteContactWayRequest();
        $specialConfigId = 'config-id_with.special@chars_123';

        $request->setConfigId($specialConfigId);

        $this->assertSame($specialConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($specialConfigId, $json['config_id'] ?? null);
    }

    public function testLongConfigId(): void
    {
        // 测试长配置ID
        $request = new DeleteContactWayRequest();
        $longConfigId = str_repeat('config_part_', 10) . 'end';

        $request->setConfigId($longConfigId);

        $this->assertSame($longConfigId, $request->getConfigId());
    }

    public function testUnicodeCharacters(): void
    {
        // 测试Unicode字符
        $request = new DeleteContactWayRequest();
        $unicodeConfigId = '删除_配置_ID_测试_123';

        $request->setConfigId($unicodeConfigId);

        $this->assertSame($unicodeConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($unicodeConfigId, $json['config_id'] ?? null);
    }

    public function testMultipleSetOperations(): void
    {
        // 测试多次设置值
        $request = new DeleteContactWayRequest();

        $firstConfigId = 'first_config_id';
        $secondConfigId = 'second_config_id';

        $request->setConfigId($firstConfigId);
        $this->assertSame($firstConfigId, $request->getConfigId());

        $request->setConfigId($secondConfigId);
        $this->assertSame($secondConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($secondConfigId, $json['config_id'] ?? null);
    }

    public function testIdempotentMethodCalls(): void
    {
        // 测试方法调用是幂等的
        $request = new DeleteContactWayRequest();
        $configId = 'idempotent_config_id';

        $request->setConfigId($configId);

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
        $request = new DeleteContactWayRequest();
        $originalConfigId = 'original_config_id';

        $request->setConfigId($originalConfigId);

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();

        // 修改返回的数组不应影响原始数据
        if (isset($options1['json']) && is_array($options1['json'])) {
            $options1['json']['config_id'] = 'modified_config_id';
            $options1['json']['new_field'] = 'new_value';
        }
        $options1['new_key'] = 'new_value';

        $this->assertSame($originalConfigId, $request->getConfigId());
        $this->assertNotNull($options2);
        $json2 = $options2['json'] ?? [];
        $this->assertIsArray($json2);
        $this->assertSame($originalConfigId, $json2['config_id'] ?? null);
        $this->assertArrayNotHasKey('new_field', $json2);
        $this->assertArrayNotHasKey('new_key', $options2);
    }

    public function testEmptyStringValue(): void
    {
        // 测试空字符串值
        $request = new DeleteContactWayRequest();
        $request->setConfigId('');

        $this->assertSame('', $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame('', $json['config_id'] ?? null);
    }

    public function testApiEndpointCorrectness(): void
    {
        // 测试API端点正确性
        $request = new DeleteContactWayRequest();
        $path = $request->getRequestPath();

        $this->assertStringContainsString('externalcontact', $path);
        $this->assertStringContainsString('del_contact_way', $path);
        $this->assertStringStartsWith('/', $path);
        $this->assertStringEndsWith('/del_contact_way', $path);
    }

    public function testJsonRequestFormat(): void
    {
        // 测试JSON请求格式
        $request = new DeleteContactWayRequest();
        $configId = 'json_format_config_id';

        $request->setConfigId($configId);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证使用json而不是query格式
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayNotHasKey('query', $options);
        $this->assertArrayNotHasKey('body', $options);
        $this->assertArrayNotHasKey('form_params', $options);
    }

    public function testBusinessScenarioConfigurationCleanup(): void
    {
        // 测试业务场景：配置清理
        $request = new DeleteContactWayRequest();
        $cleanupConfigId = 'cleanup_config_001';

        $request->setConfigId($cleanupConfigId);

        $this->assertSame($cleanupConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($cleanupConfigId, $json['config_id'] ?? null);

        // 验证API支持配置删除
        $this->assertStringContainsString('del_contact_way', $request->getRequestPath());
    }

    public function testRequestDataIntegrity(): void
    {
        // 测试请求数据完整性
        $request = new DeleteContactWayRequest();
        $configId = 'integrity_test_config_id';

        $request->setConfigId($configId);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证请求数据结构完整性
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($configId, $json['config_id'] ?? null);

        // 验证只包含必要的字段
        $this->assertCount(1, $options);
        $this->assertCount(1, $json);
    }

    public function testConfigIdValidation(): void
    {
        // 测试配置ID验证
        $request = new DeleteContactWayRequest();

        // 测试配置ID是必需的字符串
        $configId = 'validation_test_config_id';
        $request->setConfigId($configId);
        $this->assertSame($configId, $request->getConfigId());
    }

    public function testConfigIdFormats(): void
    {
        // 测试配置ID格式
        $request = new DeleteContactWayRequest();
        $formats = [
            'simple_config_id',
            'config-with-dashes',
            'config_with_underscores',
            'config.with.dots',
            'config123456',
            'UPPERCASE_CONFIG_ID',
        ];

        foreach ($formats as $format) {
            $request->setConfigId($format);
            $this->assertSame($format, $request->getConfigId());

            $options = $request->getRequestOptions();
            $this->assertIsArray($options['json'] ?? null);
            $json = $options['json'] ?? [];
            $this->assertSame($format, $json['config_id'] ?? null);
        }
    }

    public function testBusinessScenarioBatchCleanup(): void
    {
        // 测试业务场景：批量清理（单个请求）
        $request = new DeleteContactWayRequest();
        $batchConfigId = 'batch_delete_config_001';

        $request->setConfigId($batchConfigId);

        $this->assertSame($batchConfigId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame($batchConfigId, $json['config_id'] ?? null);

        // 验证API支持单个配置删除
        $this->assertStringContainsString('del_contact_way', $request->getRequestPath());
    }

    public function testConfigIdPersistence(): void
    {
        // 测试配置ID持久性
        $request = new DeleteContactWayRequest();
        $configId = 'persistence_test_config_id';

        $request->setConfigId($configId);

        // 多次获取应保持一致
        $this->assertSame($configId, $request->getConfigId());

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($configId, $json['config_id'] ?? null);

        // 再次获取选项应保持一致
        $optionsAgain = $request->getRequestOptions();
        $jsonAgain = $optionsAgain['json'] ?? [];
        $this->assertIsArray($jsonAgain);
        $this->assertSame($configId, $jsonAgain['config_id'] ?? null);
    }
}
