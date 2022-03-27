<?php

namespace Tests\Unit\Models;

//use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_many_repositories()
    {
        $user = new User();

        $this->assertInstanceOf(Collection::class,$user->repositories);
    }
}
