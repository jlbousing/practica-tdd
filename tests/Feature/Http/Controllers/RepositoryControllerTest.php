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

    public function test_index_empty()
    {
        Repository::factory()->create(); //user_id = 1
        $user = User::factory()->create(); //id = 2

        $this->actingAs($user)
            ->get("repositories")
            ->assertStatus(200)
            ->assertSee("No hay repositorios creados");
    }

    public function test_index_with_data()
    {
        $user = User::factory()->create(); //id = 2
        $repository = Repository::factory()->create(["user_id" => $user->id]);

        $this->actingAs($user)
            ->get("repositories")
            ->assertStatus(200)
            ->assertSee($repository->id)
            ->assertSee($repository->url);
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
        //DEBE HABER UN USUARIO LOGEADO
        $user = User::factory()->create();

        //EXISTE UN DATO QUE SE QUIERE ACTUALIZAR
        $repository = Repository::factory()->create([
            "user_id" => $user->id
        ]);

        //SE LLENA EL FORMULARIO CON LA INFORMACION QUE SE QUIERE EDITAR
        $data = [
            "url" => $this->faker->url,
            "description" => $this->faker->text
        ];



        //SE ACTUA COMO EL USUARIO LOGEADO Y SE ACTUALIZA Y SE REDIRECCIONA
        $this->actingAs($user)
             ->put("repositories/{$repository->id}",$data)
             ->assertRedirect("repositories/{$repository->id}/edit");

        //EL DATO SE REGISTRA EN LA BD
        $this->assertDatabaseHas("repositories",$data);
    }

    public function test_show()
    {

        $user = User::factory()->create();

        $repository = Repository::factory()->create([
            "user_id" => $user->id
        ]);

        $this->actingAs($user)
            ->get("repositories/{$repository->id}")
            ->assertStatus(200);
            //->assertRedirect("repositories/{$repository->id}");

    }

    public function test_edit()
    {
        $user = User::factory()->create();

        $repository = Repository::factory()->create([
            "user_id" => $user->id
        ]);

        $this->actingAs($user)
            ->get("repositories/{$repository->id}/edit")
            ->assertStatus(200)
            ->assertSee($repository->url)
            ->assertSee($repository->description);

    }

    public function test_validate_store()
    {

        //SE TIENE UN FORMULARIO
        $data = [];

        //SE TIENE UN USUARIO REGISTRADO
        $user = User::factory()->create();

        //SE CONECTA COMO ESE USUARIO REGISTRADO, SE LLENA EL FORMULARIO Y HAY UNA REDIRECCION AL INDEX
        $this->actingAs($user) //ACTUA COMO EL USUARIO CREEADO POR EL FAKER
        ->post("repositories",$data)
            ->assertStatus(302)
            ->assertSessionHasErrors(["url","description"]);
    }

    public function test_validate_update()
    {
        //EXISTE UN DATO QUE SE QUIERE ACTUALIZAR
        $repository = Repository::factory()->create();

        //SE LLENA EL FORMULARIO CON LA INFORMACION QUE SE QUIERE EDITAR
        $data = [];

        //DEBE HABER UN USUARIO LOGEADO
        $user = User::factory()->create();

        //SE ACTUA COMO EL USUARIO LOGEADO Y SE ACTUALIZA Y SE REDIRECCIONA
        $this->actingAs($user)
            ->put("repositories/{$repository->id}",$data)
            ->assertStatus(302)
            ->assertSessionHasErrors(["url","description"]);

        //EL DATO SE REGISTRA EN LA BD
        $this->assertDatabaseHas("repositories",$data);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();

        $repository = Repository::factory()->create(["user_id" => $user->id]);

        $this->actingAs($user)
             ->delete("repositories/{$repository->id}")
             ->assertRedirect("repositories");

        //SE VALIDA QUE HACE FALTA ESE REGISTRO EN LA BASE DE DATOS
        $this->assertDatabaseMissing("repositories",[
            "id" => $repository->id,
            "url" => $repository->url,
            "description" => $repository->description
        ]);
    }

    public function test_update_policy()
    {

        $user = User::factory()->create(); // id = 1

        $repository = Repository::factory()->create(); //user_id = 2

        $data = [
            "url" => $this->faker->url,
            "description" => $this->faker->text
        ];

        $this->actingAs($user)
            ->put("repositories/{$repository->id}",$data)
            ->assertStatus(403);

    }

    public function test_destroy_policy()
    {

        $user = User::factory()->create(); //id = 1
        $repository = Repository::factory()->create(); //user_id = 2

        $this->actingAs($user)
            ->delete("repositories/{$repository->id}")
            ->assertStatus(403);

    }

    public function test_show_policy()
    {
        $user = User::factory()->create(); //id = 1
        $repository = Repository::factory()->create(); //user_id = 2

        $this->actingAs($user)
            ->get("repositories/{$repository->id}")
            ->assertStatus(403);
    }

    public function test_edit_policy()
    {
        $user = User::factory()->create(); //id = 1
        $repository = Repository::factory()->create(); //user_id = 2

        $this->actingAs($user)
            ->get("repositories/{$repository->id}/edit")
            ->assertStatus(403);
    }
}
