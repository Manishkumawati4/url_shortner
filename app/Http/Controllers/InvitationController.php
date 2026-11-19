<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InvitationController extends Controller
{
    // Invitation listing
    public function index()
    {
        $user = auth()->user();

        $query = Invitation::with(['company', 'inviter'])
            ->latest();

        if ($user->role === 'Admin') {
            $query->where('company_id', $user->company_id);
        }

        $invitations = $query->get();

        return view('invitations.index', compact('invitations'));
    }

    // Show invitation form
    public function create()
    {
        $user = auth()->user();

        $companies = $user->role === 'SuperAdmin'
            ? Company::orderBy('name')->get()
            : collect();

        return view('invitations.create', compact('companies'));
    }

    // Store invitation
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['SuperAdmin', 'Admin'])) {
            abort(403, 'You are not allowed to send invitations.');
        }

        if ($user->role === 'Admin' && !$user->company_id) {
            abort(403, 'Your account is not assigned to a company.');
        }

        // Role permissions
        if ($user->role === 'SuperAdmin') {
            $allowedRoles = ['Member', 'Sales', 'Manager'];
        } else {
            $allowedRoles = ['Sales', 'Manager'];
        }

        $rules = [
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::in($allowedRoles)],
        ];

        if ($user->role === 'SuperAdmin') {
            $rules['company_id'] = [
                'required',
                'exists:companies,id',
            ];
        }

        $validated = $request->validate($rules);

        // Company assignment
        $companyId = $user->role === 'SuperAdmin'
            ? $validated['company_id']
            : $user->company_id;

        // Prevent duplicate active invitations
        $existingInvitation = Invitation::where('company_id', $companyId)
            ->where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($existingInvitation) {
            return back()
                ->withErrors(['email' => 'An active invitation already exists for this email.'])
                ->withInput();
        }

        // Prevent inviting an existing user
        if (User::where('email', $validated['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'This email is already registered.'])
                ->withInput();
        }

        $invitation = Invitation::create([
            'company_id' => $companyId,
            'invited_by' => $user->id,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        $inviteUrl = route('invitations.accept', $invitation->token);

        return redirect()
            ->route('invitations.create')
            ->with('success', 'Invitation created successfully!')
            ->with('invite_url', $inviteUrl);
    }

    // Show invitation acceptance form
    public function acceptForm($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->accepted_at || $invitation->expires_at->isPast()) {
            abort(410, 'This invitation is expired or already accepted.');
        }

        return view('invitations.accept', compact('invitation'));
    }

    // Accept invitation and create user
    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->accepted_at || $invitation->expires_at->isPast()) {
            abort(410, 'This invitation is expired or already accepted.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (User::where('email', $invitation->email)->exists()) {
            return back()->withErrors([
                'email' => 'This email is already registered.',
            ]);
        }

        DB::transaction(function () use ($invitation, $validated) {

            User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'company_id' => $invitation->company_id,
                'role' => $invitation->role,
            ]);

            $invitation->update([
                'accepted_at' => now(),
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Invitation accepted! You can now log in.');
    }
}
