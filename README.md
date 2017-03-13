SEO tags module
===============
Adds a description, keywords, etc. tags to site page by url

> ATTENTION 
>
> Module is in alfa-version, some classes are not exists! Do not use as is!

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist andrew72ru/yii2-seo-tags "*"
```

or add

```
"andrew72ru/yii2-seo-tags": "*"
```

to the require section of your `composer.json` file.

**!!! will not work, moodule is not published in packagist**

Settings
--------

In you application config:

```php
…
'modules' => [
    'seotag' => [
        'class' => 'andrew72ru\seotag\Module',
        'urlManager' => 'yii\web\UrlManager'
    ]
]
```

`UrlManager` uses if application use backend / frontend parts, and pages with tags are in frontend (for example), and module are in backend. In this case, use next settings for you application:

```php
…
'components' => [
        'urlManagerFrontend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => 'http://you.app.frontend.url',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlManagerBackend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => 'http://you.app.backend.url',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
]
```

Usage
-----

In url `you.site/seotag` you may create the meta-tag for any page on you site. Just type in create form relative `url` of page, description, images urls and keywords and save it.

In head of you layout file (e.g. `ffrontend/views/layouts/main.php`):

```php
use andrew72ru\seotag\widgets\metaTags;

metaTags::widget();
```

This widget renders a meta-tags:

* description;
* keywords;
* og:description (same as description);
* og:locale (ru_RU)
* og:site_name (`Yii::$app->name`);
* og:url (canonical page url);
* og:type (website);
* og:image;
* twitter:card;
* twitter:image;
* twitter:site (NOT READY);
* twitter:title (page title);
* twitter:description (same as description)
