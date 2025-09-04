<?php

namespace App\Repositories;

use App\Models\Empresa;
use App\Repositories\Interfaces\EmpresaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EmpresaRepository implements EmpresaRepositoryInterface
{
    public function findAll(): Collection
    {
        return Empresa::all();
    }

    public function findByNit(string $nit): ?Empresa
    {
        return Empresa::where('nit', $nit)->first();
    }

    public function create(array $data): Empresa
    {
        return Empresa::create($data);
    }

    public function update(Empresa $empresa, array $data): Empresa
    {
        $empresa->update($data);
        return $empresa->fresh();
    }

    public function deleteInactiveEmpresas(): int
    {
        return Empresa::inactive()->delete();
    }

    public function findById(int $id): ?Empresa
    {
        return Empresa::find($id);
    }
}
