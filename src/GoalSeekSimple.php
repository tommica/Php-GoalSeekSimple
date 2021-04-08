<?php

namespace Tommica;

use Exception;

class IsNanError extends Exception
{
}
class FailedToConvergeError extends Exception
{
}

class GoalSeekSimple
{
    /**
     * Solve
     *
     * @param callable $fn
     * @param array|null $fnParams
     * @param float|int $percentTolerance
     * @param int $maxIterations
     * @param float|int $maxStep
     * @param float|int $goal
     * @param int $independentVariableIdx
     * @return void
     * @throws \Exception
     */
    public static function Solve(
        $fn,
        $fnParams,
        $percentTolerance,
        $maxIterations,
        $maxStep,
        $goal,
        $independentVariableIdx
    ) {

        $g = null;
        $y = null;
        $y1 = null;
        $oldGuess = null;
        $newGuess = null;

        $absoluteTolerance = ($percentTolerance / 100) * $goal;

        // iterate through the guesses
        for ($i = 0; $i < $maxIterations; $i++) {
            // define the root of the function as the error
            $y = call_user_func_array($fn, $fnParams);
            if (!is_numeric($y)) {
                throw new IsNanError("resulted in NaN");
            }
            $y = $y  - $goal;

            // was our initial guess a good one?
            if (abs($y) <= abs($absoluteTolerance)) {
                return $fnParams[$independentVariableIdx];
            }

            // set the new guess, correcting for maxStep
            $oldGuess = $fnParams[$independentVariableIdx];
            $newGuess = $oldGuess + $y;
            if (abs($newGuess - $oldGuess) > $maxStep) {
                if ($newGuess > $oldGuess) {
                    $newGuess = $oldGuess + $maxStep;
                } else {
                    $newGuess = $oldGuess - $maxStep;
                }
            }

            $fnParams[$independentVariableIdx] = $newGuess;

            // re-run the fn with the new guess
            $y1 = call_user_func_array($fn, $fnParams);
            if (!is_numeric($y1)) {
                throw new IsNanError("resulted in NaN");
            }
            $y1 = $y1 - $goal;

            // calculate the error
            $g = ($y1 - $y) / $y;
            if ($g === 0) $g = 0.0001;

            // set the new guess based on the error, correcting for maxStep
            $newGuess = $oldGuess - $y / $g;
            if ($maxStep && abs($newGuess - $oldGuess) > $maxStep) {
                if ($newGuess > $oldGuess) {
                    $newGuess = $oldGuess + $maxStep;
                } else {
                    $newGuess = $oldGuess - $maxStep;
                }
            }

            $fnParams[$independentVariableIdx] = $newGuess;
        }

        // done with iterations, and we failed to converge
        throw new FailedToConvergeError('failed to converge');
    }
}
