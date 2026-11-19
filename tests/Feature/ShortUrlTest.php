<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    protected $companyA;
    protected $companyB;

    protected $admin;
    protected $member;
    protected $memberB;
    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create companies
        $this->companyA = DB::table('companies')->insertGetId([
            'name' => 'Company A',
            'slug' => 'company-a',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->companyB = DB::table('companies')->insertGetId([
            'name' => 'Company B',
            'slug' => 'company-b',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create users
        $this->admin = $this->createUser(
            'Test Admin',
            'admin@test.com',
            'Admin',
            $this->companyA
        );

        $this->member = $this->createUser(
            'Test Member',
            'member@test.com',
            'Member',
            $this->companyA
        );

        $this->memberB = $this->createUser(
            'Test Member B',
            'memberb@test.com',
            'Member',
            $this->companyB
        );

        $this->superAdmin = $this->createUser(
            'Super Admin',
            'superadmin@test.com',
            'SuperAdmin',
            null
        );

        // Create sample short URLs
        $this->createShortUrl($this->admin, 'admin01');
        $this->createShortUrl($this->member, 'member1');
        $this->createShortUrl($this->memberB, 'memberB1');
    }

    private function createUser($name, $email, $role, $companyId)
    {
        $id = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('Password@123'),
            'role' => $role,
            'company_id' => $companyId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }

    private function createShortUrl(User $user, $code)
    {
        DB::table('short_urls')->insert([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => 'https://www.google.com',
            'short_code' => $code,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Admin cannot create short URL
    public function test_admin_cannot_create_short_url(): void
    {
        $this->actingAs($this->admin)
            ->post(route('short-urls.store'), [
                'original_url' => 'https://example.com',
            ])
            ->assertForbidden();
    }

    // Member cannot create short URL
    public function test_member_cannot_create_short_url(): void
    {
        $this->actingAs($this->member)
            ->post(route('short-urls.store'), [
                'original_url' => 'https://example.com',
            ])
            ->assertForbidden();
    }

    // SuperAdmin cannot create short URL
    public function test_superadmin_cannot_create_short_url(): void
    {
        $this->actingAs($this->superAdmin)
            ->post(route('short-urls.store'), [
                'original_url' => 'https://example.com',
            ])
            ->assertForbidden();
    }

    // Admin can only see URLs from other companies
    public function test_admin_can_only_see_other_company_urls(): void
    {
        $this->actingAs($this->admin)
            ->get(route('short-urls.index'))
            ->assertOk()
            ->assertSee('memberB1')
            ->assertDontSee('admin01');
    }

    // Member cannot see their own URLs
    public function test_member_can_only_see_other_users_urls(): void
    {
        $this->actingAs($this->member)
            ->get(route('short-urls.index'))
            ->assertOk()
            ->assertSee('admin01')
            ->assertSee('memberB1')
            ->assertDontSee('member1');
    }

    // SuperAdmin cannot view URL listing
    public function test_superadmin_cannot_view_short_url_listing(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('short-urls.index'))
            ->assertForbidden();
    }

    // Guest cannot resolve short URL
    public function test_guest_cannot_resolve_short_url(): void
    {
        $this->get('/s/memberB1')
            ->assertRedirect(route('login'));
    }

    // Logged-in user can resolve short URL
    public function test_authenticated_user_can_resolve_short_url(): void
    {
        $this->actingAs($this->member)
            ->get('/s/memberB1')
            ->assertRedirect('https://www.google.com');
    }

    // Invalid short code returns 404
    public function test_invalid_short_code_returns_404(): void
    {
        $this->actingAs($this->member)
            ->get('/s/invalid123')
            ->assertNotFound();
    }
}
