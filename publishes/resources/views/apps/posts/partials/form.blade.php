<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="createPost">
	@csrf

	@if (!empty($post))
		@method('PUT')
	@endif

	<div class="form-row justify-content-center">
		<div class="col-12 col-lg-8">
			<div class="card shadow mb-2">

				<!-- Konten Utama -->
				<div class="card-body">
					@error ('thumbnail')
						<x-alert type="danger">
							Post harus memiliki gambar Cover
						</x-alert>
					@enderror

					<x-form-group-text label="Judul" name="title" id="field_title" :value="$post ? $post->title : old('title')" :message="$errors->first('title')"/>
					<x-rich-text-editor label="Konten" name="content" id="field_content" :value="$post ? $post->content : old('content')" :message="$errors->first('content')"/>
				</div>
				<!-- [END] Konten Utama -->

			</div>
		</div>
		<div class="col-12 col-lg-4">

			<!-- Simpan -->
			<div class="card shadow mb-2">
				<div class="card-body">
					<input type="submit" name="status" value="Terbitkan" class="btn btn-primary btn-block">
					<input type="submit" name="status" value="Draft" class="btn btn-outline-primary btn-block">
				</div>
			</div>
			<!-- Simpan -->

			<!-- Info Tambahan -->
			<div class="card shadow mb-2">
				<div class="card-header">
					<ul class="nav nav-pills card-header-pills">
						<li class="nav-item">
							<a class="nav-link" id="pillSEOTab" data-toggle="pill" href="#pillSEO" role="tab" aria-controls="pillSEO" aria-selected="false">SEO</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" id="pillCoverTab" data-toggle="pill" href="#pillCover" role="tab" aria-controls="pillCover" aria-selected="true">Post</a>
						</li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content" id="navTabContent">

						<!-- SEO -->
						<div class="tab-pane fade" id="pillSEO" role="tabpanel" aria-labelledby="pillSEOTab">
							<x-form-group-text label="Judul SEO" name="seo_title" id="field_seo_title" :value="$post ? $post->seo_title : old('seo_title')" :message="$errors->first('seo_title')"/>
							<x-form-group-text label="URL" name="slug" id="field_slug" :value="$post ? $post->slug : old('slug')" :message="$errors->first('slug')"/>
							<x-form-group-textarea label="Meta Deskripsi" name="excerpt" id="field_excerpt" :value="$post ? $post->excerpt : old('excerpt')" :message="$errors->first('excerpt')"/>
						</div>
						<!-- [END] SEO -->

						<!-- Cover -->
						<div class="tab-pane fade show active" id="pillCover" role="tabpanel" aria-labelledby="pillCoverTab">
							<x-form-group-select label="Kategori" name="category_id" id="fieldCategoryId" :value="$post ? $post->category_id : old('category_id')" :message="$errors->first('category_id')" :options="$categories"/>

							<div class="form-group">
								<label for="fieldLabels">Label</label>
								<input type="text" name="labels" id="fieldLabels" value="{{ $post ? $post->get_labels() : old('labels') }}" class="form-control">
								<small class="form-text text-muted">
									Pisahkan dengan koma
								</small>
							</div>

							<div>
								<label>Gambar Cover</label>
								<button class="btn btn-outline-primary btn-block" type="button" data-toggle="modal" data-target="#uploadCoverModal">Upload Cover</button>
							</div>

							@if (empty($post->cover))
								<div id="coverImageContainer" class="text-center mt-2 {{ old('cover') ? 'd-block' : 'd-none' }}">
									<img id="coverImage" class="img-fluid rounded-lg" alt="Placeholder" src="{{ old('cover') ? old('cover') : 'https://picsum.photos/100/100' }}" loading="lazy">
								</div>
							@else
								<div id="coverImageContainer" class="text-center mt-2">
									<img id="coverImage" class="img-fluid rounded-lg" alt="Placeholder" src="{{ $post->cover }}">
								</div>
							@endif

							<input type="hidden" name="cover" id="fieldCover" value="{{ $post ? $post->cover : old('cover') }}">
							<input type="hidden" name="thumbnail" id="fieldThumbnail" value="{{ $post ? $post->thumbnail : old('thumbnail') }}">
						</div>
						<!-- [END] Cover -->

					</div>
				</div>
			</div>
			<!-- [END] Info Tambahan -->
		</div>
	</div>
</form>

<x-modal id="uploadCoverModal" title="Upload Cover">
	<div class="modal-body">
		<form method="POST" action="{{ route('upload_image') }}" id="uploadCoverForm" enctype="multipart/form-data">
			@csrf
			<x-file label="Gambar" name="image" id="fieldCoverImage"/>
			<button type="submit" class="btn btn-primary">Simpan</button>
		</form>
	</div>
</x-modal>

@push('scripts')
	<script>
		$(document).ready(function () {
			var fieldCoverImage = $("#fieldCoverImage");

			var fieldCover = $("#fieldCover");
			var fieldThumbnail = $("#fieldThumbnail");

			var coverImageContainer = $("#coverImageContainer");
			var coverImage = $("#coverImage");

			var resetUploadCoverForm = function () {
				fieldCoverImage.removeClass("is-invalid");
				$(".invalid-feedback").remove();
			};

			$("#uploadCoverForm").submit(function (event) {
				event.preventDefault();

				// Reset form Upload Cover
				resetUploadCoverForm();

				$.ajax({
					url 		: "{{ route('upload_image') }}",
					method		: "POST",
					data 		: new FormData(this),
					dataType	: "JSON",
					contentType	: false,
					cache		: false,
					processData	: false,
					success		: function (data) {

						coverImageContainer.removeClass("d-none");
						coverImage.attr("src", data.image);

						fieldCover.val(data.image);
						fieldThumbnail.val(data.image_thumbnail);

						$("#uploadCoverModal").modal("hide");

						// Reset form Upload Cover
						resetUploadCoverForm();

						fieldCoverImage.val("");

						$("label[for='fieldCoverImage']").text("Pilih file");
						// [END] Reset form Upload Cover

					},
					error 		: function (data) {

						var res = data.responseJSON;
						var msg;

						// image error
						if (res.errors.image) {
							fieldCoverImage.addClass("is-invalid");
							msg = '<span class="invalid-feedback">' + res.errors.image[0] + '</span>';
							fieldCoverImage.after(msg);
						}

					}
				});
			}); // Upload Cover Image
		});
	</script>
@endpush