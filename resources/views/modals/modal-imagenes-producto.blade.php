{{-- Modal de Carrusel de Imágenes del Producto --}}
<div class="modal fade" id="modalImagenesProducto" tabindex="-1" role="dialog" aria-labelledby="modalImagenesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalImagenesLabel">
                    <i class="fas fa-images"></i> Imágenes del Producto
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="cerrarModalImagenes()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <div id="carouselImagenesProducto" class="carousel slide" data-interval="false">
                    <div class="carousel-inner" id="carouselImagenesContent" style="min-height: 400px; background: #000;">
                        {{-- Las imágenes se cargarán dinámicamente aquí --}}
                        <div class="text-center p-5">
                            <i class="fas fa-spinner fa-spin fa-3x text-white"></i>
                            <p class="mt-2 text-white">Cargando imágenes...</p>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselImagenesProducto" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselImagenesProducto" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                </div>
                <div class="text-center mt-3">
                    <span id="contadorImagenes" class="badge badge-info badge-lg" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-images"></i> Cargando...
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrarModalImagenes()">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cerrarModalImagenes() {
    // Detener el carrusel
    $('#carouselImagenesProducto').carousel('dispose');
    // Cerrar el modal
    $('#modalImagenesProducto').modal('hide');
}
</script>
