Compare System Versions
=======================

Check the versions of the packages and frameworks within project running in production.

This library look up on each project and scan for `composer.json` and `package.json` 
files to check if the versions of its installed  packages are outdated.

[screenshot]: ./screenshot.png "Screenshot"

![Screenshot][screenshot]

## Setup

Create a fresh composer project then install this project.

    mkdir /var/www/compare-system-versions
    composer init
    
    git init
    echo "/vendor/" > .gitignore
    git add .gitignore
    git commit -m "First Commit" 

## Install

Download the latest release `csv.phar`.

    curl -Lo csv https://github.com/adrianorsouza/compare-system-versions/releases/download/v0.1.0/csv.phar
    chmod +x csv
    sudo mv csv /usr/local/bin/

## Config

First Create a config YAML file `config.yaml`:

```yaml
system_versions:
    latest:
      php: 7.4.7
      nginx: 1.18.0
      mysql: 8.0.20
      platform: Ubuntu 20.04 LTS (Focal Fossa)

    current:
      php: 7.3.1
      nginx: 1.16.0
      mysql: 8.0.20
      platform: Ubuntu 18.04 LTS (Bionic Beaver)

package_versions:
    latest:
      phpunit: 9.2.0
      laravel: 7.18.0
      react: 16.13.1
      grunt: 1.2.1

iterator:
  dir: /var/www
  packages:
    - composer.lock
    - package-lock.json
    - bower.json
  exclude:
    - bower_components
    - node_modules
    - public
    - public_html
    - vendor
    - compare-system-versions

options:
  title: System Versions Compare
  build_dir: public/
```

## Usage

    php csv.phar update config.yaml

The code above will scan for projects within `/var/www` dir and get the versions for packages and versions like: 

- PHP
- Ubuntu
- Laravel Framework
- ReactJS Framework
- GruntJS Task Runner


## Author

**Adriano Rosa** (https://adrianorosa.com)  

## Licence

Copyright © 2020, Adriano Rosa  <info@adrianorosa.com>
All rights reserved.

For the full copyright and license information, please view the LICENSE 
file that was distributed within the source root of this project.
