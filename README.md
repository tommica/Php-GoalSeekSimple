# Php-GoalSeekSimple

This is a port of:
https://github.com/adam-hanna/goal-seek

Usage:

```
composer require tommica/php-goalseeksimple
```

```php
<?php

use Tommica\GoalSeekSimple;

function calculate($x, $y, $z) {
    return $x * $y * $z;
}

$result = GoalSeekSimple::Solve(
    'calculate',
    array(1, 9, 19),
    0,
    200,
    1,
    684,
    0
);

echo $result; // 4
```
