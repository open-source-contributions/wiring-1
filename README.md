# Wiring

An PHP Microframework with Interoperability (PSRs).

## Installation / Usage

1. Download and install Composer by following the [official instructions](https://getcomposer.org/download/).
2. Create a composer.json defining wiring dependencie.

    ``` json
    {
        "require": {
            "aracaw/wiring": "^1.0.0"
        }
    }
    ```

3. Run Composer: `php composer.phar install`
4. Check the last version on [Packagist](https://packagist.org/packages/aracaw/wiring).

## Quick start

Use Wiring [Skeleton](https://github.com/aracaw/wiring-skeleton.git):

1. Clone the repo:

    ```bash
    git clone https://github.com/aracaw/wiring-skeleton.git
    ```

2. Change to the directory created

    ```bash
    cd wiring-skeleton/
    ```

3. Download Composer

    Run this in your terminal to get the latest Composer version:
    
    ```bash
    curl -sS https://getcomposer.org/installer | php
    ```

    or if you don't have curl:

    ```bash
    php -r "readfile('https://getcomposer.org/installer');" | php
    ```

4. Composer Install

    ```bash
    php composer.phar install
    ```

5. Start PHP Built-in web server:

    ```bash
    php -S 127.0.0.1:8000 -t public/
    ```

Requirements
------------

PHP 5.5 or above (PHP 7 recommended to avoid potential bugs).

##Copyright and license

Code and documentation copyright (c) 2016, Code released under the BSD-3-Clause license.