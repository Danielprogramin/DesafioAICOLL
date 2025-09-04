<?php

namespace Tests\Unit;

use App\Models\Empresa;
use PHPUnit\Framework\TestCase;

class EmpresaModelTest extends TestCase
{
    public function test_empresa_has_correct_fillable_attributes(): void
    {
        $empresa = new Empresa();
        $expectedFillable = ['nit', 'nombre', 'direccion', 'telefono', 'estado'];

        $this->assertEquals($expectedFillable, $empresa->getFillable());
    }

    public function test_empresa_has_default_estado_activo(): void
    {
        $empresa = new Empresa();
        $this->assertEquals('Activo', $empresa->getAttributes()['estado']);
    }

    public function test_is_active_method_returns_true_when_estado_is_activo(): void
    {
        $empresa = new Empresa(['estado' => 'Activo']);
        $this->assertTrue($empresa->isActive());
    }

    public function test_is_active_method_returns_false_when_estado_is_inactivo(): void
    {
        $empresa = new Empresa(['estado' => 'Inactivo']);
        $this->assertFalse($empresa->isActive());
    }

    public function test_is_inactive_method_returns_true_when_estado_is_inactivo(): void
    {
        $empresa = new Empresa(['estado' => 'Inactivo']);
        $this->assertTrue($empresa->isInactive());
    }

    public function test_is_inactive_method_returns_false_when_estado_is_activo(): void
    {
        $empresa = new Empresa(['estado' => 'Activo']);
        $this->assertFalse($empresa->isInactive());
    }
}
