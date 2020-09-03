@extends('apps.blog.partials.blog')

@section('page_title')
	@if ($posts->currentPage() == 1)
		Daftar Artikel dengan Topik {{ ucfirst($label->name) }}
	@else
		Daftar Artikel dengan Topik {{ ucfirst($label->name) }} - Halaman {{ $posts->currentPage() }}
	@endif
@endsection

@section('seo')
	<script type="application/ld+json">
		{!! sd_breadcrumb($breadcrumb) !!}
	</script>
	@if ($posts->currentPage() == 1)

		{!! meta_description('Daftar artikel dengan topik ' . ucfirst($label->name)) !!}
		{!! og_description('Daftar artikel dengan topik ' . ucfirst($label->name)) !!}
		{!! tc_description('Daftar artikel dengan topik ' . ucfirst($label->name)) !!}
		{!! canonical(route('blog.label', ['label' => $label])) !!}
		{!! og_url(route('blog.label', ['label' => $label])) !!}

	@else

		{!! meta_description('Halaman ' . $posts->currentPage() . ' - ' . 'Daftar artikel dengan topik ' . ucfirst($label->name)) !!}
		{!! og_description('Halaman ' . $posts->currentPage() . ' - ' . 'Daftar artikel dengan topik ' . ucfirst($label->name)) !!}
		{!! tc_description('Halaman ' . $posts->currentPage() . ' - ' . 'Daftar artikel dengan topik ' . ucfirst($label->name)) !!}
		{!! canonical(route('blog.label', ['label' => $label, 'page' => $posts->currentPage()])) !!}
		{!! og_url(route('blog.label', ['label' => $label, 'page' => $posts->currentPage()])) !!}

	@endif

	@if ($posts->hasPages())
		@if ($posts->hasMorePages())
			{!! nextpage(route('blog.label', ['label' => $label, 'page' => ($posts->currentPage() + 1)])) !!}
		@endif

		@if ($posts->currentPage() == 2)
			{!! prevpage(route('blog.label', ['label' => $label])) !!}
		@elseif ($posts->currentPage() > 2)
			{!! prevpage(route('blog.label', ['label' => $label, 'page' => ($posts->currentPage() - 1)])) !!}
		@endif
	@endif

	<!-- Open Graph -->
	@if ($posts->currentPage() == 1)
		{!! og_title('Daftar Artikel dengan Topik ' . ucfirst($label->name)) !!}
		{!! tc_title('Daftar Artikel dengan Topik ' . ucfirst($label->name)) !!}
	@else
		{!! og_title('Daftar Artikel dengan Topik ' . ucfirst($label->name) . ' - Halaman ' . $posts->currentPage()) !!}
		{!! tc_title('Daftar Artikel dengan Topik ' . ucfirst($label->name) . ' - Halaman ' . $posts->currentPage()) !!}
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
				<h1 class="h3">{{ ucfirst($label->name) }}</h1>

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