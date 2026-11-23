<?php

namespace App\Controllers;

use App\Models\Empleado;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class EmpleadoController
{
    function index(?string $estado = null, ?string $area = null): Collection
    {
        $query = Empleado::query();

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        if (!empty($area)) {
            $query->where('area', $area);
        }

        return $query->get();
    }

    function show(int $id): Empleado
    {
        return Empleado::findOrFail($id);
    }

    function buscarPorDocumento(string $documento): Empleado
    {
        return Empleado::where('documento', $documento)->firstOrFail();
    }

    function store(array $data): Empleado
    {
        $existe = Empleado::where('documento', $data['documento'])
            ->orWhere('correo', $data['correo'])
            ->exists();

        if ($existe) {
            throw new Exception("El número de documento o el correo ya se encuentran registrados.", 400);
        }

        $empleado = new Empleado();
        $empleado->nombres = $data['nombres'];
        $empleado->apellidos = $data['apellidos'];
        $empleado->documento = $data['documento'];
        $empleado->correo = $data['correo'];
        $empleado->telefono = $data['telefono'];
        $empleado->cargo = $data['cargo'];
        $empleado->area = $data['area'];
        $empleado->fecha_ingreso = $data['fecha_ingreso'];
        $empleado->estado = $data['estado'] ?? 'activo';
        $empleado->save();

        return $empleado;
    }

    function update(int $id, array $data): Empleado
    {
        $empleado = Empleado::findOrFail($id);

        if (isset($data['correo']) && $data['correo'] !== $empleado->correo) {
            $correoDuplicado = Empleado::where('correo', $data['correo'])
                ->where('id', '!=', $id)
                ->exists();

            if ($correoDuplicado) {
                throw new Exception("El correo ya está en uso por otro empleado.", 400);
            }
        }

        $empleado->nombres = $data['nombres'] ?? $empleado->nombres;
        $empleado->apellidos = $data['apellidos'] ?? $empleado->apellidos;
        $empleado->correo = $data['correo'] ?? $empleado->correo;
        $empleado->telefono = $data['telefono'] ?? $empleado->telefono;
        $empleado->cargo = $data['cargo'] ?? $empleado->cargo;
        $empleado->area = $data['area'] ?? $empleado->area;
        $empleado->fecha_ingreso = $data['fecha_ingreso'] ?? $empleado->fecha_ingreso;
        $empleado->save();

        return $empleado;
    }

    function cambiarEstado(int $id, ?string $estado = null): Empleado
    {
        $empleado = Empleado::findOrFail($id);

        if ($estado !== null) {
            if (!in_array($estado, ['activo', 'inactivo'])) {
                throw new Exception("Estado no válido. Use 'activo' o 'inactivo'.", 400);
            }
            $empleado->estado = $estado;
        } else {
            $empleado->estado = ($empleado->estado === 'activo') ? 'inactivo' : 'activo';
        }

        $empleado->save();
        return $empleado;
    }
}
