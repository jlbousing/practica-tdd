<?php

namespace Tests\Unit\Models;

use App\Models\Repository;
//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{

    use RefreshDatabase;

    public function test_belong_to_user()
    {
        $repository = Repository::factory()->create();

        $this->assertInstanceOf(User::class,$repository->user);
    }
}
