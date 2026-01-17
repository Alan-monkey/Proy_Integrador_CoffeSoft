<!-- Modal -->
<div class="modal fade" id="modal{{ $libro->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $libro->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel{{ $libro->id }}">Actualizar libro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form method="POST" action="{{ route('libros.update', $libro) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="nombre{{ $libro->id }}" class="form-label">Nombre del libro</label>
            <input type="text" id="nombre{{ $libro->id }}" name="nombre" value="{{ $libro->nombre }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="descripcion{{ $libro->id }}" class="form-label">Descripción</label>
            <input type="text" id="descripcion{{ $libro->id }}" name="descripcion" value="{{ $libro->descripcion }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="autor{{ $libro->id }}" class="form-label">Autor</label>
            <input type="text" id="autor{{ $libro->id }}" name="autor" value="{{ $libro->autor }}" class="form-control" required>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
