<?php

Post::created(function($post) {

	//Build user array

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$user['agent']  = $_SERVER['HTTP_USER_AGENT'];
	}
	else {
		$user['agent']  = '';
	}
	
	if (Request::header('X-Forwarded-For') != NULL) {
		$user['ip'] = Request::header('X-Forwarded-For');
	}
	else {
		$user['ip'] = Request::getClientIp();
	}
	if (isset($_SERVER['HTTP_REFERER'])) {
		$user['referrer'] = $_SERVER['HTTP_REFERER'];
	}
	else {
		$user['referrer'] = '';
	}

	$check = akismet_post($post, $user);

	if($check == 'true') {
		$post->is_queued = true;
		$post->akismet = true;
	}

	$post->save();

});

Thread::saved(function($thread) {

	//Have to do this on-save because post id is only added to the thread once a post is created.
	//Meaning, the head cannot be retrieved on-create.

	if(!$thread->is_queued) {
		$head = $thread->head();
		if ($head && $head->is_queued) {
			$thread->is_queued = true;
			$thread->save();
		}

		//check bamwar and bisbury

		$bamwar = bamwar_filter($thread->name);
		$bisbury = bisbury_filter($thread->name);

		if (($bamwar || $bisbury) && $head) {
			$head->is_queued = true;
			$thread->is_queued = true;

			$head->save();
			$thread->save();
		}

	}

});