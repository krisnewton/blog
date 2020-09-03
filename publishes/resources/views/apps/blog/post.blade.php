@extends('apps.blog.partials.blog')

@section('page_title')
	@if (!empty($post->seo_title))
		{{ $post->seo_title }}
	@else
		{{ $post->title }}
	@endif
@endsection

@section('seo')
	<script type="application/ld+json">
		{!! sd_breadcrumb($breadcrumb) !!}
	</script>
	@if (empty(trim($post->excerpt)))
		{!! meta_description($post->snippet) !!}
		{!! og_description($post->snippet) !!}
		{!! tc_description($post->snippet) !!}
	@else
		{!! meta_description($post->excerpt) !!}
		{!! og_description($post->excerpt) !!}
		{!! tc_description($post->excerpt) !!}
	@endif

	{!! canonical(route('blog.post', ['post' => $post])) !!}

	<!-- Open Graph -->
	{!! og_title($post->title) !!}
	{!! og_type('article') !!}
	<meta property="article:published_time" content="{{ $post->created_at->format('Y-m-d') }}">
	<meta property="article:section" content="{{ $post->category->name }}">
	@if (!empty($post->user->facebook))
		<meta property="article:author" content="https://www.facebook.com/{{ $post->user->facebook }}">
	@else
		<meta property="article:author" content="{{ route('profile.show', ['user' => $post->user]) }}">
	@endif
	{!! og_url(route('blog.post', ['post' => $post])) !!}
	{!! og_image($post->cover) !!}
	{!! og_locale() !!}
	{!! og_site_name() !!}

	<!-- Twitter Card -->
	{!! tc_card() !!}
	{!! tc_image(str_replace('thumbnails', 'squares', $post->thumbnail)) !!}
@endsection

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<div class="container">
		<div class="form-row">
			<div class="col-12 col-lg-8">
				<h1 class="h3 mb-0 text-center">
					{{ $post->title }}
				</h1>

				<hr>
				<p class="text-muted text-center">
					<small>
						Oleh {{ $post->user->name }} – 
						{{ $post->created_at->diffForHumans() }}
					</small>
				</p>

				<hr>
				<div>
					<img src="{{ $post->cover }}" class="img-fluid w-100" alt="{{ $post->title }}" loading="lazy">
				</div>
				<hr>

				<div>
					{!! $post->content !!}
				</div>

				<hr>
				<h4 class="h6">Bagikan:</h4>
				<div class="mb-2">
					<a href="{{ sharer_facebook(route('blog.post', ['post' => $post])) }}" class="btn btn-outline-secondary btn-sm mb-2" target="_blank" rel="nofollow noopener">
						<span class="fab fa-facebook fa-fw"></span>
						Facebook
					</a>
					<a href="{{ sharer_twitter(route('blog.post', ['post' => $post])) }}" class="btn btn-outline-secondary btn-sm mb-2" target="_blank" rel="nofollow noopener">
						<span class="fab fa-twitter fa-fw"></span>
						Twitter
					</a>
					<a href="{{ sharer_whatsapp(route('blog.post', ['post' => $post])) }}" class="btn btn-outline-secondary btn-sm mb-2" target="_blank" rel="nofollow noopener">
						<span class="fab fa-whatsapp fa-fw"></span>
						WhatsApp
					</a>
					<a href="{{ sharer_telegram(route('blog.post', ['post' => $post])) }}" class="btn btn-outline-secondary btn-sm mb-2" target="_blank" rel="nofollow noopener">
						<span class="fab fa-telegram-plane fa-fw"></span>
						Telegram
					</a>
					<button class="btn btn-outline-secondary btn-sm mb-2" id="copyButton" data-clipboard-text="{{ route('blog.post', ['post' => $post]) }}">
						<span class="fas fa-link fa-fw"></span>
						Salin Link
					</button>
				</div>

				@if (count($related_posts) > 0)
					<hr>
					<h4 class="h6">Artikel Terkait:</h4>
					<div class="mb-2">
						<ul class="list-unstyled">
							@foreach ($related_posts as $related_post)
								<li class="mb-2">
									<a href="{{ route('blog.post', ['post' => $related_post]) }}">{{ $related_post->title }}</a>
								</li>
							@endforeach
						</ul>
					</div>
				@endif

				@if (count($post->labels) > 0)
					<hr>
					<h4 class="h6">Topik Terkait:</h4>
					<div class="mb-2">
						@foreach ($post->labels as $label)
							<a href="{{ route('blog.label', ['label' => $label]) }}" class="btn btn-sm btn-outline-info mb-2">{{ $label->name }}</a>
						@endforeach
					</div>
				@endif
			</div>
			<div class="col-12 col-lg-4">
				@include('apps.blog.partials.sidebar')
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('vendor/clipboardjs/clipboard.min.js') }}"></script>
	<script>
		$(document).ready(function () {
			var clipboard = new ClipboardJS("#copyButton");
			clipboard.on("success", function () {
				alert("Berhasil disalin");
			});
		});
	</script>
@endpush