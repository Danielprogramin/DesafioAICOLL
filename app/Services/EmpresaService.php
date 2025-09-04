<?php

namespace App\Services;

use App\Exceptions\DuplicateNitException;
use App\Exceptions\EmpresaNotFoundException;
use App\Models\Empresa;
use App\Repositories\Interfaces\EmpresaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaService
{
    public function __construct(
        private EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function getAllEmpresas(): Collection
    {
        try {
            return $this->empresaRepository->findAll();
        } catch (\Exception $e) {
            Log::error('Error retrieving all empresas: ' . $e->getMessage());
            throw new \Exception('Error al obtener las empresas.');
        }
    }

    public function getEmpresaByNit(string $nit): Empresa
    {
        try {
            $empresa = $this->empresaRepository->findByNit($nit);

            if (!$empresa) {
                throw new EmpresaNotFoundException($nit);
            }

            return $empresa;
        } catch (EmpresaNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error retrieving empresa by NIT {$nit}: " . $e->getMessage());
            throw new \Exception('Error al buscar la empresa.');
        }
    }

    public function createEmpresa(array $data): Empresa
    {
        try {
            return DB::transaction(function () use ($data) {
                // Verificar si ya existe una empresa con ese NIT
                if ($this->empresaRepository->findByNit($data['nit'])) {
                    throw new DuplicateNitException($data['nit']);
                }

                return $this->empresaRepository->create($data);
            });
        } catch (DuplicateNitException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating empresa: ' . $e->getMessage());
            throw new \Exception('Error al crear la empresa.');
        }
    }

    public function updateEmpresa(string $nit, array $data): Empresa
    {
        try {
            return DB::transaction(function () use ($nit, $data) {
                $empresa = $this->empresaRepository->findByNit($nit);

                if (!$empresa) {
                    throw new EmpresaNotFoundException($nit);
                }

                return $this->empresaRepository->update($empresa, $data);
            });
        } catch (EmpresaNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error updating empresa with NIT {$nit}: " . $e->getMessage());
            throw new \Exception('Error al actualizar la empresa.');
        }
    }

    public function deleteInactiveEmpresas(): array
    {
        try {
            $deletedCount = $this->empresaRepository->deleteInactiveEmpresas();

            Log::info("Deleted {$deletedCount} inactive empresas");

            return [
                'deleted_count' => $deletedCount,
                'message' => "Se eliminaron {$deletedCount} empresas inactivas."
            ];
        } catch (\Exception $e) {
            Log::error('Error deleting inactive empresas: ' . $e->getMessage());
            throw new \Exception('Error al eliminar las empresas inactivas.');
        }
    }
}
