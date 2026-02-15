<?php

namespace Tests\Feature\Api;

use App\Models\Application;
use App\Models\Followup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageTrackerApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Helper: authenticated request headers.
     */
    private function authHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    // ────────────────────────────────────────────
    // AUTH TESTS
    // ────────────────────────────────────────────

    public function test_login_returns_token(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'token']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_unauthenticated_access_blocked(): void
    {
        $response = $this->getJson('/api/applications');

        $response->assertUnauthorized(); // 401
    }

    public function test_logout_revokes_token(): void
    {
        $response = $this->postJson('/api/logout', [], $this->authHeaders());
        $response->assertNoContent(); // 204

        // Verify token was deleted from DB
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    // ────────────────────────────────────────────
    // APPLICATION CRUD TESTS
    // ────────────────────────────────────────────

    public function test_create_application(): void
    {
        $payload = [
            'company' => 'Google',
            'position' => 'Backend Developer',
            'location' => 'Paris',
            'status' => 'applied',
            'applied_at' => '2026-02-01',
            'notes' => 'Applied via website',
        ];

        $response = $this->postJson('/api/applications', $payload, $this->authHeaders());

        $response->assertCreated() // 201
            ->assertJsonFragment(['company' => 'Google', 'position' => 'Backend Developer']);

        $this->assertDatabaseHas('applications', ['company' => 'Google']);
    }

    public function test_list_applications_with_filters(): void
    {
        Application::factory()->create(['status' => 'applied']);
        Application::factory()->create(['status' => 'interview']);
        Application::factory()->create(['status' => 'applied']);

        // Filter by status=applied
        $response = $this->getJson('/api/applications?status=applied', $this->authHeaders());

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_show_application(): void
    {
        $app = Application::factory()->create();

        $response = $this->getJson("/api/applications/{$app->id}", $this->authHeaders());

        $response->assertOk()
            ->assertJsonFragment(['company' => $app->company]);
    }

    public function test_update_application(): void
    {
        $app = Application::factory()->create(['status' => 'applied']);

        $response = $this->patchJson("/api/applications/{$app->id}", [
            'status' => 'interview',
        ], $this->authHeaders());

        $response->assertOk()
            ->assertJsonFragment(['status' => 'interview']);

        $this->assertDatabaseHas('applications', ['id' => $app->id, 'status' => 'interview']);
    }

    public function test_delete_application(): void
    {
        $app = Application::factory()->create();

        $response = $this->deleteJson("/api/applications/{$app->id}", [], $this->authHeaders());

        $response->assertNoContent(); // 204
        $this->assertDatabaseMissing('applications', ['id' => $app->id]);
    }

    // ────────────────────────────────────────────
    // VALIDATION TEST
    // ────────────────────────────────────────────

    public function test_create_application_validation_fails(): void
    {
        $response = $this->postJson('/api/applications', [
            // Missing required fields
            'status' => 'invalid-status',
        ], $this->authHeaders());

        $response->assertUnprocessable() // 422
            ->assertJsonValidationErrors(['company', 'position', 'status']);
    }

    // ────────────────────────────────────────────
    // FOLLOWUP TESTS
    // ────────────────────────────────────────────

    public function test_followup_crud(): void
    {
        $app = Application::factory()->create();

        // Create followup
        $response = $this->postJson("/api/applications/{$app->id}/followups", [
            'type' => 'email',
            'done_at' => '2026-02-10',
            'notes' => 'Sent first email',
        ], $this->authHeaders());

        $response->assertCreated()
            ->assertJsonFragment(['type' => 'email']);

        $followupId = $response->json('data.id');

        // List followups
        $this->getJson("/api/applications/{$app->id}/followups", $this->authHeaders())
            ->assertOk()
            ->assertJsonCount(1, 'data');

        // Delete followup
        $this->deleteJson("/api/followups/{$followupId}", [], $this->authHeaders())
            ->assertNoContent();

        $this->assertDatabaseMissing('followups', ['id' => $followupId]);
    }

    // ────────────────────────────────────────────
    // CSV EXPORT TEST
    // ────────────────────────────────────────────

    public function test_csv_export(): void
    {
        Application::factory()->create(['company' => 'ExportCorp']);

        $response = $this->get('/api/applications/export.csv', $this->authHeaders());

        $response->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="applications.csv"');

        $content = $response->streamedContent();
        $this->assertStringContainsString('ExportCorp', $content);
        $this->assertStringContainsString('company', $content); // header row
    }

    // ────────────────────────────────────────────
    // SWAGGER DOCUMENTATION TEST
    // ────────────────────────────────────────────

    public function test_swagger_documentation_accessible(): void
    {
        $response = $this->get('/api/documentation');

        $response->assertOk(); // Swagger UI loads successfully
    }
}
