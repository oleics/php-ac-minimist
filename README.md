
ac-minimist
===========

Light-weight argument option parsing for PHP in the spirit of [minimist](https://github.com/substack/minimist).

Install
-------

```sh
composer require oleics/ac-minimist
```

Usage
-----

```php
use Ac\Minimist
$opts = Minimist::parse($argv);
var_dump($opts);
```

```
$ php example/parse.php -a beep -b boop
array(3) {
  ["_"]=>
  array(2) {
    [0]=>
    string(4) "beep"
    [1]=>
    string(4) "boop"
  }
  ["a"]=>
  bool(true)
  ["b"]=>
  bool(true)
}
```

```
$ php example/parse.php -x 3 -y 4 -n5 -abc --beep=boop foo bar baz
array(9) {
  ["_"]=>
  array(5) {
    [0]=> string(1) "3"
    [1]=> string(1) "4"
    [2]=> string(3) "foo"
    [3]=> string(3) "bar"
    [4]=> string(3) "baz"
  }
  ["x"]=> bool(true)
  ["y"]=> bool(true)
  ["n"]=> int(5)
  ["a"]=> bool(true)
  ["b"]=> bool(true)
  ["c"]=> bool(true)
  ["beep"]=> string(4) "boop"
}

```

You can specify more details about options:

```php
use Ac\Minimist
$opts = Minimist::parse($argv, [
  'string'    => ['foo', 'g'],
  'boolean'   => ['baz'],
  'alias'     => ['foo' => ['f', 'g']],
  'default'   => [ 'foo' => 'bar', 'baz' => function() { return false; } ],
  'stopEarly' => false,
  'unknown' => function($optname){return false;} // return TRUE for known options
]);
var_dump($opts);
```

MIT License
-----------

Copyright (c) 2016 Oliver Leics <oliver.leics@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
