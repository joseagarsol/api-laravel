<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
class PruebasController extends Controller
{
    public function pruebas(Request $request)
    {
        return "Acción de pruebas de pruebas";
    }
    public function testOrm(){
        $posts = Post::all();
        foreach($posts as $post)
        {
            echo "<h1>";
        }
        var_dump($post);
        die();
    }
}
