<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            [
                'nit' => '900123456-7',
                'nombre' => 'Empresa de Tecnología S.A.',
                'direccion' => 'Calle 100 # 45-67, Bogotá',
                'telefono' => '601-234-5678',
                'estado' => 'Activo'
            ],
            [
                'nit' => '900234567-8',
                'nombre' => 'Comercializadora del Norte Ltda.',
                'direccion' => 'Carrera 15 # 23-45, Medellín',
                'telefono' => '604-345-6789',
                'estado' => 'Activo'
            ],
            [
                'nit' => '900345678-9',
                'nombre' => 'Servicios Integrales del Pacífico',
                'direccion' => 'Avenida 6N # 12-34, Cali',
                'telefono' => '602-456-7890',
                'estado' => 'Inactivo'
            ],
            [
                'nit' => '900456789-0',
                'nombre' => 'Constructora Moderna S.A.S.',
                'direccion' => 'Diagonal 15 # 78-90, Cartagena',
                'telefono' => '605-567-8901',
                'estado' => 'Activo'
            ],
            [
                'nit' => '900567890-1',
                'nombre' => 'Distribuidora Nacional E.U.',
                'direccion' => 'Calle 45 # 12-67, Barranquilla',
                'telefono' => '605-678-9012',
                'estado' => 'Inactivo'
            ]
        ];

        foreach ($empresas as $empresaData) {
            Empresa::create($empresaData);
        }
    }
}
