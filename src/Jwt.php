<?php declare(strict_types=1);

namespace PluMA;

class Jwt
{
  private string $salt;

  public function __construct(
    string $salt
  )
  {
    $this->salt = $salt;
  }


}
