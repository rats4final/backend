<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    function test_can_get_all_books(){//para la funcion usar el prefijo test o /**@test */ antes de la funcion
        $books = Book::factory(4)->create();
        $this->getJson(route('books.index'))->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }
    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();
        $this->getJson(route('books.show',$book))->assertJsonFragment([
            'title'=>$book->title,
        ]);
    }
    /** @test */
    function can_create_books(){

        $this->postJson(route('books.store'),[])->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
            'title'=> 'Mi libro Luna de Pluton'
        ])->assertJsonFragment([
            'title' => 'Mi libro Luna de Pluton'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'Mi libro Luna de Pluton'
        ]);
    }
    /** @test */
    function can_update_books(){
        $book = Book::factory()->create();
        $this->patchJson(route('books.update',$book),[])->assertJsonValidationErrorFor('title');
        $this->patchJson(route('books.update',$book),[
            'title' => 'Titulo editado'
        ])->assertJsonFragment([
            'title' => 'Titulo editado'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'Titulo editado'
        ]);
    }
    /** @test */
    function can_delete_books(){
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy',$book))->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }

}
