<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    // Short URL listing
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'SuperAdmin') {
            abort(403, 'SuperAdmin cannot view all short URLs.');
        }

        $query = ShortUrl::with(['user', 'company'])->latest();

        if ($user->role === 'Admin') {
            $query->where('company_id', '!=', $user->company_id);
        } elseif ($user->role === 'Member') {
            $query->where('user_id', '!=', $user->id);
        } else {
            abort(403, 'You are not allowed to view this list.');
        }

        $shortUrls = $query->get();

        return view('short_urls.index', compact('shortUrls'));
    }

    // Show create form
    public function create()
    {
        $user = auth()->user();

        if (!in_array($user->role, ['Sales', 'Manager'])) {
            abort(403, 'You are not allowed to create short URLs.');
        }

        if (!$user->company_id) {
            abort(403, 'Your account is not assigned to a company.');
        }

        return view('short_urls.create');
    }

    // Save short URL
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['Sales', 'Manager'])) {
            abort(403, 'You are not allowed to create short URLs.');
        }

        if (!$user->company_id) {
            abort(403, 'Your account is not assigned to a company.');
        }

        $validated = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        do {
            $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
        ]);

        return redirect()
            ->route('short-urls.create')
            ->with('success', 'Short URL created successfully!')
            ->with('short_url', url('/s/' . $shortCode));
    }

    public function redirect($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}
