# Named routes plugin for Craft CMS 3.x


## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require craftsnippets/craft-named-routes

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Craft named routes.

## Usage

This plugin allows you to get a URL of routes set in `config/routes.php` from within Twig templates - you will no longer need to hardcode routes structure in your markup.

In order to use the plugin, you will need to give your route an additional `name` attribute. Here is an example of a route that creates a user profile page:

```
'users/<user_id:\d+>' => ['template' => 'pages/user', 'name' => 'userRoute'],
```

As you can see, we gave route name `userRoute`. To get URL of route in Twig template, you need to use `craft.namedRoutes.getUrl` function. Pass it route name and array or route tokens:

```
{{craft.namedRoutes.getUrl('userRoute', {
	user_id: 11,
}) }}
```

This will output URL looking like "http://website.com/users/123".

Here are the parameters received by `craft.namedRoutes.getUrl` function:

* route name
* array of route tokens
* optional parameter deciding if the token value should be checked by regexp rule, if such rule is provided within route token. Default: `true`.

## Regexp rules of tokens

The last parameter of the function requires a bit of explanation. If route token has regexp rule provided (like `<user_id:\d+>` - which would only accept digits), the plugin by default will throw an exception if the provided token value does not match this rule. This behavior can be overwritten by setting the third parameter of the function to `false`. 

One of the use cases for this can be providing URL for [redirectInput](https://docs.craftcms.com/v3/dev/functions.html#redirectinput) when [adding entry from frontend](https://docs.craftcms.com/v3/dev/examples/entry-form.html), and our entry uses custom route. Poperties of the new entry can be used in `redirectInput` - for example, this URL will have `{id}` replaced with ID of added entry:

```
{{redirectInput('http://website.com/some-entry/{id}')}}
```

So, if our route looks like this:

```
'some-entry/<entry_id:\d+>' => ['template' => 'pages/entry', 'name' => 'entryRoute'],
```

Passing `{id}` as `entry_id` token value will cause an error, because string `{id}` does not matches `\d+` rule which accepts only digits. That's why you need to set the third parameter of a function to `false` in such a situation.

```
{{redirectInput(craft.namedRoutes.getUrl('entryRoute', {
	entry_id: '{id}',
}, false) )}}
```

## Craft CMS routing documentation

More information about routes can be found in Craft [documentation](https://docs.craftcms.com/v3/routing.html#advanced-routing-with-url-rules).

-----------------

Plugin icon made by [Freepik](https://www.flaticon.com/authors/freepik) from [www.flaticon.com](https://www.flaticon.com/).

-----------------

Brought to you by [Piotr Pogorzelski](http://craftsnippets.com/)
