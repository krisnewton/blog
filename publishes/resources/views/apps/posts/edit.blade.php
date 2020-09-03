@extends('layouts.app')

@section('page_title', 'Edit Post')

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<div class="container">
		<h1 class="h3">Edit Post</h1>
		@include('apps.posts.partials.form', ['post' => $post, 'action' => route('posts.update', ['post' => $post])])
	</div>
@endsection