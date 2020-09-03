@extends('layouts.app')

@section('page_title', 'Buat Kategori')

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<x-card-large>
		<x-title>Buat Kategori</x-title>

		<form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
			@include('apps.categories.partials.form', ['category' => ''])
		</form>
	</x-card-large>
@endsection