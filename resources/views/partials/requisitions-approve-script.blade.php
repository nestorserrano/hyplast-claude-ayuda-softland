<script>
    /**
     * Función para aprobar requisición desde la vista show
     * @param {number} id - ID de la requisición a aprobar
     */
    function approveRequisition(id) {
        swal({
            title: "¿Aprobar Orden de Producción?",
            text: "Por favor confirme que desea aprobar esta orden. Una vez aprobada, se podrá iniciar la producción.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, Aprobar",
            cancelButtonText: "No, Cancelar",
            reverseButtons: true
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{url('/requisitions/approve')}}/" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            swal("¡Aprobada!", results.message, "success").then(function() {
                                location.reload();
                            });
                        } else {
                            swal("Advertencia", results.message, "warning");
                        }
                    },
                    error: function (xhr, status, error) {
                        swal("Error", "No se pudo aprobar la requisición", "error");
                    }
                });
            }
        });
    }

    /**
     * Función para aprobar requisición desde la tabla/home
     * @param {number} id - ID de la requisición a aprobar
     */
    function approveRequisitionTable(id) {
        // Usa la misma lógica que approveRequisition
        approveRequisition(id);
    }
</script>
