# GIT Panel for Tracy 

[![Build Status](https://img.shields.io/travis/nofutur3/tracy-gitpanel.svg?style=for-the-badge)](https://travis-ci.org/nofutur3/tracy-gitpanel)
[![Downloads this Month](https://img.shields.io/packagist/dm/nofutur3/tracy-gitpanel.svg?style=for-the-badge)](https://packagist.org/packages/nofutur3/tracy-gitpanel)
[![Latest stable](https://img.shields.io/packagist/v/nofutur3/tracy-gitpanel.svg?style=for-the-badge)](https://packagist.org/packages/nofutur3/tracy-gitpanel)
[![License](https://img.shields.io/github/license/nofutur3/tracy-gitpanel.svg?style=for-the-badge)](https://packagist.org/packages/nofutur3/tracy-gitpanel)

--- 

This is simple extension for [Tracy](https://tracy.nette.org/en/) which shows the information about current GIT branch.

![not versioned project](docs/not-versioned.png)

![versioned project](docs/screen.png)

## Installation

The recommended installation is using [composer](https://getcomposer.org/). 


```bash
composer req nofutur3/tracy-gitpanel
```

Alternative way - in case you are not able to use composer. Download the source code (ie clone git repo) into your project
and require it some way. For [nette framework](https://nette.org/en/) like this in your bootstrap file:

```php
$configurator
    ->createRobotLoader()
    ->addDirectory(__DIR__ . 'path/to/library/');
```

## Supported PHP versions

| PHP version | Supported till |
| --- | --- |
| 5.6 | 0.9.4 |
| 7.0 | 0.9.4 |
| 7.1 |  |
| 7.2 |  |
| 7.3 |  |
| 7.4 |  |

## Configuration

In your nette application it's simple, just add these lines to your config file. Depends on the structure of your config files,
but you may use the basic config.neon file. I usually add the lines just in config.local.neon file because I don't need this extension
in production mode.

##### Nette 2.3+

```neon
tracy:
    bar:
        - Nofutur3\GitPanel\Diagnostics\Panel
```

If you want to set up the protected branches (by default it uses just a master branch), just pass the array of branch names to the panel:

```neon
parameters: 
    protected-branches:
        - production
        - staging
tracy:
    bar:
        - Nofutur3\GitPanel\Diagnostics\Panel(%protected-branches%)
```

##### Older version of Nette:

```neon
nette:
    debugger:
        bar: 
            - Nofutur3\GitPanel\Diagnostics\Panel
```


#### Standalone Tracy

In case you are using Tracy without Nette, you can add GitPanel this way:

```php
\Tracy\Debugger::getBar()->addPanel(new \Nofutur3\GitPanel\Diagnostics\Panel());
```

Or with protected branches:

```php
\Tracy\Debugger::getBar()
    ->addPanel(new \Nofutur3\GitPanel\Diagnostics\Panel(['production','staging']));
```
