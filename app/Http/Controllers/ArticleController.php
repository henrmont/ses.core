<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getArticles()
    {
        $articles = Article::all();
        return response()->json($articles, 200);
    }
}
