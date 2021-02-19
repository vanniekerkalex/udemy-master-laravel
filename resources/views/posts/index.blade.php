@extends('layouts.app')

@section('title', 'All Post Titles')

@section('content')

{{-- @each('posts.partials.post', $posts, 'post') --}}

	@forelse ($posts as $key => $post)

		@include('posts.partials.post')
		
		@empty
			<p>No posts found!</p>

	@endforelse
@endsection