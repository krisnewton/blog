@extends('apps.blog.partials.blog')

@section('page_title')
	@if ($posts->currentPage() == 1)
		{{ setting('app.name') . ' - ' . setting('app.description') }}
	@else
		Artikel Terbaru - Halaman {{ $posts->currentPage() }}
	@endif
@endsection

@section('seo')
	<script type="application/ld+json">
		{!! sd_searchbox(route('blog.search')) !!}
	</script>
	@if ($posts->currentPage() == 1)

		{!! meta_description(setting('blog.description')) !!}
		{!! og_description(setting('blog.description')) !!}
		{!! tc_description(setting('blog.description')) !!}
		{!! canonical(route('blog.home')) !!}
		{!! og_url(route('blog.home')) !!}

	@else

		{!! meta_description('Halaman ' . $posts->currentPage() . ' - ' . setting('blog.description')) !!}
		{!! og_description('Halaman ' . $posts->currentPage() . ' - ' . setting('blog.description')) !!}
		{!! tc_description('Halaman ' . $posts->currentPage() . ' - ' . setting('blog.description')) !!}
		{!! canonical(route('blog.home', ['page' => $posts->currentPage()])) !!}
		{!! og_url(route('blog.home', ['page' => $posts->currentPage()])) !!}

	@endif

	@if ($posts->hasPages())
		@if ($posts->hasMorePages())
			{!! nextpage(route('blog.home', ['page' => ($posts->currentPage() + 1)])) !!}
		@endif

		@if ($posts->currentPage() == 2)
			{!! prevpage(route('blog.home')) !!}
		@elseif ($posts->currentPage() > 2)
			{!! prevpage(route('blog.home', ['page' => ($posts->currentPage() - 1)])) !!}
		@endif
	@endif

	<!-- Open Graph -->
	@if ($posts->currentPage() == 1)
		{!! og_title(setting('app.name') . ' - ' . setting('app.description')) !!}
		{!! tc_title(setting('app.name') . ' - ' . setting('app.description')) !!}
	@else
		{!! og_title(setting('app.name') . ' - Artikel Terbaru - Halaman ' . $posts->currentPage()) !!}
		{!! tc_title(setting('app.name') . ' - Artikel Terbaru - Halaman ' . $posts->currentPage()) !!}
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
	<div class="container">
		<div class="form-row">
			<div class="col-12 col-lg-8">
				<h1 class="h3">Artikel Terbaru</h1>

				@foreach ($posts as $post)
					<x-blog-post :post="$post"/>
				@endforeach

				<div class="d-flex justify-content-center">
					{{ $posts->withPath('blog')->links() }}
				</div>

			</div>
			<div class="col-12 col-lg-4">
				@include('apps.blog.partials.sidebar')
			</div>
		</div>
	</div>
@endsection