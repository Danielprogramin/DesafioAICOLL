<?php

namespace App\Repositories\Interfaces;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Collection;

interface EmpresaRepositoryInterface
{
    public function findAll(): Collection;

    public function findByNit(string $nit): ?Empresa;

    public function create(array $data): Empresa;

    public function update(Empresa $empresa, array $data): Empresa;

    public function deleteInactiveEmpresas(): int;

    public function findById(int $id): ?Empresa;
}
