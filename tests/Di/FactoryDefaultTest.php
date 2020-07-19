<?php declare(strict_types=1);

namespace PluMA\Test\Di\FactoryDefault;

use \PHPUnit\Framework\TestCase;
use \PluMA\Di\FactoryDefault;

class FactoryDefaultTest extends TestCase
{
  protected $container;

  public function testCanInstance(): void
  {
    $this->assertInstanceOf(
      FactoryDefault::class,
      new FactoryDefault()
    );
  }
}
