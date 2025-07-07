<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\RolesAndPermissionsSeeder;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('admin can create category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin, 'sanctum');

    $response = $this->postJson('/api/categories', [
        'name' => 'Laravel',
    ]);

    $response->assertCreated()
             ->assertJsonPath('data.name', 'Laravel');
});

test('anyone can list categories', function () {
    Category::factory()->create(['name' => 'Laravel']);

    $response = $this->getJson('/api/categories');

    $response->assertOk()
             ->assertJsonFragment(['name' => 'Laravel']);
});

test('admin can update category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    Sanctum::actingAs($admin);

    $category = Category::factory()->create(['name' => 'Old Name']);

    $response = $this->putJson("/api/categories/{$category->id}", [
        'name' => 'New Name',
    ]);

    $response->assertOk()
             ->assertJsonPath('name', 'New Name');
});

test('admin can delete category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    Sanctum::actingAs($admin);

    $category = Category::factory()->create();

    $response = $this->deleteJson("/api/categories/{$category->id}");

    $response->assertOk();
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
