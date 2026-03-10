<!-- Banner de estado de notificaciones -->
<div id="notification-banner" style="display: none; position: fixed; top: 60px; right: 20px; z-index: 9999; max-width: 350px;">
    <div class="alert alert-warning alert-dismissible fade show shadow-lg" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="sessionStorage.setItem('notification-banner-dismissed', 'true')">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="d-flex align-items-center">
            <i class="fas fa-bell-slash fa-2x mr-3 text-warning"></i>
            <div>
                <h6 class="mb-1"><strong>Notificaciones Desactivadas</strong></h6>
                <p class="mb-2 small">Actívalas para recibir mensajes incluso cuando estés en otra pestaña.</p>
                <button class="btn btn-sm btn-warning mr-1" onclick="activateNotifications()">
                    <i class="fas fa-bell"></i> Activar Ahora
                </button>
                <a href="{{ route('notifications.activar') }}" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-question-circle"></i> Ayuda
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Verificar estado de notificaciones y mostrar banner si es necesario
(function checkNotificationStatus() {
    if (!('Notification' in window)) {
        console.log('Notificaciones no soportadas en este navegador');
        return;
    }

    const permission = Notification.permission;
    const banner = document.getElementById('notification-banner');

    // Mostrar banner si el permiso es 'default' (no concedido ni negado)
    if (permission === 'default') {
        // Verificar si ya se cerró manualmente en esta sesión
        const dismissedThisSession = sessionStorage.getItem('notification-banner-dismissed');

        if (!dismissedThisSession) {
            // Mostrar después de 3 segundos
            setTimeout(() => {
                banner.style.display = 'block';
            }, 3000);
        }
    } else if (permission === 'granted') {
        console.log('✅ Notificaciones ya activadas');
    } else if (permission === 'denied') {
        console.log('❌ Notificaciones bloqueadas por el usuario');
    }
})();

// Función para activar notificaciones
async function activateNotifications() {
    try {
        const permission = await Notification.requestPermission();

        if (permission === 'granted') {
            // Ocultar banner
            document.getElementById('notification-banner').style.display = 'none';

            // Mostrar notificación de bienvenida
            if (typeof window.hyplastNotifications !== 'undefined') {
                window.hyplastNotifications.showWelcomeNotification();
            } else {
                new Notification('🎉 ¡Notificaciones Activadas!', {
                    body: 'Recibirás notificaciones de Hyplast',
                    icon: '/images/logo250.png'
                });
            }

            // Mostrar mensaje de éxito
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Notificaciones Activadas',
                    text: 'Ahora recibirás notificaciones incluso con la ventana minimizada',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        } else if (permission === 'denied') {
            // Mostrar cómo activar manualmente
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Notificaciones Bloqueadas',
                    html: `
                        <p>Has bloqueado las notificaciones. Para activarlas:</p>
                        <ol class="text-left">
                            <li>Haz clic en el <strong>candado</strong> en la barra de direcciones</li>
                            <li>Busca <strong>Notificaciones</strong></li>
                            <li>Cambia a <strong>Permitir</strong></li>
                            <li>Recarga la página</li>
                        </ol>
                    `,
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert('Notificaciones bloqueadas. Actívalas en la configuración del navegador.');
            }
        }
    } catch (error) {
        console.error('Error solicitando permiso:', error);
    }
}
</script>
