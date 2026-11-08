<?php
namespace Tests\Feature;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class AccountFormsTest extends TestCase
{
    use RefreshDatabase;
    private function user(string $username = 'cashier'): User
    {
        return User::create(['username' => $username, 'full_name' => 'Test User']);
    }
    public function test_customer_creation_and_editing(): void
    {
        $this->get('/customers/create')->assertOk();
        $this->post('/customers', ['full_name' => 'Maria Santos', 'email' => 'maria@example.com'])
            ->assertRedirect('/customers');
        $customer = Customer::firstOrFail();
        $this->get('/customers/'.$customer->id.'/edit')->assertOk()->assertSee('Maria Santos');
        $this->put('/customers/'.$customer->id, ['full_name' => 'Maria Reyes', 'email' => 'new@example.com'])
            ->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'full_name' => 'Maria Reyes']);
    }
    public function test_customer_invalid_input_is_rejected_and_old_input_retained(): void
    {
        $this->from('/customers/create')->post('/customers', ['full_name' => 'Maria', 'email' => 'invalid'])
            ->assertRedirect('/customers/create')->assertSessionHasErrors('email')
            ->assertSessionHasInput('full_name', 'Maria');
        $this->assertDatabaseCount('customers', 0);
        $this->post('/customers', [])->assertSessionHasErrors(['full_name', 'email']);
    }
    public function test_user_creation_requires_a_unique_username_and_full_name(): void
    {
        $this->get('/users/create')->assertOk();
        $this->post('/users', [])->assertSessionHasErrors(['username', 'full_name']);
        $this->post('/users', ['username' => 'cashier', 'full_name' => 'First User'])->assertRedirect('/users');
        $this->post('/users', ['username' => 'cashier', 'full_name' => 'Second User'])->assertSessionHasErrors('username');
        $this->assertDatabaseCount('users', 1);
    }
    public function test_edit_allows_own_username_but_rejects_another_users_username(): void
    {
        $first = $this->user('first');
        $second = $this->user('second');
        $this->get('/users/'.$first->id.'/edit')->assertOk()->assertSee('first');
        $this->put('/users/'.$first->id, ['username' => 'first', 'full_name' => 'Updated'])
            ->assertRedirect('/users');
        $this->put('/users/'.$first->id, ['username' => $second->username, 'full_name' => 'Updated'])
            ->assertSessionHasErrors('username');
        $this->assertSame('first', $first->fresh()->username);
    }
    public function test_avatar_is_reencoded_resized_and_replaced_safely(): void
    {
        Storage::fake('public');
        $user = $this->user();
        $data = ['username' => $user->username, 'full_name' => $user->full_name];
        $this->put('/users/'.$user->id, $data + ['avatar' => UploadedFile::fake()->image('photo.png', 800, 400)])
            ->assertRedirect('/users');
        $old = $user->fresh()->avatar;
        Storage::disk('public')->assertExists($old);
        $size = getimagesizefromstring(Storage::disk('public')->get($old));
        $this->assertSame(256, $size[0]);
        $this->assertSame(256, $size[1]);
        $this->assertSame('image/jpeg', $size['mime']);
        $this->assertStringStartsWith('avatars/', $old);
        $this->get('/users')->assertOk()->assertSee(Storage::disk('public')->url($old));
        $this->put('/users/'.$user->id, $data)->assertRedirect('/users');
        $this->assertSame($old, $user->fresh()->avatar);
        $this->put('/users/'.$user->id, $data + ['avatar' => UploadedFile::fake()->image('new.jpg', 400, 800)])
            ->assertRedirect('/users');
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($user->fresh()->avatar);
    }
    public function test_bad_uploads_do_not_change_existing_avatar_or_details(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/original.jpg', 'existing');
        $user = $this->user();
        $user->update(['avatar' => 'avatars/original.jpg']);
        $data = ['username' => $user->username, 'full_name' => 'Changed'];
        $this->put('/users/'.$user->id, $data + ['avatar' => UploadedFile::fake()->create('file.txt', 10, 'text/plain')])
            ->assertSessionHasErrors('avatar');
        $this->put('/users/'.$user->id, $data + ['avatar' => UploadedFile::fake()->image('large.jpg')->size(2049)])
            ->assertSessionHasErrors('avatar');
        $this->assertSame('Test User', $user->fresh()->full_name);
        $this->assertSame('avatars/original.jpg', $user->fresh()->avatar);
        Storage::disk('public')->assertExists('avatars/original.jpg');
    }
    public function test_missing_records_return_404_and_placeholder_is_displayed(): void
    {
        $this->get('/customers/999/edit')->assertNotFound();
        $this->get('/users/999/edit')->assertNotFound();
        $this->user();
        $this->get('/users')->assertOk()->assertSee('images/avatar-placeholder.svg');
    }
}
