@csrf
<x-form-group-text label="Nama" name="name" id="field_name" :value="$category ? $category->name : old('name')" :message="$errors->first('name')"/>
<x-form-group-textarea label="Deskripsi" name="description" id="field_description" :value="$category ? $category->description : old('description')" :message="$errors->first('description')"/>
@if (!empty($category))
	<div class="form-group">
		<label for="fieldSlug">Slug</label>
		<input type="text" value="{{ $category->slug }}" name="slug" id="fieldSlug" class="form-control" disabled>
	</div>
	<div class="form-group">
		<div class="custom-control custom-checkbox">
			<input type="checkbox" name="update_slug" class="custom-control-input" id="checkboxUpdateSlug" checked>
			<label class="custom-control-label" for="checkboxUpdateSlug">Update slug sesuai nama kategori</label>
		</div>
	</div>
@endif
<button type="submit" class="btn btn-primary">Simpan</button>