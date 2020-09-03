<form action="{{ route('blog.search') }}" method="GET">
	<div class="card mb-2 shadow-sm">
		<div class="card-header">
			<label for="fieldSearch" class="mb-0">
				Pencarian
			</label>
		</div>
		<div class="card-body">
			<div class="input-group">
				<input type="text" name="q" id="fieldSearch" class="form-control" value="{{ Request::get('q') }}">
				<div class="input-group-append">
					<button type="submit" class="btn btn-primary">Cari</button>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="card mb-2 shadow-sm">
	<div class="card-header">
		Post Terbaru
	</div>
	<div class="card-body p-2">
		@php
			if (!isset($posts)) {
				$recent_posts = \App\Post::published()->where('id', '<>', $post->id)->latest()->limit(5)->get();
			}
			else {
				$recent_posts = \App\Post::published()->latest()->limit(5)->get();
			}
		@endphp

		<ul class="list-unstyled mb-0">
			@foreach ($recent_posts as $recent_post)
				<li class="mb-2">
					<a href="{{ route('blog.post', ['post' => $recent_post]) }}">
						{{ $recent_post->title }}
					</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>

<div class="card mb-2 shadow-sm">
	<div class="card-header">
		Post Populer
	</div>
	<div class="card-body p-2">
		@php
			if (!isset($posts)) {
				$popular_posts = \App\Post::published()->where('id', '<>', $post->id)->orderBy('views', 'desc')->limit(5)->get();
			}
			else {
				$popular_posts = \App\Post::published()->orderBy('views', 'desc')->limit(5)->get();
			}
		@endphp

		<ul class="list-unstyled mb-0">
			@foreach ($popular_posts as $popular_post)
				<li class="mb-2">
					<a href="{{ route('blog.post', ['post' => $popular_post]) }}">
						{{ $popular_post->title }}
					</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>