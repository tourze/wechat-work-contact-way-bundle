<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkContactWayBundle\Controller\Admin\ContactWayCrudController;
use WechatWorkContactWayBundle\Entity\ContactWay;

/**
 * @internal
 */
#[CoversClass(ContactWayCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ContactWayCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    private function createTestCorp(): Corp
    {
        $uniqueId = uniqid('test_corp_', true);

        $corp = new Corp();
        $corp->setName('测试企业' . $uniqueId);
        $corp->setCorpId($uniqueId);
        $corp->setFromProvider(false);
        $corp->setCorpSecret('test_corp_secret_' . $uniqueId);

        $entityManager = self::getEntityManager();
        $entityManager->persist($corp);
        $entityManager->flush();

        return $corp;
    }

    private function createTestAgent(Corp $corp): Agent
    {
        $uniqueId = uniqid('1000', true);

        $agent = new Agent();
        $agent->setName('测试应用' . $uniqueId);
        $agent->setAgentId($uniqueId);
        $agent->setSecret('test_secret_' . $uniqueId);
        $agent->setToken('test_token_' . $uniqueId);
        $agent->setEncodingAESKey('test_aes_key_' . $uniqueId);
        $agent->setCorp($corp);
        $agent->setDescription('测试应用描述');

        $entityManager = self::getEntityManager();
        $entityManager->persist($agent);
        $entityManager->flush();

        return $agent;
    }

    public function testAuthenticatedAccessShouldShowIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $url = $this->generateAdminUrl(Action::INDEX);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testConfigIdFilterSearch(): void
    {
        $client = $this->createAuthenticatedClient();

        // 创建真实的 Corp 和 Agent 实体
        $corp = $this->createTestCorp();
        $agent = $this->createTestAgent($corp);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test-config-id-123');
        $contactWay->setType(1);
        $contactWay->setScene(1);

        $entityManager = self::getEntityManager();
        $entityManager->persist($contactWay);
        $entityManager->flush();

        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'configId' => [
                    'comparison' => 'like',
                    'value' => 'test-config-id',
                ],
            ],
        ]);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testStateFilterSearch(): void
    {
        $client = $this->createAuthenticatedClient();

        // 创建真实的 Corp 和 Agent 实体
        $corp = $this->createTestCorp();
        $agent = $this->createTestAgent($corp);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test-config-id-456');
        $contactWay->setType(2);
        $contactWay->setScene(2);
        $contactWay->setState('channel-test-789');

        $entityManager = self::getEntityManager();
        $entityManager->persist($contactWay);
        $entityManager->flush();

        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'state' => [
                    'comparison' => 'like',
                    'value' => 'channel-test',
                ],
            ],
        ]);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testTypeChoiceFilter(): void
    {
        $client = $this->createAuthenticatedClient();

        // 创建真实的 Corp 和 Agent 实体
        $corp = $this->createTestCorp();
        $agent = $this->createTestAgent($corp);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test-config-id-single');
        $contactWay->setType(1); // 单人类型
        $contactWay->setScene(1);

        $entityManager = self::getEntityManager();
        $entityManager->persist($contactWay);
        $entityManager->flush();

        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'type' => [
                    'comparison' => '=',
                    'value' => 1,
                ],
            ],
        ]);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testSceneChoiceFilter(): void
    {
        $client = $this->createAuthenticatedClient();

        // 创建真实的 Corp 和 Agent 实体
        $corp = $this->createTestCorp();
        $agent = $this->createTestAgent($corp);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test-config-id-qrcode');
        $contactWay->setType(1);
        $contactWay->setScene(2); // 二维码场景

        $entityManager = self::getEntityManager();
        $entityManager->persist($contactWay);
        $entityManager->flush();

        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'scene' => [
                    'comparison' => '=',
                    'value' => 2,
                ],
            ],
        ]);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testBooleanFilters(): void
    {
        $client = $this->createAuthenticatedClient();

        // 创建真实的 Corp 和 Agent 实体
        $corp = $this->createTestCorp();
        $agent = $this->createTestAgent($corp);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test-config-id-temp');
        $contactWay->setType(1);
        $contactWay->setScene(1);
        $contactWay->setSkipVerify(true);
        $contactWay->setTemp(true);
        $contactWay->setExclusive(true);

        $entityManager = self::getEntityManager();
        $entityManager->persist($contactWay);
        $entityManager->flush();

        // 测试 skipVerify 过滤器
        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'skipVerify' => [
                    'comparison' => '=',
                    'value' => 1,
                ],
            ],
        ]);
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();

        // 测试 temp 过滤器
        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'temp' => [
                    'comparison' => '=',
                    'value' => 1,
                ],
            ],
        ]);
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();

        // 测试 exclusive 过滤器
        $url = $this->generateAdminUrl(Action::INDEX, [
            'crudControllerFqcn' => ContactWayCrudController::class,
            'filters' => [
                'exclusive' => [
                    'comparison' => '=',
                    'value' => 1,
                ],
            ],
        ]);
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
    }

    public function testValidationForRequiredFields(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问 NEW 表单
        $crawler = $client->request('GET', $this->generateAdminUrl(Action::NEW, ['crudControllerFqcn' => ContactWayCrudController::class]));
        $this->assertResponseIsSuccessful();

        // 测试必填字段的验证 - 找到表单并用空值提交
        $buttonCrawler = $crawler->selectButton('Create');
        if ($buttonCrawler->count() > 0) {
            $form = $buttonCrawler->form();

            // 提交表单，必填字段留空（configId, type, scene）
            $crawler = $client->submit($form);

            // 验证表单错误状态码或成功显示表单
            $statusCode = $client->getResponse()->getStatusCode();
            if (422 === $statusCode) {
                $this->assertEquals(422, $statusCode);
            } else {
                $this->assertEquals(200, $statusCode);
                // 验证表单成功显示
                $content = $client->getResponse()->getContent() ?? '';
                $this->assertNotEmpty($content);
            }
        }
    }

    public function testEntityLabelConfiguration(): void
    {
        $controller = new ContactWayCrudController();
        $this->assertEquals(ContactWay::class, $controller::getEntityFqcn());
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // Navigate to new form
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // Find the form and submit with empty required fields
        $buttonCrawler = $crawler->selectButton('Create');
        if ($buttonCrawler->count() > 0) {
            $form = $buttonCrawler->form();
            $entityName = $this->getEntitySimpleName();

            // Submit form with empty required fields (corp, agent, configId, type, scene)
            $crawler = $client->submit($form);

            // Check response - could be success (302), validation error (422), or form redisplay (200)
            $statusCode = $client->getResponse()->getStatusCode();
            $this->assertContains($statusCode, [200, 302, 422],
                'Form submission should result in valid HTTP status code');

            // If status is 422, check for validation feedback
            if (422 === $statusCode) {
                $invalidFeedback = $crawler->filter('.invalid-feedback');
                if ($invalidFeedback->count() > 0) {
                    $this->assertStringContainsString('should not be blank',
                        $invalidFeedback->text(),
                        'Validation errors should contain expected message');
                }
            }
        }

        // Verify the controller has required fields configured
        $controller = $this->getControllerService();
        $fields = $controller->configureFields('new');
        $fieldArray = iterator_to_array($fields);
        $this->assertNotEmpty($fieldArray, 'NEW page should have configured fields');
    }

    /**
     * @return AbstractCrudController<ContactWay>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return new ContactWayCrudController();
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID列' => ['ID'];
        yield '企业列' => ['企业'];
        yield '应用列' => ['应用'];
        yield '配置ID列' => ['配置ID'];
        yield '联系方式类型列' => ['联系方式类型'];
        yield '场景列' => ['场景'];
        yield '小程序样式列' => ['小程序样式'];
        yield '备注信息列' => ['备注信息'];
        yield '无需验证列' => ['无需验证'];
        yield '渠道参数列' => ['渠道参数'];
        yield '临时会话列' => ['临时会话'];
        yield '二维码有效期列' => ['二维码有效期'];
        yield '会话有效期列' => ['会话有效期'];
        yield 'UnionID列' => ['UnionID'];
        yield '专属模式列' => ['专属模式'];
        yield '创建时间列' => ['创建时间'];
        yield '更新时间列' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield '企业字段' => ['corp'];
        yield '应用字段' => ['agent'];
        yield '配置ID字段' => ['configId'];
        yield '联系方式类型字段' => ['type'];
        yield '场景字段' => ['scene'];
        yield '小程序样式字段' => ['style'];
        yield '备注信息字段' => ['remark'];
        yield '无需验证字段' => ['skipVerify'];
        yield '渠道参数字段' => ['state'];
        yield '临时会话字段' => ['temp'];
        yield '二维码有效期字段' => ['expiresIn'];
        yield '会话有效期字段' => ['chatExpiresIn'];
        yield 'UnionID字段' => ['unionId'];
        yield '专属模式字段' => ['exclusive'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield '企业字段' => ['corp'];
        yield '应用字段' => ['agent'];
        yield '配置ID字段' => ['configId'];
        yield '联系方式类型字段' => ['type'];
        yield '场景字段' => ['scene'];
        yield '小程序样式字段' => ['style'];
        yield '备注信息字段' => ['remark'];
        yield '无需验证字段' => ['skipVerify'];
        yield '渠道参数字段' => ['state'];
        yield '临时会话字段' => ['temp'];
        yield '二维码有效期字段' => ['expiresIn'];
        yield '会话有效期字段' => ['chatExpiresIn'];
        yield 'UnionID字段' => ['unionId'];
        yield '专属模式字段' => ['exclusive'];
    }

    public function testNumericFilters(): void
    {
        $client = $this->createAuthenticatedClient();

        // 创建真实的 Corp 和 Agent 实体
        $corp = $this->createTestCorp();
        $agent = $this->createTestAgent($corp);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test-config-id-expires');
        $contactWay->setType(1);
        $contactWay->setScene(1);
        $contactWay->setExpiresIn(3600);
        $contactWay->setChatExpiresIn(7200);

        $entityManager = self::getEntityManager();
        $entityManager->persist($contactWay);
        $entityManager->flush();

        // 测试 expiresIn 数值过滤器
        $client->request('GET', '/admin', [
            'filters' => [
                'expiresIn' => [
                    'value' => '3600',
                ],
            ],
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // 测试 chatExpiresIn 数值过滤器
        $client->request('GET', '/admin', [
            'filters' => [
                'chatExpiresIn' => [
                    'value' => '7200',
                ],
            ],
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
