@extends('master')
@section('content')
{{ Breadcrumbs::addCrumb('Home', '/') }}
{{ Breadcrumbs::addCrumb(e($forum->name), $forum->permalink()) }}
{{ Breadcrumbs::addCrumb('Create a thread') }}
	<div class="row">
		<div class="col-lg-12 create-thread-container">
			<h1>Create a Thread</h1>
			{{ Form::open(array('url' => '/thread/create')) }}
			 <input type="hidden" value="{{ $forum->id }}" name="forum_id">
				@if(in_array($forum->id, Config::get('app.funding_forums')))
				<h2>Funding Options</h2>
				<div class="form-group">
					<input type="number" class="form-control" name="target" placeholder="The target goal" value="{{ Input::old('target') }}">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="currency" placeholder="The currency code" value="{{ Input::old('currency') }}">
				</div>
				<h2>Thread Options</h2>
				@endif
			    <div class="form-group">
				    <input type="text" class="form-control" name="name" placeholder="Your descriptive thread title goes here." value="{{ Input::old('name') }}">
			    </div>
				<div class="row">
					<p class="col-lg-12">
						For post formatting please use Markdown, <a href="http://kramdown.gettalong.org/syntax.html">click here</a> for a syntax guide.
					</p>
				</div>
			  <div class="form-group">
			    <textarea id="content-body" name="body" class="form-control markdown-editor" rows="10" placeholder="Anything you want to say in your thread should be here.">{{ Input::old('body') }}</textarea>
			  </div>
			  <button name="submit" type="submit" class="btn btn-success">Create Thread</button>
			  <button name="preview" class="btn btn-success preview-button non-js">Preview</button>
			  <a href="{{ $forum->permalink() }}"><button type="button" class="btn btn-danger">Back</button></a>
			  
			{{ Form::close() }}
		</div>
	</div>
	@if (Session::has('preview'))
	<div class="row content-preview">
		<div class="col-lg-12 preview-window">
		{{ Session::get('preview') }}
		</div>
	@else
	<div class="row content-preview" style="display: none">
		<div class="col-lg-12 preview-window">
			Hey, whenever you type something in the upper box using markdown, you will see a preview of it over here!
		</div>
	@endif
	</div>
@stop