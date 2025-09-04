<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateNitException;
use App\Exceptions\EmpresaNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Services\EmpresaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmpresaController extends Controller
{
    public function __construct(
        private EmpresaService $empresaService
    ) {}

    /**
     * Display a listing of all empresas.
     */
    public function index(): JsonResponse
    {
        try {
            $empresas = $this->empresaService->getAllEmpresas();

            return response()->json([
                'success' => true,
                'data' => $empresas,
                'message' => 'Empresas obtenidas exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created empresa in storage.
     */
    public function store(StoreEmpresaRequest $request): JsonResponse
    {
        try {
            $empresa = $this->empresaService->createEmpresa($request->validated());

            return response()->json([
                'success' => true,
                'data' => $empresa,
                'message' => 'Empresa creada exitosamente.'
            ], Response::HTTP_CREATED);
        } catch (DuplicateNitException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified empresa by NIT.
     */
    public function show(string $nit): JsonResponse
    {
        try {
            $empresa = $this->empresaService->getEmpresaByNit($nit);

            return response()->json([
                'success' => true,
                'data' => $empresa,
                'message' => 'Empresa encontrada exitosamente.'
            ]);
        } catch (EmpresaNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified empresa in storage.
     */
    public function update(UpdateEmpresaRequest $request, string $nit): JsonResponse
    {
        try {
            $empresa = $this->empresaService->updateEmpresa($nit, $request->validated());

            return response()->json([
                'success' => true,
                'data' => $empresa,
                'message' => 'Empresa actualizada exitosamente.'
            ]);
        } catch (EmpresaNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Note: This method is not used in our business logic as we only delete inactive empresas.
     */
    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Esta operación no está disponible. Use el endpoint /inactive para eliminar empresas inactivas.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Remove inactive empresas from storage.
     */
    public function deleteInactive(): JsonResponse
    {
        try {
            $result = $this->empresaService->deleteInactiveEmpresas();

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
