<div class="card mb-2 shadow-sm">
	<div class="card-body p-2">
		<div class="form-row">
			<div class="col-12 col-sm-4">
				<a href="{{ route('blog.post', ['post' => $post]) }}">
					<img src="{{ $post->thumbnail }}" class="img-fluid w-100 rounded" alt="{{ $post->title }}" loading="lazy">
				</a>
			</div>
			<div class="col-12 col-sm-8">
				<h2 class="h5 mt-2 mb-2 limit-2-lines">
					<a href="{{ route('blog.post', ['post' => $post]) }}" title="{{ $post->title }}" class="text-dark">{{ $post->title }}</a>
				</h2>
				<p class="card-text mb-0 limit-2-lines">
					{!! $post->snippet !!}
				</p>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<small>
			Kategori: <a href="{{ route('blog.category', ['category' => $post->category]) }}">{{ $post->category->name }}</a> – 
			Oleh {{ $post->user->name }} – 
			{{ $post->created_at->diffForHumans() }}
		</small>
	</div>
</div>