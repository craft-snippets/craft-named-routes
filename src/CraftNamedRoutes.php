<?php
/**
 * Craft named routes plugin for Craft CMS 3.x
 *
 * Craft named routes
 *
 * @link      http://craftsnippets.com/
 * @copyright Copyright (c) 2020 Piotr Pogorzelski
 */

namespace craftsnippets\craftnamedroutes;

use craftsnippets\craftnamedroutes\services\CraftNamedRoutesService as CraftNamedRoutesServiceService;
use craftsnippets\craftnamedroutes\variables\CraftNamedRoutesVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class CraftNamedRoutes
 *
 * @author    Piotr Pogorzelski
 * @package   CraftNamedRoutes
 * @since     1.0.0
 *
 * @property  CraftNamedRoutesServiceService $craftNamedRoutesService
 */
class CraftNamedRoutes extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CraftNamedRoutes
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string  $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSettings = false;

    /**
     * @var bool
     */
    public bool $hasCpSection = false;

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
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('namedRoutes', CraftNamedRoutesVariable::class);
                $variable->set('routes', CraftNamedRoutesVariable::class);
            }
        );

    }

}
