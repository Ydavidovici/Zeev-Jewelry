<?php

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

class CustomTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        echo $t->getMessage() . "\n";
    }

    public function addFailure(Test $test, \PHPUnit\Framework\AssertionFailedError $e, float $time): void
    {
        echo $e->getMessage() . "\n";
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        echo $e->getMessage() . "\n";
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        echo "Incomplete test: " . $t->getMessage() . "\n";
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        echo "Risky test: " . $t->getMessage() . "\n";
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        echo "Skipped test: " . $t->getMessage() . "\n";
    }
}
