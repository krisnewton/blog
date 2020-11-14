<?php

namespace App\Http\Controllers\Apps;

use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Image;
use DataTables;
use App\User;
use Illuminate\Support\Facades\Gate;
use App\Category;
use App\Label;
use App\PostLabel;

class PostController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
	}

    public function index(Request $request)
    {
    	$this->authorize('access', 'posts.index');

        $breadcrumb = [
        	'Post' => ''
        ];

        if (Gate::allows('access', 'posts.index.all')) {
            $posts = Post::get();
        }
        else {
            $posts = Post::where('user_id', $request->user()->id)->get();
        }

        return view('apps.posts.index', compact('breadcrumb', 'posts'));
    }

    public function datatables(Request $request)
    {
        $this->authorize('access', 'posts.index');

        if ($request->ajax() || true) {

            if (Gate::allows('access', 'posts.index.all')) {
                $data = Post::get();
            }
            else {
                $data = Post::where('user_id', $request->user()->id)->get();
            }

            return DataTables::of($data)
                ->only(['title', 'views', 'action', 'created_at', 'status', 'author', 'timestamp'])
                ->addColumn('action', function (Post $post) {
                    $output = '';

                    if (Gate::allows('access', ['posts.edit', $post->user_id]) || Gate::allows('access', 'posts.edit.all')) {
                        $output .= '<a href="' . route('posts.edit', ['post' => $post]) . '" class="btn btn-primary btn-sm">Edit</a> ';
                    }

                    if (Gate::allows('access', ['posts.destroy', $post->user_id]) || Gate::allows('access', 'posts.destroy.all')) {
                        $output .= '<button class="btn btn-danger btn-sm" data-id="' . $post->id . '" data-name="' . $post->title . '" data-action="' . route('posts.destroy', ['post' => $post]) . '">Hapus</button>';
                    }

                    return $output;
                })
                ->addColumn('author', function (Post $post) {
                    return $post->user->name;
                })
                ->addColumn('timestamp', function (Post $post) {
                    return $post->created_at->timestamp;
                })
                ->editColumn('created_at', function (Post $post) {
                    return $post->created_at->diffForHumans();
                })
                ->editColumn('status', function (Post $post) {
                    if ($post->status == 'published') {
                        return '<span class="badge badge-success">Terbit</span>';
                    }
                    else {
                        return '<span class="badge badge-warning">Draft</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->toJson();
        }
        else {
            abort(404);
        }
    }

    public function create()
    {
        $this->authorize('access', 'posts.create');

        $breadcrumb = [
        	'Post'         => route('posts.index'),
        	'Tambah Post'  => ''
        ];

        $cats = Category::where('id', '<>', 1)->orderBy('name', 'asc')->get();
        $categories = [];
        $categories[1] = 'Tidak Berkategori';
        foreach ($cats as $cat) {
            $categories[$cat->id] = $cat->name;
        }

        return view('apps.posts.create', compact('breadcrumb', 'categories'));
    }

    public function store(Request $request)
    {
    	$this->authorize('access', 'posts.create');

        $data = $request->validate([
        	'title'         => ['required'],
            'seo_title'     => ['nullable'],
            'content'       => ['required'],
            'excerpt'       => ['nullable'],
            'slug'          => ['nullable', 'unique:posts,slug'],
            'cover'         => ['required'],
            'thumbnail'     => ['required'],
            'status'        => ['required'],
            'category_id'   => ['nullable']
        ]);
        $data['user_id'] = $request->user()->id;

        if ($data['status'] == 'Terbitkan') {
            $data['status'] = 'published';
        }
        else {
            $data['status'] = 'draft';
        }

        if (empty($request->input('slug'))) {
            $data['slug'] = Str::slug($data['title'], '-');
        }
        else {
            $data['slug'] = Str::slug($data['slug'], '-');
        }

        $base_slug = $data['slug'];

        $counter = Post::where('slug', $data['slug'])->count();
        if ($counter > 0) {
            $loop = true;
            $i = 1;
            while ($loop) {
                $data['slug'] = $base_slug . '-' . $i;
                $counter = Post::where('slug', $data['slug'])->count();

                if ($counter == 0) {
                    $loop = false;
                }
                else {
                    $i++;
                    $loop = true;
                }
            }
        }

        // Create snippet
        $contents = preg_replace('/\s+/', ' ', strip_tags($data['content']));
        $contents = explode(' ', $contents);
        $snippet = '';
        for ($i = 0; $i < 20; $i++) {
            if (!empty($contents[$i])) {
                $snippet .= $contents[$i] . ' ';
            }
        }
        $data['snippet'] = trim($snippet);
        // [END] Create snippet

        Post::create($data);

        // Label
        $post_id = Post::where('slug', $data['slug'])->first();
        $post_id = $post_id->id;

        $labels = $request->input('labels');
        $labels = explode(',', $labels);

        foreach ($labels as $label) {
            $label = strtolower(trim($label));

            if (!empty($label)) {
                $label_id = Label::firstOrCreate(['name' => $label, 'slug' => Str::slug($label, '-')]);

                PostLabel::firstOrCreate([
                    'post_id' => $post_id,
                    'label_id' => $label_id->id
                ]);
            }
        }
        // [END] Label

        return redirect()->route('posts.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Post $post)
    {
        if (Gate::denies('access', ['posts.edit', $post->user_id]) && Gate::denies('access', 'posts.edit.all')) {
            abort(403);
        }

        $breadcrumb = [
        	'Post' => route('posts.index'),
        	'Edit Post' => ''
        ];

        $cats = Category::orderBy('name', 'asc')->get();
        $categories = [];
        $categories['0'] = 'Tidak Berkategori';
        foreach ($cats as $cat) {
            $categories[$cat->id] = $cat->name;
        }

        return view('apps.posts.edit', compact('breadcrumb', 'post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        if (Gate::denies('access', ['posts.edit', $post->user_id]) && Gate::denies('access', 'posts.edit.all')) {
            abort(403);
        }

        $data = $request->validate([
        	'title'         => ['required'], 
            'seo_title'     => ['nullable'],
            'content'       => ['required'], 
            'excerpt'       => ['nullable'], 
            'slug'          => ['required', 'unique:posts,slug,' . $post->id],
            'cover'         => ['nullable'],
            'thumbnail'     => ['nullable'],
            'status'        => ['required']
        ]);

        if ($data['status'] == 'Terbitkan') {
            $data['status'] = 'published';
        }
        else {
            $data['status'] = 'draft';
        }

        $data['slug'] = Str::slug($data['slug']);
        if ($data['slug'] != $post->slug) {
            $base_slug = $data['slug'];

            $counter = Post::where('slug', $data['slug'])->count();

            if ($counter > 0) {
                $loop = true;
                $i = 1;

                while ($loop) {
                    $data['slug'] = $base_slug . '-' . $i;
                    $counter = Post::where('slug', $data['slug'])->count();

                    if ($counter == 0) {
                        $loop = false;
                    }
                    else {
                        $loop = true;
                        $i++;
                    }
                }
            }
        }

        // Create snippet
        $contents = preg_replace('/\s+/', ' ', strip_tags($data['content']));
        $contents = explode(' ', $contents);
        $snippet = '';
        for ($i = 0; $i < 20; $i++) {
            if (!empty($contents[$i])) {
                $snippet .= $contents[$i] . ' ';
            }
        }
        $data['snippet'] = trim($snippet);
        // [END] Create snippet

        $post->update($data);

        // Label
        $post->post_labels()->delete();

        $labels = $request->input('labels');
        $labels = explode(',', $labels);

        foreach ($labels as $label) {
            $label = strtolower(trim($label));

            if (!empty($label)) {
                $label_id = Label::firstOrCreate(['name' => $label, 'slug' => $label]);

                PostLabel::firstOrCreate([
                    'post_id' => $post->id,
                    'label_id' => $label_id->id
                ]);
            }
        }
        // [END] Label

        return redirect()->route('posts.index')->with('success', 'Berhasil diedit');
    }

    public function destroy(Request $request, Post $post)
    {
        if (Gate::denies('access', ['posts.destroy', $post->user_id]) && Gate::denies('access', 'posts.destroy.all')) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Berhasil dihapus');
    }
}
