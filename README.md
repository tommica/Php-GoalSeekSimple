# Php-GoalSeekSimple

This is a port of:
https://github.com/adam-hanna/goal-seek

Usage:

```php
<?php

use Tommica\GoalSeekSimple;

$result = GoalSeekSimple::Solve(
    array($this, 'calculate'),
    array(1, 9, 19),
    0,
    200,
    1,
    684,
    0
);

$this->assertEquals(4, $result);
```
