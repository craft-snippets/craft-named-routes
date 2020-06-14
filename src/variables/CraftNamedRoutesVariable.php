<?php
/**
 * Craft named routes plugin for Craft CMS 3.x
 *
 * Craft named routes
 *
 * @link      http://craftsnippets.com/
 * @copyright Copyright (c) 2020 Piotr Pogorzelski
 */

namespace craftsnippets\craftnamedroutes\variables;

use craftsnippets\craftnamedroutes\CraftNamedRoutes;

use Craft;
use craftsnippets\craftnamedroutes\services\CraftNamedRoutesService as CraftNamedRoutesServiceService;

/**
 * @author    Piotr Pogorzelski
 * @package   CraftNamedRoutes
 * @since     1.0.0
 */
class CraftNamedRoutesVariable
{

    public function getUrl(string $route_name, array $provided_tokens, $check_pattern = true)
    {
        $service = new CraftNamedRoutesServiceService;
        return $service->returnRouteUrl($route_name, $provided_tokens, $check_pattern);
    }
}
