<?php
/**
 * Craft named routes plugin for Craft CMS 3.x
 *
 * Craft named routes
 *
 * @link      http://craftsnippets.com/
 * @copyright Copyright (c) 2020 Piotr Pogorzelski
 */

namespace craftsnippets\craftnamedroutes\services;

use craftsnippets\craftnamedroutes\CraftNamedRoutes;

use Craft;
use craft\base\Component;

use craft\helpers\UrlHelper;
use Twig\Error\RuntimeError;

/**
 * @author    Piotr Pogorzelski
 * @package   CraftNamedRoutes
 * @since     1.0.0
 */
class CraftNamedRoutesService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public static function returnRouteUrl(string $route_name, $provided_tokens, $check_pattern = true)
    {

        $keyword = 'name';

        // find route by name
        $routes = Craft::$app->getRoutes()->getConfigFileRoutes();
        $selected_route = null;
        foreach ($routes as $single_route => $route_settings) {
            if(isset($route_settings[$keyword]) && $route_settings[$keyword] == $route_name){
                $selected_route = $single_route;
            }
        }

        if (is_null($selected_route = null)) {
            $manager = Craft::$app->getUrlManager();
            $rules = Craft::$app->getUrlManager()->rules;
            foreach ($rules as $rule) {
                if(isset($rule->$keyword) && $rule->$keyword == $route_name){
                    $selected_route = $rule->createUrl($manager, $rule->route, $provided_tokens);
                }
            }
        }

        // exception if no route with provided name was found
        if(is_null($selected_route)){
            throw new RuntimeError(sprintf('Route "%s" was not found.', $route_name));
        }

        
        if(!is_null($provided_tokens) && is_array($provided_tokens)){
        // if tokens provided
            $selected_route_tokens = explode('/', $selected_route);
            $result_segments = [];
            //for each token in selected route
            foreach ($selected_route_tokens as $route_token) {
                if(preg_match('/\<(\w+)\:?(.*)\>/', $route_token, $matches)){
                    $token_name = $matches[1];

                    // check if token exists with provided function param
                    if(!array_key_exists($token_name, $provided_tokens)){
                        throw new RuntimeError(sprintf('Route "%s" - token with name "%s" does not have value provided.', $route_name, $token_name));
                    }

                    // if token has regexp provided, check if token value validates
                    if($check_pattern && !empty($matches[2]) && !preg_match('/^'.$matches[2].'$/', $provided_tokens[$token_name])){
                        throw new RuntimeError(sprintf('Route "%s" - token with name "%s" does not match "%s" rule.', $route_name, $token_name, $matches[2]));
                    }

                    $result_segments[] = $provided_tokens[$token_name];
                }else{
                    $result_segments[] = $route_token;
                }
            }
            $result_url = implode('/', $result_segments);
        }else{
            $result_url = $selected_route;
        }

        
        $result_url = UrlHelper::url($result_url);
        return $result_url;

    }

}
