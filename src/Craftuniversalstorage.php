<?php
/**
 * craft-universal-storage plugin for Craft CMS 3.x
 *
 * Universal S3 object storage for CraftCMS
 *
 * @link      https://industryarena.com/
 * @copyright Copyright (c) 2021 IndustryArena
 */

namespace industryarena\craftuniversalstorage;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Volumes;

/**
 * Class Craftuniversalstorage
 *
 * @author    IndustryArena
 * @package   Craftuniversalstorage
 * @since     1.0.3
 *
 */
class Craftuniversalstorage extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Craftuniversalstorage
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.3';

    /**
     * @var bool
     */
    public $hasCpSettings = false;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'craft-universal-storage',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );


        Event::on(Volumes::class, Volumes::EVENT_REGISTER_VOLUME_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Volume::class;
        });
    }

    // Protected Methods
    // =========================================================================
}
