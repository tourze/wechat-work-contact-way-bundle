<?php

namespace WechatWorkContactWayBundle\Tests\Request;

use HttpClientBundle\Request\ApiRequest;
use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use WechatWorkContactWayBundle\Request\ListContactWayRequest;

/**
 * ListContactWayRequest 测试
 *
 * @internal
 */
#[CoversClass(ListContactWayRequest::class)]
final class ListContactWayRequestTest extends RequestTestCase
{
    public function testInheritance(): void
    {
        // 测试继承关系
        $request = new ListContactWayRequest();
        $this->assertInstanceOf(ApiRequest::class, $request);
    }

    public function testRequestPath(): void
    {
        // 测试请求路径
        $request = new ListContactWayRequest();
        $this->assertStringContainsString('externalcontact/list_contact_way', $request->getRequestPath());
    }

    public function testDefaultValues(): void
    {
        // 测试默认值
        $request = new ListContactWayRequest();

        $this->assertSame(100, $request->getLimit()); // 默认值100
        $this->assertNull($request->getStartTime());
        $this->assertNull($request->getEndTime());
        $this->assertNull($request->getCursor());
    }

    public function testStartTimeSetterAndGetter(): void
    {
        // 测试开始时间设置和获取
        $request = new ListContactWayRequest();
        $startTime = time() - 7 * 24 * 3600; // 7天前

        $request->setStartTime($startTime);
        $this->assertSame($startTime, $request->getStartTime());

        $request->setStartTime(null);
        $this->assertNull($request->getStartTime());
    }

    public function testEndTimeSetterAndGetter(): void
    {
        // 测试结束时间设置和获取
        $request = new ListContactWayRequest();
        $endTime = time(); // 当前时间

        $request->setEndTime($endTime);
        $this->assertSame($endTime, $request->getEndTime());

        $request->setEndTime(null);
        $this->assertNull($request->getEndTime());
    }

    public function testCursorSetterAndGetter(): void
    {
        // 测试游标设置和获取
        $request = new ListContactWayRequest();
        $cursor = 'next_cursor_12345';

        $request->setCursor($cursor);
        $this->assertSame($cursor, $request->getCursor());

        $request->setCursor(null);
        $this->assertNull($request->getCursor());
    }

    public function testLimitSetterAndGetter(): void
    {
        // 测试限制数量设置和获取
        $request = new ListContactWayRequest();

        $request->setLimit(50);
        $this->assertSame(50, $request->getLimit());

        $request->setLimit(1000); // 最大值
        $this->assertSame(1000, $request->getLimit());

        $request->setLimit(1);
        $this->assertSame(1, $request->getLimit());
    }

    public function testRequestOptionsDefaultOnly(): void
    {
        // 测试只有默认值的请求选项
        $request = new ListContactWayRequest();

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertArrayHasKey('limit', $json);
        $this->assertSame(100, $json['limit'] ?? null);

        // 应该只包含limit字段
        $this->assertCount(1, $json);
        $this->assertArrayNotHasKey('start_time', $json);
        $this->assertArrayNotHasKey('end_time', $json);
        $this->assertArrayNotHasKey('cursor', $json);
    }

    public function testRequestOptionsWithAllFields(): void
    {
        // 测试包含所有字段的请求选项
        $request = new ListContactWayRequest();
        $startTime = time() - 7 * 24 * 3600;
        $endTime = time();
        $cursor = 'cursor_12345';
        $limit = 50;

        $request->setStartTime($startTime);
        $request->setEndTime($endTime);
        $request->setCursor($cursor);
        $request->setLimit($limit);

        $options = $request->getRequestOptions();

        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($startTime, $json['start_time'] ?? null);
        $this->assertSame($endTime, $json['end_time'] ?? null);
        $this->assertSame($cursor, $json['cursor'] ?? null);
        $this->assertSame($limit, $json['limit'] ?? null);
        $this->assertCount(4, $json);
    }

    public function testRequestOptionsPartialFields(): void
    {
        // 测试部分字段的请求选项
        $request = new ListContactWayRequest();
        $startTime = time() - 24 * 3600; // 1天前
        $limit = 200;

        $request->setStartTime($startTime);
        $request->setLimit($limit);
        // endTime和cursor保持null

        $options = $request->getRequestOptions();

        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('start_time', $json);
        $this->assertArrayHasKey('limit', $json);
        $this->assertArrayNotHasKey('end_time', $json);
        $this->assertArrayNotHasKey('cursor', $json);
        $this->assertSame($startTime, $json['start_time'] ?? null);
        $this->assertSame($limit, $json['limit'] ?? null);
        $this->assertCount(2, $json);
    }

    public function testBusinessScenarioFirstPageList(): void
    {
        // 测试业务场景：获取第一页列表
        $request = new ListContactWayRequest();
        $request->setLimit(20); // 每页20条

        $options = $request->getRequestOptions();

        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame(20, $json['limit'] ?? null);
        $this->assertArrayNotHasKey('cursor', $json);

        // 验证API路径正确
        $this->assertStringContainsString('externalcontact/list_contact_way', $request->getRequestPath());
    }

    public function testBusinessScenarioPaginatedList(): void
    {
        // 测试业务场景：分页获取列表
        $request = new ListContactWayRequest();
        $cursor = 'page_2_cursor_abc123';
        $limit = 100;

        $request->setCursor($cursor);
        $request->setLimit($limit);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];

        $this->assertSame($cursor, $json['cursor'] ?? null);
        $this->assertSame($limit, $json['limit'] ?? null);

        // 验证分页参数正确
        $this->assertArrayHasKey('cursor', $json);
        $this->assertArrayHasKey('limit', $json);
    }

    public function testBusinessScenarioTimeRangeList(): void
    {
        // 测试业务场景：按时间范围获取列表
        $request = new ListContactWayRequest();
        $startTime = strtotime('2024-01-01 00:00:00');
        $endTime = strtotime('2024-01-31 23:59:59');

        $request->setStartTime($startTime);
        $request->setEndTime($endTime);
        $request->setLimit(500);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];

        $this->assertSame($startTime, $json['start_time'] ?? null);
        $this->assertSame($endTime, $json['end_time'] ?? null);
        $this->assertSame(500, $json['limit'] ?? null);

        // 验证时间范围查询
        $this->assertArrayHasKey('start_time', $json);
        $this->assertArrayHasKey('end_time', $json);
    }

    public function testBusinessScenarioRecentContactWays(): void
    {
        // 测试业务场景：获取最近的联系方式
        $request = new ListContactWayRequest();
        $recentTime = time() - 3 * 24 * 3600; // 最近3天

        $request->setStartTime($recentTime);
        $request->setLimit(10); // 只要最近10个

        $options = $request->getRequestOptions();

        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($recentTime, $json['start_time'] ?? null);
        $this->assertSame(10, $json['limit'] ?? null);
        $this->assertArrayNotHasKey('end_time', $json); // 不设置结束时间
    }

    public function testLimitBoundaryValues(): void
    {
        // 测试限制数量边界值
        $request = new ListContactWayRequest();

        // 最小值：1
        $request->setLimit(1);
        $this->assertSame(1, $request->getLimit());

        // 默认值：100
        $request->setLimit(100);
        $this->assertSame(100, $request->getLimit());

        // 最大值：1000
        $request->setLimit(1000);
        $this->assertSame(1000, $request->getLimit());
    }

    public function testTimestampValues(): void
    {
        // 测试时间戳值
        $request = new ListContactWayRequest();

        // 测试各种时间戳
        $timestamps = [
            1640995200, // 2022-01-01 00:00:00
            time(), // 当前时间
            time() - 86400, // 1天前
            time() + 86400, // 1天后
        ];

        foreach ($timestamps as $timestamp) {
            $request->setStartTime($timestamp);
            $this->assertSame($timestamp, $request->getStartTime());

            $request->setEndTime($timestamp);
            $this->assertSame($timestamp, $request->getEndTime());
        }
    }

    public function testCursorFormats(): void
    {
        // 测试游标格式
        $request = new ListContactWayRequest();
        $cursors = [
            'simple_cursor',
            'cursor_with_numbers_123',
            'cursor-with-dashes',
            'cursor_with_underscores',
            'UPPERCASE_CURSOR',
            'cursor.with.dots',
            base64_encode('encoded_cursor'),
        ];

        foreach ($cursors as $cursor) {
            $request->setCursor($cursor);
            $this->assertSame($cursor, $request->getCursor());

            $options = $request->getRequestOptions();
            $json = $options['json'] ?? [];
            $this->assertIsArray($json);
            $this->assertSame($cursor, $json['cursor'] ?? null);
        }
    }

    public function testMultipleSetOperations(): void
    {
        // 测试多次设置值
        $request = new ListContactWayRequest();

        $request->setLimit(50);
        $request->setLimit(200);
        $this->assertSame(200, $request->getLimit());

        $request->setCursor('first_cursor');
        $request->setCursor('second_cursor');
        $this->assertSame('second_cursor', $request->getCursor());

        $firstTime = time() - 86400;
        $secondTime = time() - 3600;
        $request->setStartTime($firstTime);
        $request->setStartTime($secondTime);
        $this->assertSame($secondTime, $request->getStartTime());
    }

    public function testIdempotentMethodCalls(): void
    {
        // 测试方法调用是幂等的
        $request = new ListContactWayRequest();
        $startTime = time() - 86400;
        $cursor = 'test_cursor';

        $request->setStartTime($startTime);
        $request->setCursor($cursor);
        $request->setLimit(50);

        // 多次调用应该返回相同结果
        $this->assertSame($startTime, $request->getStartTime());
        $this->assertSame($startTime, $request->getStartTime());

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
        $request = new ListContactWayRequest();
        $originalStartTime = time() - 86400;
        $originalCursor = 'original_cursor';

        $request->setStartTime($originalStartTime);
        $request->setCursor($originalCursor);
        $request->setLimit(100);

        $options1 = $request->getRequestOptions();
        $options2 = $request->getRequestOptions();

        // 修改返回的数组不应影响原始数据
        $this->assertNotNull($options1);
        $this->assertArrayHasKey('json', $options1);
        $this->assertIsArray($options1['json'] ?? null);
        $json1 = $options1['json'] ?? [];
        $json1['start_time'] = time();
        $json1['cursor'] = 'modified_cursor';
        $json1['limit'] = 500;
        $json1['new_field'] = 'new_value';

        $this->assertSame($originalStartTime, $request->getStartTime());
        $this->assertSame($originalCursor, $request->getCursor());
        $this->assertSame(100, $request->getLimit());

        $this->assertNotNull($options2);
        $this->assertArrayHasKey('json', $options2);
        $this->assertIsArray($options2['json'] ?? null);
        $json2 = $options2['json'] ?? [];
        $this->assertSame($originalStartTime, $json2['start_time'] ?? null);
        $this->assertSame($originalCursor, $json2['cursor'] ?? null);
        $this->assertSame(100, $json2['limit'] ?? null);
        $this->assertArrayNotHasKey('new_field', $json2);
    }

    public function testEmptyCursor(): void
    {
        // 测试空游标
        $request = new ListContactWayRequest();
        $request->setCursor('');

        $this->assertSame('', $request->getCursor());

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json'] ?? null);
        $json = $options['json'] ?? [];
        $this->assertSame('', $json['cursor'] ?? null);
    }

    public function testApiEndpointCorrectness(): void
    {
        // 测试API端点正确性
        $request = new ListContactWayRequest();
        $path = $request->getRequestPath();

        $this->assertStringContainsString('externalcontact', $path);
        $this->assertStringContainsString('list_contact_way', $path);
        $this->assertStringStartsWith('/', $path);
        $this->assertStringEndsWith('/list_contact_way', $path);
    }

    public function testJsonRequestFormat(): void
    {
        // 测试JSON请求格式
        $request = new ListContactWayRequest();
        $request->setLimit(50);
        $request->setCursor('test_cursor');

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
        $request = new ListContactWayRequest();
        $startTime = time() - 86400;
        $endTime = time();
        $cursor = 'integrity_cursor';
        $limit = 200;

        $request->setStartTime($startTime);
        $request->setEndTime($endTime);
        $request->setCursor($cursor);
        $request->setLimit($limit);

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);

        // 验证请求数据结构完整性
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertSame($startTime, $json['start_time'] ?? null);
        $this->assertSame($endTime, $json['end_time'] ?? null);
        $this->assertSame($cursor, $json['cursor'] ?? null);
        $this->assertSame($limit, $json['limit'] ?? null);

        // 验证只包含设置的字段
        $this->assertCount(1, $options);
        $this->assertCount(4, $json);
    }

    public function testNullFieldsNotIncluded(): void
    {
        // 测试null字段不包含在请求中
        $request = new ListContactWayRequest();
        $request->setLimit(100); // 只设置limit
        // 其他字段保持null

        $options = $request->getRequestOptions();

        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('limit', $json);
        $this->assertArrayNotHasKey('start_time', $json);
        $this->assertArrayNotHasKey('end_time', $json);
        $this->assertArrayNotHasKey('cursor', $json);
        $this->assertCount(1, $json);
    }

    public function testTimeRangeLogic(): void
    {
        // 测试时间范围逻辑
        $request = new ListContactWayRequest();
        $startTime = time() - 7 * 24 * 3600; // 7天前
        $endTime = time(); // 现在

        $request->setStartTime($startTime);
        $request->setEndTime($endTime);

        // 验证开始时间小于结束时间
        $this->assertLessThan($request->getEndTime(), $request->getStartTime());

        $options = $request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'] ?? [];
        $this->assertIsArray($json);
        $this->assertLessThan($json['end_time'] ?? 0, $json['start_time'] ?? 0);
    }
}
