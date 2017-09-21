<?php declare(strict_types=1);

namespace Shopware\ShippingMethod\Writer\Resource;

use Shopware\Framework\Write\Field\FkField;
use Shopware\Framework\Write\Field\ReferenceField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\Resource;

class ShippingMethodCategoryResource extends Resource
{
    public function __construct()
    {
        parent::__construct('shipping_method_category');

        $this->fields['shippingMethod'] = new ReferenceField('shippingMethodUuid', 'uuid', \Shopware\ShippingMethod\Writer\Resource\ShippingMethodResource::class);
        $this->fields['shippingMethodUuid'] = (new FkField('shipping_method_uuid', \Shopware\ShippingMethod\Writer\Resource\ShippingMethodResource::class, 'uuid'))->setFlags(new Required());
        $this->fields['category'] = new ReferenceField('categoryUuid', 'uuid', \Shopware\Category\Writer\Resource\CategoryResource::class);
        $this->fields['categoryUuid'] = (new FkField('category_uuid', \Shopware\Category\Writer\Resource\CategoryResource::class, 'uuid'))->setFlags(new Required());
    }

    public function getWriteOrder(): array
    {
        return [
            \Shopware\ShippingMethod\Writer\Resource\ShippingMethodResource::class,
            \Shopware\Category\Writer\Resource\CategoryResource::class,
            \Shopware\ShippingMethod\Writer\Resource\ShippingMethodCategoryResource::class,
        ];
    }

    public static function createWrittenEvent(array $updates, array $errors = []): \Shopware\ShippingMethod\Event\ShippingMethodCategoryWrittenEvent
    {
        $event = new \Shopware\ShippingMethod\Event\ShippingMethodCategoryWrittenEvent($updates[self::class] ?? [], $errors);

        unset($updates[self::class]);

        if (!empty($updates[\Shopware\ShippingMethod\Writer\Resource\ShippingMethodResource::class])) {
            $event->addEvent(\Shopware\ShippingMethod\Writer\Resource\ShippingMethodResource::createWrittenEvent($updates));
        }

        if (!empty($updates[\Shopware\Category\Writer\Resource\CategoryResource::class])) {
            $event->addEvent(\Shopware\Category\Writer\Resource\CategoryResource::createWrittenEvent($updates));
        }

        if (!empty($updates[\Shopware\ShippingMethod\Writer\Resource\ShippingMethodCategoryResource::class])) {
            $event->addEvent(\Shopware\ShippingMethod\Writer\Resource\ShippingMethodCategoryResource::createWrittenEvent($updates));
        }

        return $event;
    }
}