<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_guest()
    {
        $this->get("repositories")->assertRedirect("login");  //index
        $this->get("repositories/1")->assertRedirect("login"); //show
        $this->get("repositories/1/edit")->assertRedirect("login"); //edit
        $this->put("repositories/1")->assertRedirect("login"); //update
        $this->delete("repositories/1")->assertRedirect("login"); //destroy
        $this->get("repositories/create")->assertRedirect("login"); //create
        $this->post("repositories",[])->assertRedirect("login"); //store
    }

    public function test_store()
    {

        //SE TIENE UN FORMULARIO
        $data = [
            "url" => $this->faker->url,
            "description" => $this->faker->text,
        ];

        //SE TIENE UN USUARIO REGISTRADO
        $user = User::factory()->create();

        //SE CONECTA COMO ESE USUARIO REGISTRADO, SE LLENA EL FORMULARIO Y HAY UNA REDIRECCION AL INDEX
        $this->actingAs($user) //ACTUA COMO EL USUARIO CREEADO POR EL FAKER
             ->post("repositories",$data)
            ->assertRedirect("repositories");

        //EL DATO SE REGISTRA EN LA BD
        $this->assertDatabaseHas("repositories", $data);
    }

    public function test_update()
    {
        //EXISTE UN DATO QUE SE QUIERE ACTUALIZAR
        $repository = Repository::factory()->create();

        //SE LLENA EL FORMULARIO CON LA INFORMACION QUE SE QUIERE EDITAR
        $data = [
            "url" => $this->faker->url,
            "description" => $this->faker->text
        ];

        //DEBE HABER UN USUARIO LOGEADO
        $user = User::factory()->create();

        //SE ACTUA COMO EL USUARIO LOGEADO Y SE ACTUALIZA Y SE REDIRECCIONA
        $this->actingAs($user)
             ->put("repositories/{$repository->id}",$data)
             ->assertRedirect("repositories/{$repository->id}/edit");

        //EL DATO SE REGISTRA EN LA BD
        $this->assertDatabaseHas("repositories",$data);


    }
}
