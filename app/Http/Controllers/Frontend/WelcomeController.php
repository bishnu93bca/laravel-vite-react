<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\ShippingMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

use DateTime;
use DateTimeZone;
use DB;

class WelcomeController extends Controller{
	public function getIndex()
    {
        $message="Writing Log Messages";
        $id=122;
        Log::info('Showing the user profile for user: {id}', ['id' => $id]);
        Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);
        return view('index', ['name' => 'Samantha']);
    }


    /**
     * Show the form to create a new blog post.
     */
    public function create(): View
    {
        return view('post.create');
    }
 
    /**
     * Store a new blog post.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate and store the blog post...
 
        $post = 100;/** ... */
        $validatedData = $request->validate([
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);
        return redirect('/posts');
        return to_route('post.show', ['post' => 1]);
    }

}