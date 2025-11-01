<?php

namespace WechatWorkContactWayBundle\Tests\Request;

use HttpClientBundle\Request\ApiRequest;
use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkContactWayBundle\Request\AddContactWayRequest;

/**
 * AddContactWayRequest 测试
 *
 * @internal
 */
#[CoversClass(AddContactWayRequest::class)]
final class AddContactWayRequestTest extends RequestTestCase
{
    public function testInheritance(): void
    {
        // 测试继承关系
        $request = new AddContactWayRequest();
        $this->assertInstanceOf(ApiRequest::class, $request);
    }

    public function testRequestPath(): void
    {
        // 测试请求路径
        $request = new AddContactWayRequest();
        $this->assertStringContainsString('externalcontact/add_contact_way', $request->getRequestPath());
    }

    public function testTypeSetterAndGetter(): void
    {
        // 测试联系方式类型设置和获取
        $request = new AddContactWayRequest();

        $request->setType(1); // 单人
        $this->assertSame(1, $request->getType());

        $request->setType(2); // 多人
        $this->assertSame(2, $request->getType());
    }

    public function testSceneSetterAndGetter(): void
    {
        // 测试场景设置和获取
        $request = new AddContactWayRequest();

        $request->setScene(1); // 小程序中联系
        $this->assertSame(1, $request->getScene());

        $request->setScene(2); // 通过二维码联系
        $this->assertSame(2, $request->getScene());
    }

    public function testStyleSetterAndGetter(): void
    {
        // 测试控件样式设置和获取
        $request = new AddContactWayRequest();

        $request->setStyle(1);
        $this->assertSame(1, $request->getStyle());

        $request->setStyle(null);
        $this->assertNull($request->getStyle());
    }

    public function testUserSetterAndGetter(): void
    {
        // 测试用户列表设置和获取
        $request = new AddContactWayRequest();
        $users = ['user001', 'user002'];

        $request->setUser($users);
        $this->assertSame($users, $request->getUser());

        $request->setUser(null);
        $this->assertNull($request->getUser());
    }

    public function testSkipVerifySetterAndGetter(): void
    {
        // 测试是否需要验证设置和获取
        $request = new AddContactWayRequest();

        $request->setSkipVerify(true);
        $this->assertTrue($request->isSkipVerify());

        $request->setSkipVerify(false);
        $this->assertFalse($request->isSkipVerify());
    }

    public function testStateSetterAndGetter(): void
    {
        // 测试自定义参数设置和获取
        $request = new AddContactWayRequest();
        $state = 'channel_001';

        $request->setState($state);
        $this->assertSame($state, $request->getState());

        $request->setState(null);
        $this->assertNull($request->getState());
    }

    public function testPartySetterAndGetter(): void
    {
        // 测试部门列表设置和获取
        $request = new AddContactWayRequest();
        $parties = ['100', '200', '300'];

        $request->setParty($parties);
        $this->assertSame($parties, $request->getParty());

        $request->setParty(null);
        $this->assertNull($request->getParty());
    }

    public function testTempSetterAndGetter(): void
    {
        // 测试临时会话模式设置和获取
        $request = new AddContactWayRequest();

        $request->setTemp(true);
        $this->assertTrue($request->isTemp());

        $request->setTemp(false);
        $this->assertFalse($request->isTemp());
    }

    public function testExpiresInSetterAndGetter(): void
    {
        // 测试二维码有效期设置和获取
        $request = new AddContactWayRequest();
        $expiresIn = 86400; // 1天

        $request->setExpiresIn($expiresIn);
        $this->assertSame($expiresIn, $request->getExpiresIn());

        $request->setExpiresIn(null);
        $this->assertNull($request->getExpiresIn());
    }

    public function testChatExpiresInSetterAndGetter(): void
    {
        // 测试临时会话有效期设置和获取
        $request = new AddContactWayRequest();
        $chatExpiresIn = 172800; // 2天

        $request->setChatExpiresIn($chatExpiresIn);
        $this->assertSame($chatExpiresIn, $request->getChatExpiresIn());

        $request->setChatExpiresIn(null);
        $this->assertNull($request->getChatExpiresIn());
    }

    public function testUnionIdSetterAndGetter(): void
    {
        // 测试联合ID设置和获取
        $request = new AddContactWayRequest();
        $unionId = 'union_id_123';

        $request->setUnionId($unionId);
        $this->assertSame($unionId, $request->getUnionId());

        $request->setUnionId(null);
        $this->assertNull($request->getUnionId());
    }

    public function testExclusiveSetterAndGetter(): void
    {
        // 测试独占模式设置和获取
        $request = new AddContactWayRequest();

        $request->setExclusive(true);
        $this->assertTrue($request->isExclusive());

        $request->setExclusive(false);
        $this->assertFalse($request->isExclusive());
    }

    public function testConclusionsSetterAndGetter(): void
    {
        // 测试结束语设置和获取
        $request = new AddContactWayRequest();
        $conclusions = [
            ['text' => ['content' => '感谢您的咨询！']],
        ];

        $request->setConclusions($conclusions);
        $this->assertSame($conclusions, $request->getConclusions());

        $request->setConclusions(null);
        $this->assertNull($request->getConclusions());
    }

    public function testRemarkSetterAndGetter(): void
    {
        // 测试备注设置和获取
        $request = new AddContactWayRequest();
        $remark = '销售渠道联系方式';

        $request->setRemark($remark);
        $this->assertSame($remark, $request->getRemark());

        $request->setRemark(null);
        $this->assertNull($request->getRemark());
    }

    public function testRequestOptionsBasicConfiguration(): void
    {
        // 测试基本配置的请求选项
        $request = new AddContactWayRequest();
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['user001']);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('type', $options['json']);
        $this->assertArrayHasKey('scene', $options['json']);
        $this->assertArrayHasKey('user', $options['json']);
        $this->assertSame(1, $options['json']['type']);
        $this->assertSame(2, $options['json']['scene']);
        $this->assertSame(['user001'], $options['json']['user']);
    }

    public function testRequestOptionsAdvancedConfiguration(): void
    {
        // 测试高级配置的请求选项
        $request = new AddContactWayRequest();
        $request->setType(2);
        $request->setScene(1);
        $request->setStyle(1);
        $request->setParty(['100', '200']);
        $request->setSkipVerify(false);
        $request->setState('advanced_channel');
        $request->setTemp(true);
        $request->setExpiresIn(86400);
        $request->setChatExpiresIn(172800);
        $request->setUnionId('union_advanced');
        $request->setExclusive(true);
        $request->setRemark('高级渠道');

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];

        $this->assertIsArray($json);
        $this->assertSame(2, $json['type']);
        $this->assertSame(1, $json['scene']);
        $this->assertSame(1, $json['style']);
        $this->assertSame(['100', '200'], $json['party']);
        $this->assertFalse($json['skip_verify']);
        $this->assertSame('advanced_channel', $json['state']);
        $this->assertTrue($json['is_temp']);
        $this->assertSame(86400, $json['expires_in']);
        $this->assertSame(172800, $json['chat_expires_in']);
        $this->assertSame('union_advanced', $json['unionid']);
        $this->assertTrue($json['is_exclusive']);
        $this->assertSame('高级渠道', $json['remark']);
    }

    public function testBusinessScenarioSingleUserQRCode(): void
    {
        // 测试业务场景：单人二维码联系方式
        $request = new AddContactWayRequest();
        $request->setType(1); // 单人
        $request->setScene(2); // 二维码
        $request->setUser(['sales_001']); // 销售员工
        $request->setSkipVerify(true); // 无需验证
        $request->setState('qr_sales_channel'); // 销售渠道
        $request->setRemark('销售二维码');

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];

        $this->assertIsArray($json);
        $this->assertSame(1, $json['type']);
        $this->assertSame(2, $json['scene']);
        $this->assertSame(['sales_001'], $json['user']);
        $this->assertTrue($json['skip_verify']);
        $this->assertSame('qr_sales_channel', $json['state']);
        $this->assertSame('销售二维码', $json['remark']);

        // 验证API路径
        $this->assertStringContainsString('externalcontact/add_contact_way', $request->getRequestPath());
    }

    public function testBusinessScenarioMultiUserMiniProgram(): void
    {
        // 测试业务场景：多人小程序联系方式
        $request = new AddContactWayRequest();
        $request->setType(2); // 多人
        $request->setScene(1); // 小程序
        $request->setStyle(1); // 控件样式
        $request->setParty(['100', '200']); // 销售部门
        $request->setSkipVerify(false); // 需要验证
        $request->setState('miniprogram_channel');
        $request->setRemark('小程序联系');

        $options = $request->getRequestOptions();
        $json = $options['json'] ?? [];

        $this->assertIsArray($json);
        $this->assertSame(2, $json['type']);
        $this->assertSame(1, $json['scene']);
        $this->assertSame(1, $json['style']);
        $this->assertSame(['100', '200'], $json['party']);
        $this->assertFalse($json['skip_verify']);
        $this->assertSame('miniprogram_channel', $json['state']);
    }

    public function testBusinessScenarioTemporarySession(): void
    {
        // 测试业务场景：临时会话
        $request = new AddContactWayRequest();
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['cs_001']); // 客服
        $request->setTemp(true); // 临时会话
        $request->setExpiresIn(604800); // 7天有效期
        $request->setChatExpiresIn(86400); // 24小时会话
        $request->setUnionId('temp_union_123');
        $request->setConclusions([
            ['text' => ['content' => '感谢您的咨询，再见！']],
        ]);
        $request->setState('temp_cs_channel');
        $request->setRemark('临时客服');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertTrue($options['json']['is_temp']);
        $this->assertSame(604800, $options['json']['expires_in']);
        $this->assertSame(86400, $options['json']['chat_expires_in']);
        $this->assertSame('temp_union_123', $options['json']['unionid']);
        $this->assertArrayHasKey('conclusions', $options['json']);
        $this->assertSame('temp_cs_channel', $options['json']['state']);
    }

    public function testBusinessScenarioExclusiveContact(): void
    {
        // 测试业务场景：独占联系方式
        $request = new AddContactWayRequest();
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['exclusive_001']);
        $request->setExclusive(true); // 独占模式
        $request->setState('exclusive_channel');
        $request->setRemark('独占客户联系');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertTrue($options['json']['is_exclusive']);
        $this->assertSame('exclusive_channel', $options['json']['state']);
        $this->assertSame('独占客户联系', $options['json']['remark']);
    }

    public function testStateMaxLength(): void
    {
        // 测试state参数最大长度
        $request = new AddContactWayRequest();
        $longState = str_repeat('a', 30); // 30个字符

        $request->setState($longState);
        $this->assertSame($longState, $request->getState());
        $this->assertSame(30, strlen($request->getState()));
    }

    public function testRemarkMaxLength(): void
    {
        // 测试remark参数最大长度
        $request = new AddContactWayRequest();
        $longRemark = str_repeat('备', 30); // 30个字符

        $request->setRemark($longRemark);
        $this->assertSame($longRemark, $request->getRemark());
        $this->assertSame(30, mb_strlen($request->getRemark()));
    }

    public function testExpiresInBoundaryValues(): void
    {
        // 测试有效期边界值
        $request = new AddContactWayRequest();

        // 最小值：1秒
        $request->setExpiresIn(1);
        $this->assertSame(1, $request->getExpiresIn());

        // 7天（默认值）
        $request->setExpiresIn(604800);
        $this->assertSame(604800, $request->getExpiresIn());

        // 最大值：14天
        $request->setExpiresIn(1209600);
        $this->assertSame(1209600, $request->getExpiresIn());
    }

    public function testChatExpiresInBoundaryValues(): void
    {
        // 测试会话有效期边界值
        $request = new AddContactWayRequest();

        // 24小时（默认值）
        $request->setChatExpiresIn(86400);
        $this->assertSame(86400, $request->getChatExpiresIn());

        // 最大值：14天
        $request->setChatExpiresIn(1209600);
        $this->assertSame(1209600, $request->getChatExpiresIn());
    }

    public function testLargeUserArray(): void
    {
        // 测试大用户数组
        $request = new AddContactWayRequest();
        $users = [];
        for ($i = 0; $i < 100; ++$i) {
            $users[] = "user_{$i}";
        }

        $request->setUser($users);
        $this->assertSame($users, $request->getUser());
        $this->assertCount(100, $request->getUser());
    }

    public function testLargePartyArray(): void
    {
        // 测试大部门数组
        $request = new AddContactWayRequest();
        $parties = [];
        for ($i = 1; $i <= 50; ++$i) {
            $parties[] = (string) ($i * 100);
        }

        $request->setParty($parties);
        $this->assertSame($parties, $request->getParty());
        $this->assertCount(50, $request->getParty());
    }

    public function testMultipleSetOperations(): void
    {
        // 测试多次设置值
        $request = new AddContactWayRequest();

        $request->setType(1);
        $request->setType(2);
        $this->assertSame(2, $request->getType());

        $request->setScene(1);
        $request->setScene(2);
        $this->assertSame(2, $request->getScene());

        $request->setState('first');
        $request->setState('second');
        $this->assertSame('second', $request->getState());
    }

    public function testIdempotentMethodCalls(): void
    {
        // 测试方法调用是幂等的
        $request = new AddContactWayRequest();
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['user001']);

        // 多次调用应该返回相同结果
        $this->assertSame(1, $request->getType());
        $this->assertSame(1, $request->getType());

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();
        $this->assertNotNull($options1);
        $this->assertNotNull($options2);
        $this->assertSame($options1, $options2);

        $path1 = $request->getRequestPath();
        $path2 = $request->getRequestPath();
        $this->assertSame($path1, $path2);
    }

    public function testImmutableRequestOptions(): void
    {
        // 测试获取请求选项不会修改原始数据
        $request = new AddContactWayRequest();
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['user001']);
        $request->setState('original');

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();

        // 修改返回的数组不应影响原始数据
        if (isset($options1['json']) && is_array($options1['json'])) {
            $options1['json']['type'] = 2;
            $options1['json']['state'] = 'modified';
            $options1['json']['new_field'] = 'new_value';
        }
        $options1['new_key'] = 'new_value';

        $this->assertSame(1, $request->getType());
        $this->assertSame('original', $request->getState());

        $this->assertIsArray($options2['json'] ?? null);
        $json2 = $options2['json'] ?? [];
        $this->assertSame(1, $json2['type'] ?? null);
        $this->assertSame('original', $json2['state'] ?? null);
        $this->assertArrayNotHasKey('new_field', $json2);
        $this->assertArrayNotHasKey('new_key', $options2);
    }

    public function testContactWayFieldTrait(): void
    {
        // 测试ContactWayField特性
        $request = new AddContactWayRequest();

        // 验证trait提供的方法都存在
        $methods = [
            'getType', 'setType', 'getScene', 'setScene', 'getStyle', 'setStyle',
            'getUser', 'setUser', 'isSkipVerify', 'setSkipVerify', 'getState', 'setState',
            'getParty', 'setParty', 'isTemp', 'setTemp', 'getExpiresIn', 'setExpiresIn',
            'getChatExpiresIn', 'setChatExpiresIn', 'getUnionId', 'setUnionId',
            'isExclusive', 'setExclusive', 'getConclusions', 'setConclusions',
            'getRemark', 'setRemark',
        ];

        foreach ($methods as $method) {
            $this->assertTrue(method_exists($request, $method));
        }
    }

    public function testApiEndpointCorrectness(): void
    {
        // 测试API端点正确性
        $request = new AddContactWayRequest();
        $path = $request->getRequestPath();

        $this->assertStringContainsString('externalcontact', $path);
        $this->assertStringContainsString('add_contact_way', $path);
        $this->assertStringStartsWith('/', $path);
        $this->assertStringEndsWith('/add_contact_way', $path);
    }

    public function testJsonRequestFormat(): void
    {
        // 测试JSON请求格式
        $request = new AddContactWayRequest();
        $request->setType(1);
        $request->setScene(2);
        $request->setUser(['user001']);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证使用json而不是query格式
        $json = $options['json'] ?? [];
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayNotHasKey('query', $options);
        $this->assertArrayNotHasKey('body', $options);
        $this->assertArrayNotHasKey('form_params', $options);
    }

    public function testRequestDataIntegrity(): void
    {
        // 测试请求数据完整性
        $request = new AddContactWayRequest();
        $request->setType(2);
        $request->setScene(1);
        $request->setParty(['100']);
        $request->setState('integrity_test');

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证请求数据结构完整性
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame(2, $json['type']);
        $this->assertSame(1, $json['scene']);
        $this->assertSame(['100'], $json['party']);
        $this->assertSame('integrity_test', $json['state']);

        // 验证只包含设置的字段
        $this->assertCount(1, $options);
    }
}
