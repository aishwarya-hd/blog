@extends('main')

@section('title', '| Edit Blog Post')

@section('stylesheets')
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
  <script>
tinymce.init({
  selector: 'textarea',
  plugins: 'link',
  menubar: false
});
  </script>

@section('content')

<div class="row">
{!! Form::model($post, ['route' => ['posts.update', $post->id], 'method' => 'PUT', 'files' => true]) !!}
	<div class="col-md-8">
{{ Form::label('title', 'Title:') }}
{{ Form::text('title', null, ['class' => 'form-control input-lg']) }}
{{ Form::label('slug', 'Slug:', ['class' => 'form-spacing-top']) }}
{{ Form::text('slug', null, ['class' => 'form-control']) }}

{{ Form::label('featured_image', 'Update Featured Image:', ['class' => 'form-spacing-top']) }}
{{ Form::file('featured_image') }}
{{ Form::label('body', 'Body:', ['class' => 'form-spacing-top']) }}
{{ Form::textarea('body', null, ['class' => 'form-control']) }}
</div>
<div class="col-md-4">
    <div class="well">
	<dl class="dl-horizontal">
		<dt>Created at:</dt>
		<dd>{{ date('M j ,Y h:ia', strtotime($post-> created_at)) }}</dd>
	</dl>
	<dl class="dl-horizontal">
		<dt>Last updated:</dt>
		<dd>{{ date('M j ,Y h:ia', strtotime($post->updated_at)) }}</dd>
	</dl>
	<hr>
	    <div class="row">
		    <div class="col-sm-6">
		    	{!! Html::linkRoute('posts.show', 'Cancel', array($post->id), array('class' => 'btn btn-danger btn-block')) !!}
		    </div>
		    <div class="col-sm-6">
		    	{{ Form::submit('Save changes', ['class' => 'btn btn-success btn-block']) }}
		    </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
</div>
@endsection