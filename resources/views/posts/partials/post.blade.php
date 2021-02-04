@if($loop->even)
	<h1> {{ $key }}. {{ $post['title'] }}</h1>
@else
	<h1 style="color: red;"> {{ $key }}. {{ $post['title'] }}</h1>
@endif