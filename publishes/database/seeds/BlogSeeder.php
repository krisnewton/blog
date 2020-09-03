<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Access Post
        $id = DB::table('access_groups')->insertGetId(
        	['name' => 'Post']
        );

        DB::table('accesses')->insert([
            [
                'access_group_id'   => $id,
                'name'              => 'Melihat Daftar Post',
                'code'              => 'posts.index'
            ],
            [
                'access_group_id'   => $id,
                'name'              => 'Buat Post',
                'code'              => 'posts.create'
            ],
            [
                'access_group_id'   => $id,
                'name'              => 'Edit Post',
                'code'              => 'posts.edit'
            ],
            [
                'access_group_id'   => $id,
                'name'              => 'Hapus Post',
                'code'              => 'posts.destroy'
            ]
        ]);

        // Access Post Editor
        $id = DB::table('access_groups')->insertGetId(
        	['name' => 'Post Editor']
        );

        DB::table('accesses')->insert([
            [
                'access_group_id'   => 5,
                'name'              => 'Melihat Daftar Post Semua Pengguna',
                'code'              => 'posts.index.all'
            ],
            [
                'access_group_id'   => 5,
                'name'              => 'Edit Post Semua Pengguna',
                'code'              => 'posts.edit.all'
            ],
            [
                'access_group_id'   => 5,
                'name'              => 'Hapus Post Semua Pengguna',
                'code'              => 'posts.destroy.all'
            ]
        ]);

        // Category
        $id = DB::table('access_groups')->insertGetId(
            ['name' => 'Kategori']
        );

        DB::table('accesses')->insert([
            [
                'access_group_id'   => $id,
                'name'              => 'Melihat Daftar Kategori',
                'code'              => 'categories.index'
            ],
            [
                'access_group_id'   => $id,
                'name'              => 'Buat Kategori',
                'code'              => 'categories.create'
            ],
            [
                'access_group_id'   => $id,
                'name'              => 'Edit Kategori',
                'code'              => 'categories.edit'
            ],
            [
                'access_group_id'   => $id,
                'name'              => 'Hapus Kategori',
                'code'              => 'categories.destroy'
            ]
        ]);

        // Kategori
        DB::table('categories')->insert([
            [
                'name'          => 'Tidak Berkategori',
                'slug'          => 'tidak-berkategori',
                'description'   => ''
            ]
        ]);

        // Pengaturan
        $id = DB::table('settings')->insertGetId([
            'name'      => 'Blog',
            'slug'      => 'blog',
            'position'  => 100
        ]);

        $extra = [
            'type' => 'text',
            'validation' => ['required', 'numeric']
        ];
        $extra = json_encode($extra);

        DB::table('setting_values')->insert([
            'setting_id'    => $id,
            'code'          => 'blog.items_per_page',
            'form_name'     => 'blog_items_per_page',
            'name'          => 'Item artikel per halaman',
            'value'         => 5,
            'position'      => 1,
            'extra'         => $extra
        ]);

        $extra = [
            'type' => 'textarea',
            'validation' => ['required']
        ];
        $extra = json_encode($extra);

        DB::table('setting_values')->insert([
            'setting_id'    => $id,
            'code'          => 'blog.description',
            'form_name'     => 'blog_description',
            'name'          => 'Deskripsi blog',
            'value'         => 'Hello, World!',
            'position'      => 2,
            'extra'         => $extra
        ]);

        DB::table('setting_values')->insert([
            'setting_id'    => $id,
            'code'          => 'blog.image',
            'form_name'     => 'blog_image',
            'name'          => 'Gambar blog',
            'value'         => '',
            'position'      => 3,
            'extra'         => json_encode([
                'type' => 'image'
            ])
        ]);

        DB::table('setting_values')->insert([
            'setting_id'    => $id,
            'code'          => 'blog.twitter_image',
            'form_name'     => 'blog_twitter_image',
            'name'          => 'Gambar blog untuk Twitter',
            'value'         => '',
            'position'      => 4,
            'extra'         => json_encode([
                'type' => 'image', 
                'edit' => [
                    'resize' => [300, 300]
                ]
            ])
        ]);
    }
}
