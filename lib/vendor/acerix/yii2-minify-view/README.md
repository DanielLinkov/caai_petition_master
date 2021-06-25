Yii 2 Minify View Component
===========================

Concatenates AssetBundle files of the same type and minifies CSS, JavaScript, and page HTML.

[![License](https://poser.pugx.org/acerix/yii2-minify-view/license.svg)](https://packagist.org/packages/acerix/yii2-minify-view)
[![Latest Stable Version](https://poser.pugx.org/acerix/yii2-minify-view/v/stable.svg)](https://packagist.org/packages/acerix/yii2-minify-view)
[![Latest Unstable Version](https://poser.pugx.org/acerix/yii2-minify-view/v/unstable.svg)](https://packagist.org/packages/acerix/yii2-minify-view)
[![Total Downloads](https://poser.pugx.org/acerix/yii2-minify-view/downloads.svg)](https://packagist.org/packages/acerix/yii2-minify-view)

Code Status
-----------
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/acerix/yii2-minify-view/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/acerix/yii2-minify-view/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/acerix/yii2-minify-view/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/acerix/yii2-minify-view/?branch=master)
[![Travis CI Build Status](https://travis-ci.org/acerix/yii2-minify-view.svg?branch=master)](https://travis-ci.org/acerix/yii2-minify-view)

Support
-------
[Issues on GitHub](https://github.com/acerix/yii2-minify-view/issues)

Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/).

Either run

```bash
composer require acerix/yii2-minify-view
```

or add

```
"acerix/yii2-minify-view": "~2.0",
```

to the `require` section of your `composer.json` file.

Configure
---------
```php
<?php

return [
    // ...
        'components' => [
        // ...
        'view' => [
            'class' => '\acerix\yii\minify\View',
            'enableMinify' => !YII_DEBUG,
            'concatCss' => true,
            'minifyCss' => true,
            'concatJs' => true,
            'minifyJs' => true,
            'minifyOutput' => true, // minify html page
            'webPath' => '@web', // path alias to web base
            'basePath' => '@webroot', // path alias to web base
            'minifyPath' => '@webroot/minify', // path alias to save minify result
            'jsPosition' => [ \yii\web\View::POS_END ], // positions of js files to be minified
            'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
            'expandImports' => true, // whether to change @import on content
            'compressOptions' => ['jsCleanComments' => false], // options for html compress
            'excludeFiles' => [
                'jquery.js', // exclude this file from minification
                'app-[^.].js', // regexp
            ],
            'excludeBundles' => [
            	\app\helloworld\AssetBundle::class, // exclude this bundle from minification
            ],
        ]
    ]
];
```

Forked From
-----------
[rmrevin/yii2-minify-view](https://github.com/rmrevin/yii2-minify-view)
