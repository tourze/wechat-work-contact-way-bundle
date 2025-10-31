<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatWorkContactWayBundle\Entity\ContactWay;

/**
 * 企业微信联系我方式管理
 *
 * @extends AbstractCrudController<ContactWay>
 */
#[AdminCrud(routePath: '/wechat-work/contact-way', routeName: 'wechat_work_contact_way')]
final class ContactWayCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactWay::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('联系我方式')
            ->setEntityLabelInPlural('联系我方式列表')
            ->setPageTitle('index', '联系我方式列表')
            ->setPageTitle('new', '创建联系我方式')
            ->setPageTitle('edit', '编辑联系我方式')
            ->setPageTitle('detail', '联系我方式详情')
            ->setHelp('index', '管理企业微信「联系我」方式配置')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'configId', 'state', 'remark', 'unionId'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // 基本字段 - 在所有页面显示
        yield IdField::new('id', 'ID')
            ->setMaxLength(9999)
            ->hideOnForm()
        ;

        yield AssociationField::new('corp', '企业')
            ->setRequired(true)
            ->autocomplete()
        ;

        yield AssociationField::new('agent', '应用')
            ->setRequired(true)
            ->autocomplete()
        ;

        yield TextField::new('configId', '配置ID')
            ->setRequired(true)
            ->setHelp('微信「联系我」配置的唯一标识')
        ;

        yield ChoiceField::new('type', '联系方式类型')
            ->setRequired(true)
            ->setChoices([
                '单人' => 1,
                '多人' => 2,
            ])
            ->setHelp('1-单人, 2-多人')
        ;

        yield ChoiceField::new('scene', '场景')
            ->setRequired(true)
            ->setChoices([
                '在小程序中联系' => 1,
                '通过二维码联系' => 2,
            ])
            ->setHelp('1-在小程序中联系, 2-通过二维码联系')
        ;

        yield ChoiceField::new('style', '小程序样式')
            ->setChoices([
                '样式一' => 1,
                '样式二' => 2,
            ])
            ->setHelp('小程序控件样式，1或2')
        ;

        yield TextField::new('remark', '备注信息')
            ->setMaxLength(30)
            ->setHelp('最多30个字符')
        ;

        yield BooleanField::new('skipVerify', '无需验证')
            ->setHelp('添加时是否无需验证')
        ;

        yield TextField::new('state', '渠道参数')
            ->setMaxLength(30)
            ->setHelp('自定义的状态值，用于区分不同渠道')
        ;

        yield BooleanField::new('temp', '临时会话')
            ->setHelp('是否临时会话模式')
        ;

        yield IntegerField::new('expiresIn', '二维码有效期')
            ->setHelp('临时会话二维码有效期，单位秒')
        ;

        yield IntegerField::new('chatExpiresIn', '会话有效期')
            ->setHelp('临时会话有效期，单位秒')
        ;

        yield TextField::new('unionId', 'UnionID')
            ->setMaxLength(128)
            ->setHelp('临时会话指定的unionid')
        ;

        yield BooleanField::new('exclusive', '专属模式')
            ->setHelp('是否开启同一外部企业客户只能添加同一个员工')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->hideOnForm()
        ;

        // 数组字段 - 使用ArrayField避免字符串转换错误
        yield ArrayField::new('user', '使用用户')
            ->setHelp('可以使用该联系方式的成员UserID列表')
            ->onlyOnDetail()
        ;

        yield ArrayField::new('party', '使用部门')
            ->setHelp('可以使用该联系方式的部门ID列表')
            ->onlyOnDetail()
        ;

        yield ArrayField::new('conclusions', '结束语')
            ->setHelp('可选择的结束语，会话结束时自动发送')
            ->onlyOnDetail()
        ;

        // 其他详情字段 - 仅在详情页显示
        if (Crud::PAGE_DETAIL === $pageName) {
            yield TextField::new('qrCode', '二维码链接')
                ->setMaxLength(255)
                ->setHelp('生成的二维码图片链接')
            ;

            yield TextField::new('createdFromIp', '创建IP');

            yield TextField::new('updatedFromIp', '更新IP');

            yield TextField::new('createdBy', '创建者');

            yield TextField::new('updatedBy', '更新者');
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('corp', '企业'))
            ->add(EntityFilter::new('agent', '应用'))
            ->add(TextFilter::new('configId', '配置ID'))
            ->add(ChoiceFilter::new('type', '联系方式类型')->setChoices([
                '单人' => 1,
                '多人' => 2,
            ]))
            ->add(ChoiceFilter::new('scene', '场景')->setChoices([
                '在小程序中联系' => 1,
                '通过二维码联系' => 2,
            ]))
            ->add(ChoiceFilter::new('style', '小程序样式')->setChoices([
                '样式一' => 1,
                '样式二' => 2,
            ]))
            ->add(TextFilter::new('remark', '备注信息'))
            ->add(BooleanFilter::new('skipVerify', '无需验证'))
            ->add(TextFilter::new('state', '渠道参数'))
            ->add(BooleanFilter::new('temp', '临时会话'))
            ->add(NumericFilter::new('expiresIn', '二维码有效期'))
            ->add(NumericFilter::new('chatExpiresIn', '会话有效期'))
            ->add(TextFilter::new('unionId', 'UnionID'))
            ->add(BooleanFilter::new('exclusive', '专属模式'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
