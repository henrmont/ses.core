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

    public function getArticle($id)
    {
        $article = Article::find($id);
        return response()->json($article, 200);
    }
}
