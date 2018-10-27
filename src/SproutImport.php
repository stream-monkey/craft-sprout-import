<?php
/**
 * Sprout Import plugin for Craft CMS 3.x
 *
 * Import content and settings. Generate fake data.
 *
 * @link      http://barrelstrengthdesign.com
 * @copyright Copyright (c) 2017 Barrel Strength Design
 */

namespace barrelstrength\sproutimport;

use barrelstrength\sproutbase\base\BaseSproutTrait;
use barrelstrength\sproutbase\SproutBaseHelper;
use barrelstrength\sproutimport\models\Settings;
use barrelstrength\sproutbase\helpers\UninstallHelper;
use Craft;
use craft\base\Plugin;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use yii\base\Event;

/**
 * Class SproutImport
 *
 * @package barrelstrength\sproutimport
 */
class SproutImport extends Plugin
{
    use BaseSproutTrait;

    /**
     * Identify our plugin for BaseSproutTrait
     *
     * @var string
     */
    public static $pluginHandle = 'sprout-import';

    /**
     * @var bool
     */
    public $hasCpSection = true;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.1';

    /**
     * @var string
     */
    public $minVersionRequired = '0.6.3';

    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();

        Craft::setAlias('@sproutimport', $this->getBasePath());

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['sprout-import'] = ['template' => 'sprout-base-import/index'];
            $event->rules['sprout-import/index'] = ['template' => 'sprout-base-import/index'];
            $event->rules['sprout-import/weed'] = 'sprout/weed/weed-index';
            $event->rules['sprout-import/seed'] = 'sprout/seed/seed-index';
            $event->rules['sprout-import/bundles'] = ['template' => 'sprout-base-import/bundles'];
            $event->rules['sprout-import/settings'] = 'sprout/settings/edit-settings';
            $event->rules['sprout-import/settings/<settingsSectionHandle:.*>'] = 'sprout/settings/edit-settings';
        });
    }

    /**
     * @return Settings
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return array
     */
    public function getCpNavItem()
    {
        $parent = parent::getCpNavItem();

        // Allow user to override plugin name in sidebar
        if ($this->getSettings()->pluginNameOverride) {
            $parent['label'] = $this->getSettings()->pluginNameOverride;
        }

        return array_merge($parent, [
            'subnav' => [
                'index' => [
                    'label' => Craft::t('sprout-import', 'Import'),
                    'url' => 'sprout-import/index'
                ],
                'seed' => [
                    'label' => Craft::t('sprout-import', 'Seed'),
                    'url' => 'sprout-import/seed'
                ],
                'weed' => [
                    'label' => Craft::t('sprout-import', 'Weed'),
                    'url' => 'sprout-import/weed'
                ],
//                'bundles' => [
//                    'label' => Craft::t('sprout-import', 'Bundles'),
//                    'url' => 'sprout-import/bundles'
//                ],
                'settings' => [
                    'label' => Craft::t('sprout-import', 'Settings'),
                    'url' => 'sprout-import/settings/general'
                ]
            ]
        ]);
    }

    /**
     * Uninstall Sprout Import and related schema
     */
    public function uninstall()
    {
        $uninstallHelper = new UninstallHelper($this);
        $uninstallHelper->uninstall();
    }
}
