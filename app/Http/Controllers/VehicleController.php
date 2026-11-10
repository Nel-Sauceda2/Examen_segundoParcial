<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('Vehicle.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:15|unique:vehicles,plate_number',
            'brand'        => 'required|string|max:50',
            'model'        => 'required|string|max:80',
            'vehicle_type' => 'required|string|max:50',
            'color'        => 'required|string|max:30',
            'capacity'     => 'required|integer|min:1',
            'daily_rate'   => 'required|numeric|min:0',
            'status'       => 'required|string|max:30',
        ], [
            'plate_number.required' => 'El número de placa es obligatorio.',
            'plate_number.unique'   => 'Esta placa ya se encuentra registrada en el sistema.',
            'plate_number.max'      => 'La placa no puede exceder los 15 caracteres.',
            'brand.required'        => 'La marca del vehículo es obligatoria.',
            'brand.max'             => 'La marca no puede exceder los 50 caracteres.',
            'model.required'        => 'El modelo del vehículo es obligatorio.',
            'model.max'             => 'El modelo no puede exceder los 80 caracteres.',
            'vehicle_type.required' => 'El tipo de vehículo es obligatorio.',
            'color.required'        => 'El color es obligatorio.',
            'capacity.required'     => 'La capacidad es obligatoria y debe ser un número válido.',
            'capacity.integer'      => 'La capacidad debe ser un número entero.',
            'daily_rate.required'   => 'La tarifa diaria es obligatoria.',
            'daily_rate.numeric'    => 'La tarifa diaria debe ser un valor numérico.',
            'status.required'       => 'El estado del vehículo es obligatorio.',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', '¡Vehículo registrado exitosamente!');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_number' => 'required|string|max:15|unique:vehicles,plate_number,' . $vehicle->id,
            'brand'        => 'required|string|max:50',
            'model'        => 'required|string|max:80',
            'vehicle_type' => 'required|string|max:50',
            'color'        => 'required|string|max:30',
            'capacity'     => 'required|integer|min:1',
            'daily_rate'   => 'required|numeric|min:0',
            'status'       => 'required|string|max:30',
        ], [
            'plate_number.required' => 'El número de placa es obligatorio.',
            'plate_number.unique'   => 'Esta placa ya pertenece a otro vehículo registrado.',
            'brand.required'        => 'La marca es obligatoria.',
            'model.required'        => 'El modelo es obligatorio.',
            'vehicle_type.required' => 'El tipo de vehículo es obligatorio.',
            'color.required'        => 'El color es obligatorio.',
            'capacity.required'     => 'La capacidad es obligatoria.',
            'daily_rate.required'   => 'La tarifa diaria es obligatoria.',
            'daily_rate.numeric'    => 'La tarifa diaria debe ser un número.',
            'status.required'       => 'El estado es obligatorio.',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', '¡Vehículo actualizado correctamente!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', '¡Vehículo eliminado exitosamente!');
    }
}