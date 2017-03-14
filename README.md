SEO tags module
===============
Adds a description, keywords, etc. tags to site page by url

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add to you `composer.json`

```json
{
  …
  "repositories": [
    {
      "type": "git",
      "url": "file:///Users/andrew/Sites/yii2-seo-tags"
    }
  ],
  …
}
```

Either run

```
php composer.phar require --prefer-dist andrew72ru/yii2-seo-tags "*"
```

or add

```
"andrew72ru/yii2-seo-tags": "*"
```

to the require section of your `composer.json` file.

Database migration class is `andrew72ru\seotag\commands\m170301_051438_seotag.php`. Add to you console application config

```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationNamespaces' => [
            'andrew72ru\seotag\commands',
        ],
        'migrationPath' => null,
    ],
]

```

Settings
--------

In you application config:

```php
…
'modules' => [
    'seotag' => [
        'class' => 'andrew72ru\seotag\Module',
        'urlManager' => '\yii\web\UrlManager',
        'baseUrl' => 'http://you.site.url',
        'twitterUsername' => '@you_twitter_username',
        'imagePath' => '@webroot/assets/share',
        'imageUrl' => '/share'
    ]
]
```

- `urlManager` creates a addresses for target pages and pictures.
- `baseUrl` need to create `url` to frontend from backend, for example
- `twitterUsername` uses in meta-tag `twitter:site`. For example, with module `twitterUsername` setting is `@you_twitter_username` meta-tag will be the `<meta property="twitter:site" content="@you_twitter_username">`
- `imagePath`, alias, where module will save a pictures for `og:image` meta-tag
- `imageUrl` – relative url to image directory. E.g. with `'imageUrl' => '/share'` and 'baseUrl' => 'http://you.site.url', url will be a `http://you.site.url/share/<model_id>/big.jpg`

TIP
---

Add `'controllerMap' => ['main' => '\your\own\MainController']` to module config and rewrite `MainController.php::actionPagesList` function to load pages data.

### Example function

```php
    public function actionPagesList($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];
        $pages = StaticPage::find()
            ->limit(50)->asArray();
        if(!is_null($q))
        {
            $pages->andwhere(['like', 'slug', $q]);
            $pages->orWhere(['like', 'title', $q]);

        }

        foreach ($pages->all() as $page)
        {
            $out[] = [
                'url' => $page['slug'],
                'name' => $page['title'],
                'value' => $page['title'] . ' (' . $page['slug'] . ')',
                'route' => Yii::$app->urlManager->createAbsoluteUrl([$page['slug']]),
            ];
        }

        $blog = Blog::find()
            ->limit(50)->asArray();

        if(!is_null($q))
        {
            $blog->andFilterWhere(['like', 'slug', $q]);
            $blog->orFilterWhere(['like', 'title', $q]);
        }

        foreach ($blog->all() as $blogItem)
        {
            $out[] = [
                'url' => '/blog/' . $blogItem['slug'],
                'name' => $blogItem['title'],
                'value' => $blogItem['title'] . ' (/blog/' . $blogItem['slug'] . ')',
                'route' => Yii::$app->urlManager->createAbsoluteUrl(['/blog/' . $blogItem['slug']]),
            ];
        }

        return $out;
    }

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
* link rel="canonical" with page address

Tests
-----

- clone this repository,
- `cd` to cloned folder,
- type `composer install`,
- build the tests with `vendor/bin/codecept build`
- run tests with `vendor/bin/codecept run`