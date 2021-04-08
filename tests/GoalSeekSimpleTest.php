<?php

use PHPUnit\Framework\TestCase;
use Tommica\FailedToConvergeError;
use Tommica\GoalSeekSimple;
use Tommica\IsNanError;

final class GoalSeekSimpleTest extends TestCase
{
    public function calculate($x, $y, $z, $withPercentageSign = false)
    {
        $result = $x * $y * $z;

        if ($withPercentageSign) {
            return $result . "%";
        } else {
            return $result;
        }
    }

    public function test_throws_is_nan_exception()
    {
        $this->expectException(IsNanError::class);

        $_ = GoalSeekSimple::Solve(
            array($this, 'calculate'),
            array(1, 2, 9, true),
            0,
            10,
            1,
            100,
            0
        );
    }

    public function test_throws_converge_failed_exception()
    {
        $this->expectException(FailedToConvergeError::class);

        $_ = GoalSeekSimple::Solve(
            array($this, 'calculate'),
            array(1, 9, 19),
            0,
            2,
            1,
            100,
            0
        );
    }

    public function test_manages_to_solve_the_calculation()
    {
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
    }
}
