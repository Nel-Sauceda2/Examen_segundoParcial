@extends('app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Administración de Flota de Vehículos</h1>

    <!-- Alertas de Éxito -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Alertas de Error general de validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Atención!</strong> Por favor corrige los siguientes errores:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Botón para abrir modal de Registrar -->
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="fas fa-plus"></i> + Registrar Vehículo
        </button>
    </div>

    <!-- Tabla de Listado de Vehículos -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Placa</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>Color</th>
                            <th>Capacidad</th>
                            <th>Tarifa Diaria</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->id }}</td>
                                <td>{{ $vehicle->plate_number }}</td>
                                <td>{{ $vehicle->brand }}</td>
                                <td>{{ $vehicle->model }}</td>
                                <td>{{ $vehicle->vehicle_type }}</td>
                                <td>{{ $vehicle->color }}</td>
                                <td>{{ $vehicle->capacity }}</td>
                                <td>${{ number_format($vehicle->daily_rate, 2) }}</td>
                                <td>
                                    <span class="badge {{ $vehicle->status == 'Disponible' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $vehicle->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditar{{ $vehicle->id }}">
                                            Editar
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEliminar{{ $vehicle->id }}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL EDITAR VEHÍCULO -->
                            <div class="modal fade" id="modalEditar{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">Editar Vehículo: {{ $vehicle->plate_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label">Placa</label>
                                                    <input type="text" class="form-control" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required maxlength="15">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Marca</label>
                                                    <input type="text" class="form-control" name="brand" value="{{ old('brand', $vehicle->brand) }}" required maxlength="50">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Modelo</label>
                                                    <input type="text" class="form-control" name="model" value="{{ old('model', $vehicle->model) }}" required maxlength="80">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tipo de Vehículo</label>
                                                    <input type="text" class="form-control" name="vehicle_type" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" required maxlength="50">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Color</label>
                                                    <input type="text" class="form-control" name="color" value="{{ old('color', $vehicle->color) }}" required maxlength="30">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Capacidad</label>
                                                    <input type="number" class="form-control" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" required min="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tarifa Diaria ($)</label>
                                                    <input type="number" step="0.01" class="form-control" name="daily_rate" value="{{ old('daily_rate', $vehicle->daily_rate) }}" required min="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Estado</label>
                                                    <select class="form-select" name="status" required>
                                                        <option value="Disponible" @selected(old('status', $vehicle->status) == 'Disponible')>Disponible</option>
                                                        <option value="En Ruta" @selected(old('status', $vehicle->status) == 'En Ruta')>En Ruta</option>
                                                        <option value="Mantenimiento" @selected(old('status', $vehicle->status) == 'Mantenimiento')>Mantenimiento</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-warning">Actualizar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL ELIMINAR VEHÍCULO -->
                            <div class="modal fade" id="modalEliminar{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>¿Estás seguro de que deseas eliminar el vehículo con placa <strong>{{ $vehicle->plate_number }}</strong>?</p>
                                                <p class="text-muted small">Esta acción no se puede deshacer.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    No hay vehículos registrados en el sistema.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL REGISTRAR NUEVO VEHÍCULO -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalCrearLabel">Registrar Nuevo Vehículo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Placa</label>
                        <input type="text" class="form-control" name="plate_number" value="{{ old('plate_number') }}" required maxlength="15" placeholder="Ej: P-123ABC">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Marca</label>
                        <input type="text" class="form-control" name="brand" value="{{ old('brand') }}" required maxlength="50" placeholder="Ej: Toyota">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Modelo</label>
                        <input type="text" class="form-control" name="model" value="{{ old('model') }}" required maxlength="80" placeholder="Ej: Coaster">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Vehículo</label>
                        <input type="text" class="form-control" name="vehicle_type" value="{{ old('vehicle_type') }}" required maxlength="50" placeholder="Ej: Autobús">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="text" class="form-control" name="color" value="{{ old('color') }}" required maxlength="30" placeholder="Ej: Blanco">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacidad (Pasajeros)</label>
                        <input type="number" class="form-control" name="capacity" value="{{ old('capacity') }}" required min="1" placeholder="Ej: 30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarifa Diaria ($)</label>
                        <input type="number" step="0.01" class="form-control" name="daily_rate" value="{{ old('daily_rate') }}" required min="0" placeholder="Ej: 150.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="status" required>
                            <option value="Disponible" @selected(old('status', 'Disponible') == 'Disponible')>Disponible</option>
                            <option value="En Ruta" @selected(old('status') == 'En Ruta')>En Ruta</option>
                            <option value="Mantenimiento" @selected(old('status') == 'Mantenimiento')>Mantenimiento</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Vehículo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para mantener abierto el modal si ocurren errores de validación -->
@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalCrear'));
        myModal.show();
    });
</script>
@endif
@endsection