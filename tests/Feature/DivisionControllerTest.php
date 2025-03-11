<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Division;

class DivisionControllerTest extends TestCase
{


    /**
     * Тест для метода index (GET /api/divisions)
     */
    public function test_index()
    {
        // Создаем тестовые данные
        Division::factory()->count(5)->create();

        // Выполняем запрос
        $response = $this->getJson('/api/divisions');

        // Проверяем статус и структуру ответа
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'current_page',
                'per_page',
                'total',
            ]);
    }

    /**
     * Тест для метода store (POST /api/divisions)
     */
    public function test_store()
    {
        // Данные для создания дивизиона
        $data = [
            'name' => 'Test Division ' . Str::random(5),
        ];

        // Выполняем запрос
        $response = $this->postJson('/api/divisions', $data);

        // Проверяем статус и структуру ответа
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]);

        // Проверяем, что дивизион был создан в базе данных
        $this->assertDatabaseHas('divisions', $data);
    }

    /**
     * Тест для метода show (GET /api/divisions/{id})
     */
    public function test_show()
    {
        // Создаем тестовый дивизион
        $division = Division::factory()->create();

        // Выполняем запрос
        $response = $this->getJson("/api/divisions/{$division->id}");

        // Проверяем статус и структуру ответа
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Тест для метода update (PUT /api/divisions/{id})
     */
    public function test_update()
    {
        // Создаем тестовый дивизион
        $division = Division::factory()->create();

        // Новые данные для обновления
        $data = [
            'name' => 'Updated Division Name'. Str::random(5),
        ];

        // Выполняем запрос
        $response = $this->putJson("/api/divisions/{$division->id}", $data);

        // Проверяем статус и структуру ответа
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]);

        // Проверяем, что данные были обновлены в базе данных
        $this->assertDatabaseHas('divisions', $data);
    }

    /**
     * Тест для метода destroy (DELETE /api/divisions/{id})
     */
    public function test_destroy()
    {
        // Создаем тестовый дивизион
        $division = Division::factory()->create();

        // Выполняем запрос
        $response = $this->deleteJson("/api/divisions/{$division->id}");

        // Проверяем статус ответа
        $response->assertStatus(204);

        // Проверяем, что дивизион был удален из базы данных
        $this->assertDatabaseMissing('divisions', ['id' => $division->id]);
    }
}
