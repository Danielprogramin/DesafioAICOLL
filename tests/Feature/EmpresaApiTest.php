<?php

namespace Tests\Feature;

use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmpresaApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_all_empresas(): void
    {
        // Arrange
        Empresa::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/empresas');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'nit',
                        'nombre',
                        'direccion',
                        'telefono',
                        'estado',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'message'
            ])
            ->assertJsonPath('success', true);
    }

    public function test_can_create_empresa(): void
    {
        // Arrange
        $empresaData = [
            'nit' => '123456789-0',
            'nombre' => 'Test Company',
            'direccion' => 'Test Address 123',
            'telefono' => '555-1234567',
        ];

        // Act
        $response = $this->postJson('/api/empresas', $empresaData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'nit',
                    'nombre',
                    'direccion',
                    'telefono',
                    'estado',
                    'created_at',
                    'updated_at'
                ],
                'message'
            ])
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nit', $empresaData['nit'])
            ->assertJsonPath('data.estado', 'Activo');

        $this->assertDatabaseHas('empresas', $empresaData);
    }

    public function test_cannot_create_empresa_with_duplicate_nit(): void
    {
        // Arrange
        $existingEmpresa = Empresa::factory()->create(['nit' => '123456789-0']);

        $empresaData = [
            'nit' => '123456789-0',
            'nombre' => 'Another Company',
            'direccion' => 'Another Address',
            'telefono' => '555-7654321',
        ];

        // Act
        $response = $this->postJson('/api/empresas', $empresaData);

        // Assert
        $response->assertStatus(409)
            ->assertJsonPath('success', false);
    }

    public function test_can_get_empresa_by_nit(): void
    {
        // Arrange
        $empresa = Empresa::factory()->create(['nit' => '123456789-0']);

        // Act
        $response = $this->getJson("/api/empresas/{$empresa->nit}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $empresa->id)
            ->assertJsonPath('data.nit', $empresa->nit);
    }

    public function test_returns_404_when_empresa_not_found(): void
    {
        // Act
        $response = $this->getJson('/api/empresas/nonexistent-nit');

        // Assert
        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_can_update_empresa(): void
    {
        // Arrange
        $empresa = Empresa::factory()->create();
        $updateData = [
            'nombre' => 'Updated Company Name',
            'direccion' => 'Updated Address',
            'estado' => 'Inactivo'
        ];

        // Act
        $response = $this->putJson("/api/empresas/{$empresa->nit}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.nombre', $updateData['nombre'])
            ->assertJsonPath('data.direccion', $updateData['direccion'])
            ->assertJsonPath('data.estado', $updateData['estado']);

        $this->assertDatabaseHas('empresas', [
            'id' => $empresa->id,
            'nombre' => $updateData['nombre'],
            'direccion' => $updateData['direccion'],
            'estado' => $updateData['estado']
        ]);
    }

    public function test_can_delete_inactive_empresas(): void
    {
        // Arrange
        Empresa::factory()->count(2)->create(['estado' => 'Activo']);
        Empresa::factory()->count(3)->create(['estado' => 'Inactivo']);

        // Act
        $response = $this->deleteJson('/api/empresas/inactive');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.deleted_count', 3);

        $this->assertDatabaseCount('empresas', 2);
        $this->assertDatabaseMissing('empresas', ['estado' => 'Inactivo']);
    }

    public function test_validation_fails_for_missing_required_fields(): void
    {
        // Act
        $response = $this->postJson('/api/empresas', []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nit', 'nombre', 'direccion', 'telefono']);
    }

    public function test_validation_fails_for_invalid_estado_in_update(): void
    {
        // Arrange
        $empresa = Empresa::factory()->create();

        // Act
        $response = $this->putJson("/api/empresas/{$empresa->nit}", [
            'estado' => 'InvalidEstado'
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['estado']);
    }
}
