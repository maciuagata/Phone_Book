<?php
namespace App\Tests\Util;

use App\Entity\User;
use PHPUnit\Framework\TestCase;


class PasswordTest extends TestCase
{
    public function testGetPassword()
    {
      $user= new User;
      $user->setPassword('agata1234');
      $this->assertEquals($user->getPassword(), 'agata1234');
    }
}