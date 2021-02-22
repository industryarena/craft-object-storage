<?php

namespace IndustyArena\CraftObjectStorage;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Volumes;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // var_dump(123);
        // die;

        Event::on(Volumes::class, Volumes::EVENT_REGISTER_VOLUME_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Volume::class;
        });
    }
}
