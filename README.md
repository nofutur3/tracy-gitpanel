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

## Support

💲 Do you like this tool? Support me with few bucks to cover coffee expenses ;)

<table>
    <tr style="width: 100%">
        <td style="width: 25%">
            Hey! You can send me money on Revolut by following this link: <a href="https://revolut.me/jakub9sv">https://revolut.me/jakub9sv</a>
        </td>
        <td style="width: 25%">
            bitcoin<br>
            <img src="docs/btc.png" width="260px" />
            <span style="word-wrap: anywhere">bc1q5ednnq59x70sv0zlk9w5p4fm92srlwljhr7yk6</span>
        </td>
        <td style="width: 25%">
            lightning network<br>
            <img src="docs/ln.png" width="260px" />
            <span style="word-wrap: anywhere">lnurl1dp68gurn8ghj7ampd3kx2ar0veekzar0wd5xjtnrdakj7tnhv4kxctttdehhwm30d3h82unvwqhhwunev4jxjar0wgunw3gu8z2</span>
        </td>
        <td style="width: 25%">
            litecoin<br>
            <img src="docs/ltc.png" width="260px" />
            <span style="word-wrap: anywhere">ltc1q3e8g32u8ltgw5ycymw42feuvurgx89yayuxw9u</span>
        </td>
    </tr>
</table>

