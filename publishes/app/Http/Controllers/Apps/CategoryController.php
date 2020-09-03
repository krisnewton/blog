<?php

namespace App\Http\Controllers\Apps;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grafika\Grafika;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Post;

class CategoryController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
	}

    public function index()
    {
    	$this->authorize('access', 'categories.index');

        $breadcrumb = [
        	'Kategori' => ''
        ];

        $categories = Category::real()->get();

        return view('apps.categories.index', compact('breadcrumb', 'categories'));
    }

    public function create()
    {
    	$this->authorize('access', 'categories.create');

        $breadcrumb = [
        	'Kategori' => route('categories.index'),
        	'Tambah Kategori' => ''
        ];

        return view('apps.categories.create', compact('breadcrumb'));
    }

    public function store(Request $request)
    {
    	$this->authorize('access', 'categories.create');

        $data = $request->validate([
        	'name'         => ['required', 'max:32', 'unique:categories,name'],
            'description'  => ['nullable'],
        ]);
        $data['slug'] = Str::slug($data['name'], '-');

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
    	$this->authorize('access', 'categories.edit');

        if ($category->id == 1) {
            abort(404);
        }

        $breadcrumb = [
        	'Kategori' => route('categories.index'),
        	'Edit Kategori' => ''
        ];

        return view('apps.categories.edit', compact('breadcrumb', 'category'));
    }

    public function update(Request $request, Category $category)
    {
    	$this->authorize('access', 'categories.edit');

        if ($category->id == 1) {
            abort(404);
        }

        $data = $request->validate([
        	'name' => ['required', 'max:32', 'unique:categories,name,' . $category->id], 
            'description' => ['nullable'], 
        ]);

        if ($request->input('update_slug')) {
            $data['slug'] = Str::slug($data['name'], '-');
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Berhasil diedit');
    }

    public function destroy(Request $request, Category $category)
    {
    	$this->authorize('access', 'categories.destroy');

        $category_id = $category->id;

        if ($category_id == 1) {
            $message = 'Tidak dapat dihapus';

            return redirect()->route('categories.index')->with('danger', $message);
        }
        else {
            Post::where('category_id', $category_id)->update(['category_id' => 1]);

            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Berhasil dihapus');
        }
    }
}
