<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Session;
use App\Post;
class PagesController extends Controller {
	public function getIndex() {
		$posts = Post::orderBy('created_at', 'desc')->limit(4)->get();
		return view('pages.welcome')->withPosts($posts); 
	}
	public function getAbout() {
		$first = 'Aishwarya';
		$last = 'HD';

		$fullname = $first . " " . $last;
		$email = 'aishwarya@gmail.com';
		$data = [];
		$data['email'] = $email;
		$data['fullname'] = $fullname;
return view('pages.about')->withData($data);
	}
	public function getContact() {
           return view('pages.contact');
	}
	public function getLogout() {
		Auth::logout();
		Session::flush();
		return Redirect::to('/');
	}
}