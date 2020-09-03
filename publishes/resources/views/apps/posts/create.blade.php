@extends('layouts.app')

@section('page_title', 'Buat Post')

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>

	<div class="container">
		<h1 class="h3">Buat Post</h1>
		@include('apps.posts.partials.form', ['post' => '', 'action' => route('posts.store')])
	</div>
@endsection