<?php

namespace WechatWorkContactWayBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatWorkContactWayBundle\Entity\ContactWay;

/**
 * ContactWay 实体测试用例
 *
 * 测试客户联系「联系我」实体的所有功能
 *
 * @internal
 */
#[CoversClass(ContactWay::class)]
final class ContactWayTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ContactWay();
    }

    /**
     * 提供属性及其样本值的 Data Provider
     *
     * @return iterable<string, array{0: string, 1: mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'configId' => ['configId', 'test_config_id'];
        yield 'type' => ['type', 1];
        yield 'scene' => ['scene', 1];
        yield 'style' => ['style', 1];
        yield 'remark' => ['remark', 'test remark'];
        yield 'skipVerify' => ['skipVerify', true];
        yield 'state' => ['state', 'test_state'];
        yield 'user' => ['user', ['user1', 'user2']];
        yield 'party' => ['party', ['1', '2']];
        yield 'temp' => ['temp', false];
        yield 'expiresIn' => ['expiresIn', 3600];
        yield 'chatExpiresIn' => ['chatExpiresIn', 1800];
        yield 'unionId' => ['unionId', 'test_union_id'];
        yield 'exclusive' => ['exclusive', false];
        yield 'conclusions' => ['conclusions', ['text' => 'test conclusion']];
        yield 'qrCode' => ['qrCode', 'https://test.com/qrcode.jpg'];
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $contactWay = new ContactWay();

        $this->assertNull($contactWay->getId());
        $this->assertNull($contactWay->getCorp());
        $this->assertNull($contactWay->getAgent());
        $this->assertNull($contactWay->getConfigId());
        $this->assertNull($contactWay->getType());
        $this->assertNull($contactWay->getScene());
        $this->assertNull($contactWay->getStyle());
        $this->assertNull($contactWay->getRemark());
        $this->assertTrue($contactWay->isSkipVerify());
        $this->assertNull($contactWay->getState());
        $this->assertNull($contactWay->getUser());
        $this->assertNull($contactWay->getParty());
        $this->assertFalse($contactWay->isTemp());
        $this->assertNull($contactWay->getExpiresIn());
        $this->assertNull($contactWay->getChatExpiresIn());
        $this->assertNull($contactWay->getUnionId());
        $this->assertFalse($contactWay->isExclusive());
        $this->assertNull($contactWay->getConclusions());
        $this->assertNull($contactWay->getQrCode());
        $this->assertNull($contactWay->getCreatedFromIp());
        $this->assertNull($contactWay->getUpdatedFromIp());
        $this->assertNull($contactWay->getCreatedBy());
        $this->assertNull($contactWay->getUpdatedBy());
        $this->assertNull($contactWay->getCreateTime());
        $this->assertNull($contactWay->getUpdateTime());
    }

    public function testImplementsCorrectInterfaces(): void
    {
        $contactWay = new ContactWay();
        $this->assertInstanceOf(PlainArrayInterface::class, $contactWay);
    }

    public function testSetConfigIdWithValidIdSetsIdCorrectly(): void
    {
        $configId = 'config_123456';
        $contactWay = new ContactWay();

        $contactWay->setConfigId($configId);

        $this->assertSame($configId, $contactWay->getConfigId());
    }

    public function testSetConfigIdWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setConfigId('old_config');

        $contactWay->setConfigId(null);

        $this->assertNull($contactWay->getConfigId());
    }

    public function testSetTypeWithValidTypeSetsTypeCorrectly(): void
    {
        $type = 1; // 单人联系方式
        $contactWay = new ContactWay();

        $contactWay->setType($type);

        $this->assertSame($type, $contactWay->getType());
    }

    public function testSetTypeWithMultiplePersonTypeSetsTypeCorrectly(): void
    {
        $type = 2; // 多人联系方式
        $contactWay = new ContactWay();

        $contactWay->setType($type);

        $this->assertSame($type, $contactWay->getType());
    }

    public function testSetSceneWithValidSceneSetsSceneCorrectly(): void
    {
        $scene = 1; // 在小程序中联系
        $contactWay = new ContactWay();

        $contactWay->setScene($scene);

        $this->assertSame($scene, $contactWay->getScene());
    }

    public function testSetSceneWithQrcodeSceneSetsSceneCorrectly(): void
    {
        $scene = 2; // 通过二维码联系
        $contactWay = new ContactWay();

        $contactWay->setScene($scene);

        $this->assertSame($scene, $contactWay->getScene());
    }

    public function testSetStyleWithValidStyleSetsStyleCorrectly(): void
    {
        $style = 1; // 小程序控件样式
        $contactWay = new ContactWay();

        $contactWay->setStyle($style);

        $this->assertSame($style, $contactWay->getStyle());
    }

    public function testSetStyleWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setStyle(1);

        $contactWay->setStyle(null);

        $this->assertNull($contactWay->getStyle());
    }

    public function testSetRemarkWithValidRemarkSetsRemarkCorrectly(): void
    {
        $remark = '这是测试备注';
        $contactWay = new ContactWay();

        $contactWay->setRemark($remark);

        $this->assertSame($remark, $contactWay->getRemark());
    }

    public function testSetRemarkWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setRemark('old remark');

        $contactWay->setRemark(null);

        $this->assertNull($contactWay->getRemark());
    }

    public function testSetSkipVerifyWithTrueSetsTrue(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setSkipVerify(true);

        $this->assertTrue($contactWay->isSkipVerify());
    }

    public function testSetSkipVerifyWithFalseSetsFalse(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setSkipVerify(false);

        $this->assertFalse($contactWay->isSkipVerify());
    }

    public function testSetSkipVerifyWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setSkipVerify(true);

        $contactWay->setSkipVerify(null);

        $this->assertNull($contactWay->isSkipVerify());
    }

    public function testSetStateWithValidStateSetsStateCorrectly(): void
    {
        $state = 'channel_123';
        $contactWay = new ContactWay();

        $contactWay->setState($state);

        $this->assertSame($state, $contactWay->getState());
    }

    public function testSetStateWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setState('old_state');

        $contactWay->setState(null);

        $this->assertNull($contactWay->getState());
    }

    public function testSetUserWithValidArraySetsUserCorrectly(): void
    {
        $user = ['user1', 'user2', 'user3'];
        $contactWay = new ContactWay();

        $contactWay->setUser($user);

        $this->assertSame($user, $contactWay->getUser());
    }

    public function testSetUserWithEmptyArraySetsEmptyArray(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setUser([]);

        $this->assertSame([], $contactWay->getUser());
    }

    public function testSetUserWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setUser(['user1']);

        $contactWay->setUser(null);

        $this->assertNull($contactWay->getUser());
    }

    public function testSetPartyWithValidArraySetsPartyCorrectly(): void
    {
        $party = ['1', '2', '3']; // 部门ID数组
        $contactWay = new ContactWay();

        $contactWay->setParty($party);

        $this->assertSame($party, $contactWay->getParty());
    }

    public function testSetPartyWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setParty(['1', '2']);

        $contactWay->setParty(null);

        $this->assertNull($contactWay->getParty());
    }

    public function testSetTempWithTrueSetsTrue(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setTemp(true);

        $this->assertTrue($contactWay->isTemp());
    }

    public function testSetTempWithFalseSetsFalse(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setTemp(false);

        $this->assertFalse($contactWay->isTemp());
    }

    public function testSetTempWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setTemp(true);

        $contactWay->setTemp(null);

        $this->assertNull($contactWay->isTemp());
    }

    public function testSetExpiresInWithValidSecondsSetsSecondsCorrectly(): void
    {
        $expiresIn = 86400; // 24小时
        $contactWay = new ContactWay();

        $contactWay->setExpiresIn($expiresIn);

        $this->assertSame($expiresIn, $contactWay->getExpiresIn());
    }

    public function testSetExpiresInWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setExpiresIn(3600);

        $contactWay->setExpiresIn(null);

        $this->assertNull($contactWay->getExpiresIn());
    }

    public function testSetChatExpiresInWithValidSecondsSetsSecondsCorrectly(): void
    {
        $chatExpiresIn = 3600; // 1小时
        $contactWay = new ContactWay();

        $contactWay->setChatExpiresIn($chatExpiresIn);

        $this->assertSame($chatExpiresIn, $contactWay->getChatExpiresIn());
    }

    public function testSetChatExpiresInWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setChatExpiresIn(1800);

        $contactWay->setChatExpiresIn(null);

        $this->assertNull($contactWay->getChatExpiresIn());
    }

    public function testSetUnionIdWithValidIdSetsIdCorrectly(): void
    {
        $unionId = 'union_123456789';
        $contactWay = new ContactWay();

        $contactWay->setUnionId($unionId);

        $this->assertSame($unionId, $contactWay->getUnionId());
    }

    public function testSetUnionIdWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setUnionId('old_union');

        $contactWay->setUnionId(null);

        $this->assertNull($contactWay->getUnionId());
    }

    public function testSetExclusiveWithTrueSetsTrue(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setExclusive(true);

        $this->assertTrue($contactWay->isExclusive());
    }

    public function testSetExclusiveWithFalseSetsFalse(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setExclusive(false);

        $this->assertFalse($contactWay->isExclusive());
    }

    public function testSetExclusiveWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setExclusive(true);

        $contactWay->setExclusive(null);

        $this->assertNull($contactWay->isExclusive());
    }

    public function testSetConclusionsWithValidArraySetConclusionsCorrectly(): void
    {
        $conclusions = [
            'text' => ['content' => '感谢您的咨询'],
            'image' => ['media_id' => 'media_123'],
        ];
        $contactWay = new ContactWay();

        $contactWay->setConclusions($conclusions);

        $this->assertSame($conclusions, $contactWay->getConclusions());
    }

    public function testSetConclusionsWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setConclusions(['text' => 'old']);

        $contactWay->setConclusions(null);

        $this->assertNull($contactWay->getConclusions());
    }

    public function testSetQrCodeWithValidUrlSetsUrlCorrectly(): void
    {
        $qrCode = 'https://example.com/qrcode.jpg';
        $contactWay = new ContactWay();

        $contactWay->setQrCode($qrCode);

        $this->assertSame($qrCode, $contactWay->getQrCode());
    }

    public function testSetQrCodeWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setQrCode('old_qrcode');

        $contactWay->setQrCode(null);

        $this->assertNull($contactWay->getQrCode());
    }

    public function testSetCreatedFromIpWithValidIpSetsIpCorrectly(): void
    {
        $ip = '192.168.1.1';
        $contactWay = new ContactWay();

        $contactWay->setCreatedFromIp($ip);

        $this->assertSame($ip, $contactWay->getCreatedFromIp());
    }

    public function testSetCreatedFromIpWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setCreatedFromIp('127.0.0.1');

        $contactWay->setCreatedFromIp(null);

        $this->assertNull($contactWay->getCreatedFromIp());
    }

    public function testSetUpdatedFromIpWithValidIpSetsIpCorrectly(): void
    {
        $ip = '10.0.0.1';
        $contactWay = new ContactWay();

        $contactWay->setUpdatedFromIp($ip);

        $this->assertSame($ip, $contactWay->getUpdatedFromIp());
    }

    public function testSetUpdatedFromIpWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setUpdatedFromIp('172.16.0.1');

        $contactWay->setUpdatedFromIp(null);

        $this->assertNull($contactWay->getUpdatedFromIp());
    }

    public function testSetCreatedByWithValidUserSetsUserCorrectly(): void
    {
        $createdBy = 'admin_user';
        $contactWay = new ContactWay();

        $contactWay->setCreatedBy($createdBy);

        $this->assertSame($createdBy, $contactWay->getCreatedBy());
    }

    public function testSetCreatedByWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setCreatedBy('old_user');

        $contactWay->setCreatedBy(null);

        $this->assertNull($contactWay->getCreatedBy());
    }

    public function testSetUpdatedByWithValidUserSetsUserCorrectly(): void
    {
        $updatedBy = 'updated_user';
        $contactWay = new ContactWay();

        $contactWay->setUpdatedBy($updatedBy);

        $this->assertSame($updatedBy, $contactWay->getUpdatedBy());
    }

    public function testSetUpdatedByWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setUpdatedBy('old_user');

        $contactWay->setUpdatedBy(null);

        $this->assertNull($contactWay->getUpdatedBy());
    }

    public function testSetCreateTimeWithValidDateTimeSetsTimeCorrectly(): void
    {
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $contactWay = new ContactWay();

        $contactWay->setCreateTime($createTime);

        $this->assertSame($createTime, $contactWay->getCreateTime());
    }

    public function testSetCreateTimeWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setCreateTime(new \DateTimeImmutable());

        $contactWay->setCreateTime(null);

        $this->assertNull($contactWay->getCreateTime());
    }

    public function testSetUpdateTimeWithValidDateTimeSetsTimeCorrectly(): void
    {
        $updateTime = new \DateTimeImmutable('2024-01-15 12:00:00');
        $contactWay = new ContactWay();

        $contactWay->setUpdateTime($updateTime);

        $this->assertSame($updateTime, $contactWay->getUpdateTime());
    }

    public function testSetUpdateTimeWithNullSetsNull(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setUpdateTime(new \DateTimeImmutable());

        $contactWay->setUpdateTime(null);

        $this->assertNull($contactWay->getUpdateTime());
    }

    /**
     * 测试 PlainArray 接口实现
     */
    public function testRetrievePlainArrayReturnsCorrectStructure(): void
    {
        $contactWay = new ContactWay();
        // 使用反射设置ID (因为ID是自动生成的)
        $reflection = new \ReflectionClass($contactWay);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($contactWay, '123456789');

        $contactWay->setConfigId('config_test');

        $result = $contactWay->retrievePlainArray();

        $expected = [
            'id' => '123456789',
            'configId' => 'config_test',
        ];

        $this->assertSame($expected, $result);
    }

    /**
     * 测试所有setter方法能正确设置值
     */
    public function testAllSettersSetValuesCorrectly(): void
    {
        $contactWay = new ContactWay();
        $createTime = new \DateTimeImmutable('2024-01-01');
        $updateTime = new \DateTimeImmutable('2024-01-15');

        // 分步设置各种值
        $contactWay->setConfigId('config_chain');
        $contactWay->setType(1);
        $contactWay->setScene(2);
        $contactWay->setStyle(1);
        $contactWay->setRemark('链式调用测试');
        $contactWay->setSkipVerify(false);
        $contactWay->setState('chain_state');
        $contactWay->setUser(['user1', 'user2']);
        $contactWay->setParty(['1', '2']);
        $contactWay->setTemp(true);
        $contactWay->setExpiresIn(3600);
        $contactWay->setChatExpiresIn(1800);
        $contactWay->setUnionId('union_chain');
        $contactWay->setExclusive(true);
        $contactWay->setConclusions(['text' => 'conclusion']);
        $contactWay->setQrCode('https://qr.example.com');
        $contactWay->setCreatedFromIp('192.168.1.1');
        $contactWay->setUpdatedFromIp('192.168.1.2');
        $contactWay->setCreatedBy('admin');
        $contactWay->setUpdatedBy('editor');
        $contactWay->setCreateTime($createTime);
        $contactWay->setUpdateTime($updateTime);

        // 验证所有值都设置正确
        $this->assertSame('config_chain', $contactWay->getConfigId());
        $this->assertSame(1, $contactWay->getType());
        $this->assertSame(2, $contactWay->getScene());
        $this->assertSame(1, $contactWay->getStyle());
        $this->assertSame('链式调用测试', $contactWay->getRemark());
        $this->assertFalse($contactWay->isSkipVerify());
        $this->assertSame('chain_state', $contactWay->getState());
        $this->assertSame(['user1', 'user2'], $contactWay->getUser());
        $this->assertSame(['1', '2'], $contactWay->getParty());
        $this->assertTrue($contactWay->isTemp());
        $this->assertSame(3600, $contactWay->getExpiresIn());
        $this->assertSame(1800, $contactWay->getChatExpiresIn());
        $this->assertSame('union_chain', $contactWay->getUnionId());
        $this->assertTrue($contactWay->isExclusive());
        $this->assertSame(['text' => 'conclusion'], $contactWay->getConclusions());
        $this->assertSame('https://qr.example.com', $contactWay->getQrCode());
        $this->assertSame('192.168.1.1', $contactWay->getCreatedFromIp());
        $this->assertSame('192.168.1.2', $contactWay->getUpdatedFromIp());
        $this->assertSame('admin', $contactWay->getCreatedBy());
        $this->assertSame('editor', $contactWay->getUpdatedBy());
        $this->assertSame($createTime, $contactWay->getCreateTime());
        $this->assertSame($updateTime, $contactWay->getUpdateTime());
    }

    /**
     * 测试边界场景
     */
    public function testEdgeCasesExtremeValues(): void
    {
        $contactWay = new ContactWay();
        // 测试极端整数值
        $contactWay->setType(PHP_INT_MAX);
        $this->assertSame(PHP_INT_MAX, $contactWay->getType());

        $contactWay->setScene(PHP_INT_MIN);
        $this->assertSame(PHP_INT_MIN, $contactWay->getScene());

        $contactWay->setExpiresIn(0);
        $this->assertSame(0, $contactWay->getExpiresIn());

        $contactWay->setChatExpiresIn(-1);
        $this->assertSame(-1, $contactWay->getChatExpiresIn());
    }

    public function testEdgeCasesLongStrings(): void
    {
        $contactWay = new ContactWay();
        $longString = str_repeat('x', 1000);

        $contactWay->setConfigId($longString);
        $contactWay->setRemark($longString);
        $contactWay->setState($longString);
        $contactWay->setUnionId($longString);
        $contactWay->setQrCode($longString);
        $contactWay->setCreatedFromIp($longString);
        $contactWay->setUpdatedFromIp($longString);
        $contactWay->setCreatedBy($longString);
        $contactWay->setUpdatedBy($longString);

        $this->assertSame($longString, $contactWay->getConfigId());
        $this->assertSame($longString, $contactWay->getRemark());
        $this->assertSame($longString, $contactWay->getState());
        $this->assertSame($longString, $contactWay->getUnionId());
        $this->assertSame($longString, $contactWay->getQrCode());
        $this->assertSame($longString, $contactWay->getCreatedFromIp());
        $this->assertSame($longString, $contactWay->getUpdatedFromIp());
        $this->assertSame($longString, $contactWay->getCreatedBy());
        $this->assertSame($longString, $contactWay->getUpdatedBy());
    }

    public function testEdgeCasesComplexArrayData(): void
    {
        $contactWay = new ContactWay();
        $complexUser = [
            'user1', 'user2', 'user3',
            'very_long_user_id_' . str_repeat('x', 100),
        ];

        $complexParty = ['1', '2', '3', '999999999', '-1', '0'];

        $complexConclusions = [
            'text' => [
                'content' => '这是一个非常长的结束语内容' . str_repeat('测试', 50),
            ],
            'image' => [
                'media_id' => 'media_' . str_repeat('a', 100),
            ],
            'link' => [
                'title' => '链接标题',
                'picurl' => 'https://example.com/very/long/path/to/image.jpg',
                'desc' => '链接描述',
                'url' => 'https://example.com/very/long/path/to/target/page.html',
            ],
        ];

        $contactWay->setUser($complexUser);
        $contactWay->setParty($complexParty);
        $contactWay->setConclusions($complexConclusions);

        $this->assertSame($complexUser, $contactWay->getUser());
        $this->assertSame($complexParty, $contactWay->getParty());
        $this->assertSame($complexConclusions, $contactWay->getConclusions());
    }

    public function testEdgeCasesDateTimeTypes(): void
    {
        $contactWay = new ContactWay();
        // 测试DateTime
        $dateTime = new \DateTimeImmutable('2024-01-15 12:30:45');
        $contactWay->setCreateTime($dateTime);
        $this->assertSame($dateTime, $contactWay->getCreateTime());

        // 测试DateTimeImmutable
        $dateTimeImmutable = new \DateTimeImmutable('2024-02-20 09:15:30');
        $contactWay->setUpdateTime($dateTimeImmutable);
        $this->assertSame($dateTimeImmutable, $contactWay->getUpdateTime());
    }
}
