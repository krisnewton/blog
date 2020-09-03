<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Label;

// Dependency:
//// harishariyanto/seo

class BlogController extends Controller
{
    // route name = 'blog.home'
    public function index()
    {
    	$posts = Post::latest()->published()->simplePaginate(setting('blog.items_per_page'));
    	
    	return view('apps.blog.index', compact('posts'));
    }

    // route name = 'blog.post'
    public function post(Request $request, Post $post)
    {
        $post->increment('views');

    	$breadcrumb = [
            $post->category->name => route('blog.category', ['category' => $post->category]),
    		$post->title => ''
    	];

        $category_id = $post->category_id;
        $related_posts = Post::published()->where('category_id', $category_id)->where('id', '<>', $post->id)->latest()->limit(5)->get();

    	return view('apps.blog.post', compact('post', 'breadcrumb', 'related_posts'));
    }

    // route name = 'blog.category'
    public function category(Request $request, Category $category)
    {
        $breadcrumb = [
            $category->name => ''
        ];
        $posts = $category->posts()->published()->latest()->simplePaginate(setting('blog.items_per_page'));

        return view('apps.blog.category', compact('category', 'breadcrumb', 'posts'));
    }

    // route name = 'blog.label'
    public function label(Request $request, Label $label)
    {
        $breadcrumb = [
            'Label ' . ucfirst($label->name) => ''
        ];
        $posts = $label->posts()->published()->latest()->simplePaginate(setting('blog.items_per_page'));

        return view('apps.blog.label', compact('label', 'breadcrumb', 'posts'));
    }

    // route name = 'blog.search'
    public function search(Request $request)
    {
        $keywords = $request->query('q');

        if (!empty($keywords)) {
            $keywords = preg_replace('/\s+/', ' ', $keywords);
            $keywords = explode(' ', $keywords);

            $posts = Post::published();
            $posts = $posts->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', '%' . $keyword . '%');
                }
            });
            $posts = $posts->latest()->simplePaginate(setting('blog.items_per_page'));

            return view('apps.blog.search', compact('posts'));
        }
        else {
            return redirect()->route('home');
        }
    }
}