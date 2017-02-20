# ZendSkeletonApplication

## Introduction

This is a skeleton application using the Zend Framework MVC layer and module
systems. This application is meant to be used as a starting place for those
looking to get their feet wet with Zend Framework.

## Installation using Composer

The easiest way to create a new Zend Framework project is to use
[Composer](https://getcomposer.org/).  If you don't have it already installed,
then please install as per the [documentation](https://getcomposer.org/doc/00-intro.md).

To create your new Zend Framework project:

```bash
$ composer create-project -sdev zendframework/skeleton-application path/to/install
```

Once installed, you can test it out immediately using PHP's built-in web server:

```bash
$ cd path/to/install
$ php -S 0.0.0.0:8080 -t public/ public/index.php
# OR use the composer alias:
$ composer serve
```

This will start the cli-server on port 8080, and bind it to all network
interfaces. You can then visit the site at http://localhost:8080/
- which will bring up Zend Framework welcome page.

**Note:** The built-in CLI server is *for development only*.

## Development mode

The skeleton ships with [zf-development-mode](https://github.com/zfcampus/zf-development-mode)
by default, and provides three aliases for consuming the script it ships with:

```bash
$ composer development-enable  # enable development mode
$ composer development-disable # disable development mode
$ composer development-status  # whether or not development mode is enabled
```

You may provide development-only modules and bootstrap-level configuration in
`config/development.config.php.dist`, and development-only application
configuration in `config/autoload/development.local.php.dist`. Enabling
development mode will copy these files to versions removing the `.dist` suffix,
while disabling development mode will remove those copies.

Development mode is automatically enabled as part of the skeleton installation process. 
After making changes to one of the above-mentioned `.dist` configuration files you will
either need to disable then enable development mode for the changes to take effect,
or manually make matching updates to the `.dist`-less copies of those files.

## Running Unit Tests

To run the supplied skeleton unit tests, you need to do one of the following:

- During initial project creation, select to install the MVC testing support.
- After initial project creation, install [zend-test](https://zendframework.github.io/zend-test/):

  ```bash
  $ composer require --dev zendframework/zend-test
  ```

Once testing support is present, you can run the tests using:

```bash
$ ./vendor/bin/phpunit
```

If you need to make local modifications for the PHPUnit test setup, copy
`phpunit.xml.dist` to `phpunit.xml` and edit the new file; the latter has
precedence over the former when running tests, and is ignored by version
control. (If you want to make the modifications permanent, edit the
`phpunit.xml.dist` file.)

## Using Vagrant

This skeleton includes a `Vagrantfile` based on ubuntu 16.04, and using the
ondrej/php PPA to provide PHP 7.0. Start it up using:

```bash
$ vagrant up
```

Once built, you can also run composer within the box. For example, the following
will install dependencies:

```bash
$ vagrant ssh -c 'composer install'
```

While this will update them:

```bash
$ vagrant ssh -c 'composer update'
```

While running, Vagrant maps your host port 8080 to port 80 on the virtual
machine; you can visit the site at http://localhost:8080/

> ### Vagrant and VirtualBox
>
> The vagrant image is based on ubuntu/xenial64. If you are using VirtualBox as
> a provider, you will need:
>
> - Vagrant 1.8.5 or later
> - VirtualBox 5.0.26 or later

For vagrant documentation, please refer to [vagrantup.com](https://www.vagrantup.com/)

## Using docker-compose

This skeleton provides a `docker-compose.yml` for use with
[docker-compose](https://docs.docker.com/compose/); it
uses the `Dockerfile` provided as its base. Build and start the image using:

```bash
$ docker-compose up -d --build
```

At this point, you can visit http://localhost:8080 to see the site running.

You can also run composer from the image. The container environment is named
"zf", so you will pass that value to `docker-compose run`:

```bash
$ docker-compose run zf composer install
```

## Web server setup

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

```apache
<VirtualHost *:80>
    ServerName zfapp.localhost
    DocumentRoot /path/to/zfapp/public
    <Directory /path/to/zfapp/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
        <IfModule mod_authz_core.c>
        Require all granted
        </IfModule>
    </Directory>
</VirtualHost>
```

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

```nginx
http {
    # ...
    include sites-enabled/*.conf;
}
```


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zfapp.localhost.conf`
it should look something like below:

```nginx
server {
    listen       80;
    server_name  zfapp.localhost;
    root         /path/to/zfapp/public;

    location / {
        index index.php;
        try_files $uri $uri/ @php;
    }

    location @php {
        # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME /path/to/zfapp/public/index.php;
        include fastcgi_params;
    }
}
```

Restart the nginx, now you should be ready to go!

## QA Tools

The skeleton does not come with any QA tooling by default, but does ship with
configuration for each of:

- [phpcs](https://github.com/squizlabs/php_codesniffer)
- [phpunit](https://phpunit.de)

Additionally, it comes with some basic tests for the shipped
`Application\Controller\IndexController`.

If you want to add these QA tools, execute the following:

```bash
$ composer require --dev phpunit/phpunit squizlabs/php_codesniffer zendframework/zend-test
```

We provide aliases for each of these tools in the Composer configuration:

```bash
# Run CS checks:
$ composer cs-check
# Fix CS errors:
$ composer cs-fix
# Run PHPUnit tests:
$ composer test
```



Select Salesperson to run pricing report:
a) Cyndi Metallo
b) Bill Zakrinski
c) Iris Derfler
d) All of the above.

foreach(salesperson){
    Show Added Products?
    foreach(salesperson.additemrows){display report}
    Show Price Overrides?
    foreach(salesperson.priceoverrides){display report}
    Show Pricing Reports?
    foreach(salesperson.priceoverridereports){display report}
}

Pricing Report for Customer ${customer} by Salesperson ${salesperson} at: ${time} on ${date}
Product             ||  Description             ||  Comment             ||  Price           ||  UOM             ||  SKU             ||  Variance (+/-)
Arctic Char - Farmed |                          |                       | $7.35             | ib                | 16200102              +$.35

Show Aggregate Total Discounts per salesperson?
foreach(salesperson){show total discounts per uom, show avg. percent of discount per uom ordered by largest margin}

cd ~/
sudo unzip pricing.zip
rm pricing.zip
cd /var/www/html/
sudo rm -rf /vol01/pricing_archive
sudo mv pricing/ /vol01/pricing_archive
sudo mv ~/pricing/ /var/www/html/pricing/
sudo chown apache:apache -R pricing/*
sudo chmod a+r,a+w,a+x -R pricing/*
sudo service httpd restart

<pre class='xdebug-var-dump' dir='ltr'>
<small>/u/local/jasonpalmer/pricing/module/Ajax/src/Controller/Sales/ItemsController.php:36:</small>
<b>object</b>(<i>Zend\Mvc\MvcEvent</i>)[<i>162</i>]
  <i>protected</i> 'application' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Zend\Mvc\Application</i>)[<i>132</i>]
      <i>protected</i> 'defaultListeners' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=6)</i>
          0 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'RouteListener'</font> <i>(length=13)</i>
          1 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'MiddlewareListener'</font> <i>(length=18)</i>
          2 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'DispatchListener'</font> <i>(length=16)</i>
          3 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'HttpMethodListener'</font> <i>(length=18)</i>
          4 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'ViewManager'</font> <i>(length=11)</i>
          5 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'SendResponseListener'</font> <i>(length=20)</i>
      <i>protected</i> 'event' <font color='#888a85'>=&gt;</font> 
        <i>&amp;</i><b>object</b>(<i>Zend\Mvc\MvcEvent</i>)[<i>162</i>]
      <i>protected</i> 'events' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\EventManager\EventManager</i>)[<i>130</i>]
          <i>protected</i> 'events' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=7)</i>
              ...
          <i>protected</i> 'eventPrototype' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\EventManager\Event</i>)[<i>133</i>]
              ...
          <i>protected</i> 'identifiers' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> 'sharedManager' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\EventManager\SharedEventManager</i>)[<i>19</i>]
              ...
      <i>protected</i> 'request' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\PhpEnvironment\Request</i>)[<i>138</i>]
          <i>protected</i> 'baseUrl' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
          <i>protected</i> 'basePath' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'requestUri' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/ajax/items?myaction=customerlisttableget&amp;customerid=398&amp;_=1479500682768'</font> <i>(length=72)</i>
          <i>protected</i> 'serverParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>141</i>]
              ...
          <i>protected</i> 'envParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>139</i>]
              ...
          <i>protected</i> 'method' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'GET'</font> <i>(length=3)</i>
          <i>protected</i> 'allowCustomMethods' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'uri' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Uri\Http</i>)[<i>145</i>]
              ...
          <i>protected</i> 'queryParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>140</i>]
              ...
          <i>protected</i> 'postParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'fileParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'1.1'</font> <i>(length=3)</i>
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Http\Headers</i>)[<i>142</i>]
              ...
          <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      <i>protected</i> 'response' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\PhpEnvironment\Response</i>)[<i>149</i>]
          <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'contentSent' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'recommendedReasonPhrases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=57)</i>
              ...
          <i>protected</i> 'statusCode' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>200</font>
          <i>protected</i> 'reasonPhrase' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      <i>protected</i> 'serviceManager' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\ServiceManager\ServiceManager</i>)[<i>11</i>]
          <i>protected</i> 'abstractFactories' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=7)</i>
              ...
          <i>protected</i> 'aliases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=65)</i>
              ...
          <i>protected</i> 'allowOverride' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'creationContext' <font color='#888a85'>=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Zend\ServiceManager\ServiceManager</i>)[<i>11</i>]
          <i>protected</i> 'delegators' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=7)</i>
              ...
          <i>protected</i> 'factories' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=98)</i>
              ...
          <i>protected</i> 'initializers' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> 'lazyServices' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>private</i> 'lazyServicesDelegator' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'resolvedAliases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=65)</i>
              ...
          <i>protected</i> 'services' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=73)</i>
              ...
          <i>protected</i> 'shared' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> 'sharedByDefault' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'configured' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
  <i>protected</i> 'request' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Zend\Http\PhpEnvironment\Request</i>)[<i>138</i>]
      <i>protected</i> 'baseUrl' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      <i>protected</i> 'basePath' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'requestUri' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/ajax/items?myaction=customerlisttableget&amp;customerid=398&amp;_=1479500682768'</font> <i>(length=72)</i>
      <i>protected</i> 'serverParams' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>141</i>]
          <i>private</i> 'storage' <small>(ArrayObject)</small> <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=73)</i>
              ...
      <i>protected</i> 'envParams' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>139</i>]
          <i>private</i> 'storage' <small>(ArrayObject)</small> <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=6)</i>
              ...
      <i>protected</i> 'method' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'GET'</font> <i>(length=3)</i>
      <i>protected</i> 'allowCustomMethods' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
      <i>protected</i> 'uri' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Uri\Http</i>)[<i>145</i>]
          <i>protected</i> 'validHostTypes' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>27</font>
          <i>protected</i> 'user' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'password' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'scheme' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'https'</font> <i>(length=5)</i>
          <i>protected</i> 'userInfo' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'host' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'pricing.localhost'</font> <i>(length=17)</i>
          <i>protected</i> 'port' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'path' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/ajax/items'</font> <i>(length=11)</i>
          <i>protected</i> 'query' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'myaction=customerlisttableget&amp;customerid=398&amp;_=1479500682768'</font> <i>(length=60)</i>
          <i>protected</i> 'fragment' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'queryParams' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>140</i>]
          <i>private</i> 'storage' <small>(ArrayObject)</small> <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=3)</i>
              ...
      <i>protected</i> 'postParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'fileParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'1.1'</font> <i>(length=3)</i>
      <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\Headers</i>)[<i>142</i>]
          <i>protected</i> 'pluginClassLoader' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Http\HeaderLoader</i>)[<i>144</i>]
              ...
          <i>protected</i> 'headersKeys' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=9)</i>
              ...
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=9)</i>
              ...
      <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color='#888a85'>empty</font></i>
      <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
  <i>protected</i> 'response' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Zend\Http\PhpEnvironment\Response</i>)[<i>149</i>]
      <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'contentSent' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
      <i>protected</i> 'recommendedReasonPhrases' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=57)</i>
          100 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Continue'</font> <i>(length=8)</i>
          101 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Switching Protocols'</font> <i>(length=19)</i>
          102 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Processing'</font> <i>(length=10)</i>
          200 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'OK'</font> <i>(length=2)</i>
          201 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Created'</font> <i>(length=7)</i>
          202 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Accepted'</font> <i>(length=8)</i>
          203 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Non-Authoritative Information'</font> <i>(length=29)</i>
          204 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'No Content'</font> <i>(length=10)</i>
          205 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Reset Content'</font> <i>(length=13)</i>
          206 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Partial Content'</font> <i>(length=15)</i>
          207 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Multi-status'</font> <i>(length=12)</i>
          208 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Already Reported'</font> <i>(length=16)</i>
          300 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Multiple Choices'</font> <i>(length=16)</i>
          301 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Moved Permanently'</font> <i>(length=17)</i>
          302 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Found'</font> <i>(length=5)</i>
          303 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'See Other'</font> <i>(length=9)</i>
          304 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Not Modified'</font> <i>(length=12)</i>
          305 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Use Proxy'</font> <i>(length=9)</i>
          306 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Switch Proxy'</font> <i>(length=12)</i>
          307 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Temporary Redirect'</font> <i>(length=18)</i>
          400 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Bad Request'</font> <i>(length=11)</i>
          401 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Unauthorized'</font> <i>(length=12)</i>
          402 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Payment Required'</font> <i>(length=16)</i>
          403 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Forbidden'</font> <i>(length=9)</i>
          404 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Not Found'</font> <i>(length=9)</i>
          405 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Method Not Allowed'</font> <i>(length=18)</i>
          406 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Not Acceptable'</font> <i>(length=14)</i>
          407 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Proxy Authentication Required'</font> <i>(length=29)</i>
          408 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Request Time-out'</font> <i>(length=16)</i>
          409 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Conflict'</font> <i>(length=8)</i>
          410 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Gone'</font> <i>(length=4)</i>
          411 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Length Required'</font> <i>(length=15)</i>
          412 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Precondition Failed'</font> <i>(length=19)</i>
          413 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Request Entity Too Large'</font> <i>(length=24)</i>
          414 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Request-URI Too Long'</font> <i>(length=20)</i>
          415 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Unsupported Media Type'</font> <i>(length=22)</i>
          416 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Requested range not satisfiable'</font> <i>(length=31)</i>
          417 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Expectation Failed'</font> <i>(length=18)</i>
          418 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'I&#39;m a teapot'</font> <i>(length=12)</i>
          422 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Unprocessable Entity'</font> <i>(length=20)</i>
          423 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Locked'</font> <i>(length=6)</i>
          424 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Failed Dependency'</font> <i>(length=17)</i>
          425 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Unordered Collection'</font> <i>(length=20)</i>
          426 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Upgrade Required'</font> <i>(length=16)</i>
          428 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Precondition Required'</font> <i>(length=21)</i>
          429 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Too Many Requests'</font> <i>(length=17)</i>
          431 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Request Header Fields Too Large'</font> <i>(length=31)</i>
          500 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Internal Server Error'</font> <i>(length=21)</i>
          501 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Not Implemented'</font> <i>(length=15)</i>
          502 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Bad Gateway'</font> <i>(length=11)</i>
          503 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Service Unavailable'</font> <i>(length=19)</i>
          504 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Gateway Time-out'</font> <i>(length=16)</i>
          505 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'HTTP Version not supported'</font> <i>(length=26)</i>
          506 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Variant Also Negotiates'</font> <i>(length=23)</i>
          507 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Insufficient Storage'</font> <i>(length=20)</i>
          508 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Loop Detected'</font> <i>(length=13)</i>
          511 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Network Authentication Required'</font> <i>(length=31)</i>
      <i>protected</i> 'statusCode' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>200</font>
      <i>protected</i> 'reasonPhrase' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color='#888a85'>empty</font></i>
      <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
  <i>protected</i> 'result' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
  <i>protected</i> 'router' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Zend\Router\Http\TreeRouteStack</i>)[<i>175</i>]
      <i>protected</i> 'baseUrl' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      <i>protected</i> 'requestUri' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Uri\Http</i>)[<i>145</i>]
          <i>protected</i> 'validHostTypes' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>27</font>
          <i>protected</i> 'user' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'password' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'scheme' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'https'</font> <i>(length=5)</i>
          <i>protected</i> 'userInfo' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'host' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'pricing.localhost'</font> <i>(length=17)</i>
          <i>protected</i> 'port' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'path' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/ajax/items'</font> <i>(length=11)</i>
          <i>protected</i> 'query' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'myaction=customerlisttableget&amp;customerid=398&amp;_=1479500682768'</font> <i>(length=60)</i>
          <i>protected</i> 'fragment' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'prototypes' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>ArrayObject</i>)[<i>177</i>]
          <i>private</i> 'storage' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
      <i>protected</i> 'routes' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Router\PriorityList</i>)[<i>176</i>]
          <i>protected</i> 'items' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=10)</i>
              ...
          <i>protected</i> 'serial' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>10</font>
          <i>protected</i> 'isLIFO' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>1</font>
          <i>protected</i> 'count' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>10</font>
          <i>protected</i> 'sorted' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
      <i>protected</i> 'routePluginManager' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Router\RoutePluginManager</i>)[<i>101</i>]
          <i>protected</i> 'instanceOf' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Zend\Router\RouteInterface'</font> <i>(length=26)</i>
          <i>protected</i> 'shareByDefault' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'sharedByDefault' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'autoAddInvokableClass' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'abstractFactories' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> 'aliases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=22)</i>
              ...
          <i>protected</i> 'allowOverride' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'creationContext' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\ServiceManager\ServiceManager</i>)[<i>11</i>]
              ...
          <i>protected</i> 'delegators' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'factories' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=19)</i>
              ...
          <i>protected</i> 'initializers' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'lazyServices' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>private</i> 'lazyServicesDelegator' <small>(Zend\ServiceManager\ServiceManager)</small> <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'resolvedAliases' <small>(Zend\ServiceManager\ServiceManager)</small> <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=22)</i>
              ...
          <i>protected</i> 'services' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'shared' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'configured' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
      <i>protected</i> 'defaultParams' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color='#888a85'>empty</font></i>
  <i>protected</i> 'routeMatch' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Zend\Router\Http\RouteMatch</i>)[<i>244</i>]
      <i>protected</i> 'length' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>11</font>
      <i>protected</i> 'params' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=1)</i>
          'controller' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Ajax\Controller\Sales\ItemsController'</font> <i>(length=37)</i>
      <i>protected</i> 'matchedRouteName' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'ajax'</font> <i>(length=4)</i>
  <i>protected</i> 'viewModel' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Zend\View\Model\ViewModel</i>)[<i>228</i>]
      <i>protected</i> 'captureTo' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'content'</font> <i>(length=7)</i>
      <i>protected</i> 'children' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color='#888a85'>empty</font></i>
      <i>protected</i> 'options' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color='#888a85'>empty</font></i>
      <i>protected</i> 'template' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'layout/layout'</font> <i>(length=13)</i>
      <i>protected</i> 'terminate' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
      <i>protected</i> 'variables' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\View\Variables</i>)[<i>229</i>]
          <i>protected</i> 'strictVars' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>private</i> 'storage' <small>(ArrayObject)</small> <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
      <i>protected</i> 'append' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
  <i>protected</i> 'name' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'dispatch'</font> <i>(length=8)</i>
  <i>protected</i> 'target' <font color='#888a85'>=&gt;</font> 
    <b>object</b>(<i>Ajax\Controller\Sales\ItemsController</i>)[<i>245</i>]
      <i>protected</i> 'restService' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Application\Service\RestService</i>)[<i>246</i>]
          <i>protected</i> 'logger' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Application\Service\LoggingService</i>)[<i>247</i>]
              ...
          <i>protected</i> 'pricingconfig' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=10)</i>
              ...
      <i>protected</i> 'logger' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Application\Service\LoggingService</i>)[<i>247</i>]
          <i>protected</i> 'logger' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Log\Logger</i>)[<i>250</i>]
              ...
      <i>protected</i> 'myauthstorage' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Login\Model\MyAuthStorage</i>)[<i>351</i>]
          <i>protected</i> 'session' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Session\Container</i>)[<i>350</i>]
              ...
          <i>protected</i> 'namespace' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'zf_tutorial'</font> <i>(length=11)</i>
          <i>protected</i> 'member' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'storage'</font> <i>(length=7)</i>
      <i>protected</i> 'pricingconfig' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=10)</i>
          'by_sku_base_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'https://svc.localhost/bySKU.php'</font> <i>(length=31)</i>
          'by_sku_userid' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'jpalmer'</font> <i>(length=7)</i>
          'by_sku_password' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'goodbass'</font> <i>(length=8)</i>
          'by_sku_method' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'GET'</font> <i>(length=3)</i>
          'by_sku_object_items_controller' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'customerlistitems'</font> <i>(length=17)</i>
          'by_sku_object_users_controller' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'customers'</font> <i>(length=9)</i>
          'by_sku_object_sales_controller' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'salespeople'</font> <i>(length=11)</i>
          'sslcapath' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/Users/jasonpalmer/svc.localhost.key'</font> <i>(length=36)</i>
          'sslcafile' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/Users/jasonpalmer/svc.localhost.crt'</font> <i>(length=36)</i>
          'ssl' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=4)</i>
              ...
      <i>protected</i> 'entityManager' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Doctrine\ORM\EntityManager</i>)[<i>257</i>]
          <i>private</i> 'config' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\Configuration</i>)[<i>262</i>]
              ...
          <i>private</i> 'conn' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\DBAL\Connection</i>)[<i>261</i>]
              ...
          <i>private</i> 'metadataFactory' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\Mapping\ClassMetadataFactory</i>)[<i>258</i>]
              ...
          <i>private</i> 'unitOfWork' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\UnitOfWork</i>)[<i>280</i>]
              ...
          <i>private</i> 'eventManager' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\Common\EventManager</i>)[<i>260</i>]
              ...
          <i>private</i> 'proxyFactory' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\Proxy\ProxyFactory</i>)[<i>287</i>]
              ...
          <i>private</i> 'repositoryFactory' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\Repository\DefaultRepositoryFactory</i>)[<i>279</i>]
              ...
          <i>private</i> 'expressionBuilder' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'closed' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>private</i> 'filterCollection' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'cache' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
      <i>protected</i> 'itemsFilterService' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Ajax\Service\Sales\ItemsFilterTableArrayService</i>)[<i>375</i>]
          <i>protected</i> 'logger' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Application\Service\LoggingService</i>)[<i>247</i>]
              ...
          <i>protected</i> 'pricingconfig' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=10)</i>
              ...
          <i>protected</i> 'myauthstorage' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Login\Model\MyAuthStorage</i>)[<i>351</i>]
              ...
          <i>protected</i> 'entityManager' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Application\Service\EntityService</i>)[<i>253</i>]
              ...
          <i>protected</i> 'checkboxService' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Sales\Service\CheckboxService</i>)[<i>255</i>]
              ...
          <i>protected</i> 'productrepository' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl</i>)[<i>395</i>]
              ...
      <i>protected</i> 'checkboxService' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Sales\Service\CheckboxService</i>)[<i>255</i>]
          <i>protected</i> 'logger' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Application\Service\LoggingService</i>)[<i>247</i>]
              ...
          <i>protected</i> 'checkboxrepository' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>DataAccess\FFM\Entity\Repository\Impl\ItemTableCheckboxRepositoryImpl</i>)[<i>314</i>]
              ...
          <i>protected</i> 'userrepository' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>DataAccess\FFM\Entity\Repository\Impl\UserRepositoryImpl</i>)[<i>342</i>]
              ...
      <i>protected</i> 'productrepository' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>DataAccess\FFM\Entity\Repository\Impl\ProductRepositoryImpl</i>)[<i>395</i>]
          <i>protected</i> '_entityName' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'DataAccess\FFM\Entity\Product'</font> <i>(length=29)</i>
          <i>protected</i> '_em' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\EntityManager</i>)[<i>257</i>]
              ...
          <i>protected</i> '_class' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\Mapping\ClassMetadata</i>)[<i>374</i>]
              ...
      <i>protected</i> 'customerrepository' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>DataAccess\FFM\Entity\Repository\Impl\CustomerRepositoryImpl</i>)[<i>426</i>]
          <i>protected</i> '_entityName' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'DataAccess\FFM\Entity\Customer'</font> <i>(length=30)</i>
          <i>protected</i> '_em' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\EntityManager</i>)[<i>257</i>]
              ...
          <i>protected</i> '_class' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\Mapping\ClassMetadata</i>)[<i>409</i>]
              ...
      <i>protected</i> 'qb' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Doctrine\ORM\QueryBuilder</i>)[<i>431</i>]
          <i>private</i> '_em' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\ORM\EntityManager</i>)[<i>257</i>]
              ...
          <i>private</i> '_dqlParts' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=9)</i>
              ...
          <i>private</i> '_type' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>0</font>
          <i>private</i> '_state' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>1</font>
          <i>private</i> '_dql' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'parameters' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Doctrine\Common\Collections\ArrayCollection</i>)[<i>435</i>]
              ...
          <i>private</i> '_firstResult' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> '_maxResults' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'joinRootAliases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'cacheable' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'cacheRegion' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'cacheMode' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'lifetime' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>0</font>
      <i>protected</i> 'eventIdentifier' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Zend\Mvc\Controller\AbstractRestfulController'</font> <i>(length=45)</i>
      <i>protected</i> 'contentTypes' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=1)</i>
          'json' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=2)</i>
              ...
      <i>protected</i> 'identifierName' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'id'</font> <i>(length=2)</i>
      <i>protected</i> 'jsonDecodeType' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
      <i>protected</i> 'customHttpMethodsMap' <font color='#888a85'>=&gt;</font> 
        <b>array</b> <i>(size=0)</i>
          <i><font color='#888a85'>empty</font></i>
      <i>protected</i> 'plugins' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Mvc\Controller\PluginManager</i>)[<i>94</i>]
          <i>protected</i> 'instanceOf' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Zend\Mvc\Controller\Plugin\PluginInterface'</font> <i>(length=42)</i>
          <i>protected</i> 'aliases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=32)</i>
              ...
          <i>protected</i> 'factories' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=19)</i>
              ...
          <i>protected</i> 'controller' <font color='#888a85'>=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Ajax\Controller\Sales\ItemsController</i>)[<i>245</i>]
          <i>protected</i> 'autoAddInvokableClass' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'abstractFactories' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'allowOverride' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'creationContext' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\ServiceManager\ServiceManager</i>)[<i>11</i>]
              ...
          <i>protected</i> 'delegators' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'initializers' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'lazyServices' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>private</i> 'lazyServicesDelegator' <small>(Zend\ServiceManager\ServiceManager)</small> <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>private</i> 'resolvedAliases' <small>(Zend\ServiceManager\ServiceManager)</small> <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=32)</i>
              ...
          <i>protected</i> 'services' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'shared' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'sharedByDefault' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'configured' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
      <i>protected</i> 'request' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\PhpEnvironment\Request</i>)[<i>138</i>]
          <i>protected</i> 'baseUrl' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
          <i>protected</i> 'basePath' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'requestUri' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/ajax/items?myaction=customerlisttableget&amp;customerid=398&amp;_=1479500682768'</font> <i>(length=72)</i>
          <i>protected</i> 'serverParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>141</i>]
              ...
          <i>protected</i> 'envParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>139</i>]
              ...
          <i>protected</i> 'method' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'GET'</font> <i>(length=3)</i>
          <i>protected</i> 'allowCustomMethods' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'uri' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Uri\Http</i>)[<i>145</i>]
              ...
          <i>protected</i> 'queryParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>140</i>]
              ...
          <i>protected</i> 'postParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'fileParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'1.1'</font> <i>(length=3)</i>
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Http\Headers</i>)[<i>142</i>]
              ...
          <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      <i>protected</i> 'response' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\PhpEnvironment\Response</i>)[<i>149</i>]
          <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'contentSent' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'recommendedReasonPhrases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=57)</i>
              ...
          <i>protected</i> 'statusCode' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>200</font>
          <i>protected</i> 'reasonPhrase' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      <i>protected</i> 'event' <font color='#888a85'>=&gt;</font> 
        <i>&amp;</i><b>object</b>(<i>Zend\Mvc\MvcEvent</i>)[<i>162</i>]
      <i>protected</i> 'events' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\EventManager\EventManager</i>)[<i>440</i>]
          <i>protected</i> 'events' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> 'eventPrototype' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\EventManager\Event</i>)[<i>441</i>]
              ...
          <i>protected</i> 'identifiers' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=8)</i>
              ...
          <i>protected</i> 'sharedManager' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\EventManager\SharedEventManager</i>)[<i>19</i>]
              ...
  <i>protected</i> 'params' <font color='#888a85'>=&gt;</font> 
    <b>array</b> <i>(size=5)</i>
      'application' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Mvc\Application</i>)[<i>132</i>]
          <i>protected</i> 'defaultListeners' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=6)</i>
              ...
          <i>protected</i> 'event' <font color='#888a85'>=&gt;</font> 
            <i>&amp;</i><b>object</b>(<i>Zend\Mvc\MvcEvent</i>)[<i>162</i>]
          <i>protected</i> 'events' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\EventManager\EventManager</i>)[<i>130</i>]
              ...
          <i>protected</i> 'request' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Http\PhpEnvironment\Request</i>)[<i>138</i>]
              ...
          <i>protected</i> 'response' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Http\PhpEnvironment\Response</i>)[<i>149</i>]
              ...
          <i>protected</i> 'serviceManager' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\ServiceManager\ServiceManager</i>)[<i>11</i>]
              ...
      'request' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\PhpEnvironment\Request</i>)[<i>138</i>]
          <i>protected</i> 'baseUrl' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
          <i>protected</i> 'basePath' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'requestUri' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/ajax/items?myaction=customerlisttableget&amp;customerid=398&amp;_=1479500682768'</font> <i>(length=72)</i>
          <i>protected</i> 'serverParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>141</i>]
              ...
          <i>protected</i> 'envParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>139</i>]
              ...
          <i>protected</i> 'method' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'GET'</font> <i>(length=3)</i>
          <i>protected</i> 'allowCustomMethods' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>true</font>
          <i>protected</i> 'uri' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Uri\Http</i>)[<i>145</i>]
              ...
          <i>protected</i> 'queryParams' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Stdlib\Parameters</i>)[<i>140</i>]
              ...
          <i>protected</i> 'postParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'fileParams' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'1.1'</font> <i>(length=3)</i>
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Http\Headers</i>)[<i>142</i>]
              ...
          <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      'response' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Http\PhpEnvironment\Response</i>)[<i>149</i>]
          <i>protected</i> 'version' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'contentSent' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>
          <i>protected</i> 'recommendedReasonPhrases' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=57)</i>
              ...
          <i>protected</i> 'statusCode' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>200</font>
          <i>protected</i> 'reasonPhrase' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'headers' <font color='#888a85'>=&gt;</font> <font color='#3465a4'>null</font>
          <i>protected</i> 'metadata' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
          <i>protected</i> 'content' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
      'router' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Router\Http\TreeRouteStack</i>)[<i>175</i>]
          <i>protected</i> 'baseUrl' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>''</font> <i>(length=0)</i>
          <i>protected</i> 'requestUri' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Uri\Http</i>)[<i>145</i>]
              ...
          <i>protected</i> 'prototypes' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>ArrayObject</i>)[<i>177</i>]
              ...
          <i>protected</i> 'routes' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Router\PriorityList</i>)[<i>176</i>]
              ...
          <i>protected</i> 'routePluginManager' <font color='#888a85'>=&gt;</font> 
            <b>object</b>(<i>Zend\Router\RoutePluginManager</i>)[<i>101</i>]
              ...
          <i>protected</i> 'defaultParams' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=0)</i>
              ...
      'route-match' <font color='#888a85'>=&gt;</font> 
        <b>object</b>(<i>Zend\Router\Http\RouteMatch</i>)[<i>244</i>]
          <i>protected</i> 'length' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>11</font>
          <i>protected</i> 'params' <font color='#888a85'>=&gt;</font> 
            <b>array</b> <i>(size=1)</i>
              ...
          <i>protected</i> 'matchedRouteName' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'ajax'</font> <i>(length=4)</i>
  <i>protected</i> 'stopPropagation' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'>false</font>

