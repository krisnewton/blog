@extends('apps.blog.partials.blog')

@section('page_title')
	@if ($posts->currentPage() == 1)
		Hasil Pencarian - {{ Request::get('q') }}
	@else
		Hasil Pencarian - {{ Request::get('q') }} - Halaman {{ $posts->currentPage() }}
	@endif
@endsection

@section('seo')
	@if ($posts->currentPage() == 1)

		{!! meta_description('Hasil pencarian artikel dengan kata kunci ' . Request::get('q')) !!}
		{!! og_description('Hasil pencarian artikel dengan kata kunci ' . Request::get('q')) !!}
		{!! tc_description('Hasil pencarian artikel dengan kata kunci ' . Request::get('q')) !!}
		{!! canonical(route('blog.search', ['q' => Request::get('q')])) !!}
		{!! og_url(route('blog.search', ['q' => Request::get('q')])) !!}

	@else

		{!! meta_description('Halaman ' . $posts->currentPage() . ' - ' . 'Hasil pencarian artikel dengan kata kunci ' . Request::get('q')) !!}
		{!! og_description('Halaman ' . $posts->currentPage() . ' - ' . 'Hasil pencarian artikel dengan kata kunci ' . Request::get('q')) !!}
		{!! tc_description('Halaman ' . $posts->currentPage() . ' - ' . 'Hasil pencarian artikel dengan kata kunci ' . Request::get('q')) !!}
		{!! canonical(route('blog.search', ['q' => Request::get('q'), 'page' => $posts->currentPage()])) !!}
		{!! og_url(route('blog.search', ['q' => Request::get('q'), 'page' => $posts->currentPage()])) !!}

	@endif

	@if ($posts->hasPages())
		@if ($posts->hasMorePages())
			{!! nextpage(route('blog.search', ['q' => Request::get('q'), 'page' => ($posts->currentPage() + 1)])) !!}
		@endif

		@if ($posts->currentPage() == 2)
			{!! prevpage(route('blog.search', ['q' => Request::get('q')])) !!}
		@elseif ($posts->currentPage() > 2)
			{!! prevpage(route('blog.search', ['q' => Request::get('q'), 'page' => ($posts->currentPage() - 1)])) !!}
		@endif
	@endif

	<!-- Open Graph -->
	@if ($posts->currentPage() == 1)
		{!! og_title('Hasil Pencarian - ' . Request::get('q')) !!}
		{!! tc_title('Hasil Pencarian - ' . Request::get('q')) !!}
	@else
		{!! og_title('Hasil Pencarian - ' . Request::get('q') . ' - Halaman ' . $posts->currentPage()) !!}
		{!! tc_title('Hasil Pencarian - ' . Request::get('q') . ' - Halaman ' . $posts->currentPage()) !!}
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
				<h1 class="h3">Hasil Pencarian - {{ Request::get('q') }}</h1>

				@if (count($posts) > 0)
					@foreach ($posts as $post)
						<x-blog-post :post="$post"/>
					@endforeach
					<div class="d-flex justify-content-center">
						{{ $posts->appends(['q' => Request::get('q')])->links() }}
					</div>
				@else
					<p>
						Tidak ditemukan hasil
					</p>
				@endif

			</div>
			<div class="col-12 col-lg-4">
				@include('apps.blog.partials.sidebar')
			</div>
		</div>
	</div>
@endsection