@extends('apps.blog.partials.blog')

@section('page_title')
	@if ($posts->currentPage() == 1)
		Daftar Artikel - {{ $category->name }}
	@else
		Daftar Artikel - {{ $category->name }} - Halaman {{ $posts->currentPage() }}
	@endif
@endsection

@section('seo')
	<script type="application/ld+json">
		{!! sd_breadcrumb($breadcrumb) !!}
	</script>
	@if ($posts->currentPage() == 1)

		@if (!empty($category->description))
			{!! meta_description($category->description) !!}
			{!! og_description($category->description) !!}
			{!! tc_description($category->description) !!}
		@else
			{!! meta_description('Daftar artikel dengan kategori ' . $category->name) !!}
			{!! og_description('Daftar artikel dengan kategori ' . $category->name) !!}
			{!! tc_description('Daftar artikel dengan kategori ' . $category->name) !!}
		@endif

		{!! canonical(route('blog.category', ['category' => $category])) !!}
		{!! og_url(route('blog.category', ['category' => $category])) !!}

	@else

		@if (!empty($category->description))
			{!! meta_description('Halaman ' . $posts->currentPage() . ' - ' . $category->description) !!}
			{!! og_description('Halaman ' . $posts->currentPage() . ' - ' . $category->description) !!}
			{!! tc_description('Halaman ' . $posts->currentPage() . ' - ' . $category->description) !!}
		@else
			{!! meta_description('Halaman ' . $posts->currentPage() . ' - ' . 'Daftar artikel dengan kategori ' . $category->name) !!}
			{!! og_description('Halaman ' . $posts->currentPage() . ' - ' . 'Daftar artikel dengan kategori ' . $category->name) !!}
			{!! tc_description('Halaman ' . $posts->currentPage() . ' - ' . 'Daftar artikel dengan kategori ' . $category->name) !!}
		@endif

		{!! canonical(route('blog.category', ['category' => $category, 'page' => $posts->currentPage()])) !!}
		{!! og_url(route('blog.category', ['category' => $category, 'page' => $posts->currentPage()])) !!}

	@endif

	@if ($posts->hasPages())
		@if ($posts->hasMorePages())
			{!! nextpage(route('blog.category', ['category' => $category, 'page' => ($posts->currentPage() + 1)])) !!}
		@endif

		@if ($posts->currentPage() == 2)
			{!! prevpage(route('blog.category', ['category' => $category])) !!}
		@elseif ($posts->currentPage() > 2)
			{!! prevpage(route('blog.category', ['category' => $category, 'page' => ($posts->currentPage() - 1)])) !!}
		@endif
	@endif

	<!-- Open Graph -->
	@if ($posts->currentPage() == 1)
		{!! og_title('Daftar Artikel - ' . $category->name) !!}
		{!! tc_title('Daftar Artikel - ' . $category->name) !!}
	@else
		{!! og_title('Daftar Artikel - ' . $category->name . ' - Halaman ' . $posts->currentPage()) !!}
		{!! tc_title('Daftar Artikel - ' . $category->name . ' - Halaman ' . $posts->currentPage()) !!}
	@endif
	{!! og_type() !!}
	@if (!empty(setting('blog.image')))
		{!! og_image(setting('blog.image')) !!}
	@endif
	{!! og_locale() !!}
	{!! og_site_name() !!}

	<!-- Twitter Card -->
	{!! tc_card() !!}
	@if (!empty(setting('blog.twitter_image')))
		{!! tc_image(setting('blog.twitter_image')) !!}
	@endif
@endsection

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<div class="container">
		<div class="form-row">
			<div class="col-12 col-lg-8">
				<h1 class="h3">{{ $category->name }}</h1>
				@if (!empty($category->description))
					<p>
						{{ $category->description }}
					</p>
				@endif

				@foreach ($posts as $post)
					<x-blog-post :post="$post"/>
				@endforeach
				<div class="d-flex justify-content-center">
					{{ $posts->links() }}
				</div>

			</div>
			<div class="col-12 col-lg-4">
				@include('apps.blog.partials.sidebar')
			</div>
		</div>
	</div>
@endsection