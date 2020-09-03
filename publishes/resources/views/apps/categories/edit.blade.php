@extends('layouts.app')

@section('page_title', 'Edit Kategori')

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<x-card-large>
		<x-title>Edit Kategori</x-title>

		<form action="{{ route('categories.update', ['category' => $category]) }}" method="POST" enctype="multipart/form-data">
			@method('PUT')
			@include('apps.categories.partials.form', ['category' => $category])
		</form>
	</x-card-large>
@endsection