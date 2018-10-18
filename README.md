Semantic UI
===========
Semantic UI extension for Yii2 framework.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist apache02/yii2-semantic-ui "*"

```

Add

```
"repositories":[
    {
        "type": "git",
        "url": "https://github.com/Apache02/yii2-semantic-ui.git"
    }
]
```

to your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \semantic\Menu::widget(['items'=>[...]]); ?>
```