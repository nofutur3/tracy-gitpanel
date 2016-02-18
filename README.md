# tracy-gitpanel

[![Build Status](https://travis-ci.org/nofutur3/tracy-gitpanel.svg?branch=master)](https://travis-ci.org/nofutur3/tracy-gitpanel)
[![Downloads this Month](https://img.shields.io/packagist/dm/nofutur3/tracy-gitpanel.svg)](https://packagist.org/packages/nofutur3/tracy-gitpanel)
[![Latest stable](https://img.shields.io/packagist/v/nofutur3/tracy-gitpanel.svg)](https://packagist.org/packages/nofutur3/tracy-gitpanel)

git panel extension for nette framework

## installation

install library via composer

```
composer require nofutur3/tracy-gitpanel
```

register panel

```
nette:
    debugger:
        bar: 
            - Nofutur3\GitPanel\Diagnostics\Panel
```
