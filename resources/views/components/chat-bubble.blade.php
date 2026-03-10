<!-- Burbuja flotante de chat mejorada con Summernote y Nueva Conversación -->
@php
    $currentRoute = request()->route()->getName();
    $hideInRoutes = ['messages.show', 'messages.index', 'messages.new'];
@endphp

@if(!in_array($currentRoute, $hideInRoutes))
<div id="chat-bubble-container">
    <!-- Botón flotante -->
    <button id="chat-bubble-btn" class="chat-bubble-btn">
        <i class="fas fa-comments"></i>
        <span id="chat-bubble-badge" class="badge badge-danger chat-bubble-badge" style="display: none;">0</span>
    </button>

    <!-- Panel principal: Lista de conversaciones -->
    <div id="chat-panel" class="chat-panel">
        <div class="chat-panel-header">
            <h5><i class="fas fa-comments"></i> Mensajes</h5>
            <button id="chat-panel-close" class="btn btn-sm btn-tool">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-panel-body" id="chat-panel-body">
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin"></i> Cargando...
            </div>
        </div>
        <div class="chat-panel-footer">
            <button id="new-conversation-btn" class="btn btn-primary btn-block btn-sm">
                <i class="fas fa-plus"></i> Nueva Conversación
            </button>
        </div>
    </div>

    <!-- Panel: Nueva Conversación -->
    <div id="chat-new-panel" class="chat-panel chat-new-panel">
        <div class="chat-panel-header">
            <button id="back-from-new-btn" class="btn btn-sm btn-tool mr-2">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h5><i class="fas fa-user-plus"></i> Nueva Conversación</h5>
            <button id="chat-new-close" class="btn btn-sm btn-tool">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-panel-body" id="chat-new-body">
            <input type="text" id="user-search-input" class="form-control form-control-sm mb-2" placeholder="Buscar usuario...">
            <div id="users-list">
                <div class="text-center py-3">
                    <i class="fas fa-spinner fa-spin"></i> Cargando usuarios...
                </div>
            </div>
        </div>
    </div>

    <!-- Panel: Conversación Individual -->
    <div id="chat-conversation-panel" class="chat-panel chat-conversation-panel">
        <div class="chat-panel-header">
            <button id="back-to-list-btn" class="btn btn-sm btn-tool mr-2">
                <i class="fas fa-arrow-left"></i>
            </button>
            <img id="conversation-user-avatar" src="" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px;">
            <h5 id="conversation-user-name" style="margin: 0;">Usuario</h5>
            <button id="chat-conversation-close" class="btn btn-sm btn-tool ml-auto">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-conversation-body" id="chat-conversation-body"></div>
        <div class="chat-panel-footer">
            <form id="quick-message-form" enctype="multipart/form-data">
                <textarea class="form-control" id="quick-message-input" rows="2" placeholder="Escribe tu mensaje..."></textarea>
                <div class="row mt-2">
                    <div class="col-8">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-attach-btn" data-type="image" title="Imagen">
                                <i class="fas fa-image"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-attach-btn" data-type="audio" title="Audio">
                                <i class="fas fa-microphone"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-attach-btn" data-type="video" title="Video">
                                <i class="fas fa-video"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-attach-btn" data-type="file" title="Archivo">
                                <i class="fas fa-paperclip"></i>
                            </button>
                        </div>
                        <input type="file" id="quick-attachment-input" style="display: none;">
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
                <div id="quick-file-preview" class="mt-1"></div>
            </form>
        </div>
    </div>
</div>

<style>
.chat-bubble-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-size: 24px;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
}

.chat-bubble-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

.chat-bubble-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    background-color: #dc3545;
    color: white;
    min-width: 20px;
}

.chat-panel {
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 380px;
    max-height: 600px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 999;
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.chat-panel.active {
    display: flex;
    animation: slideUp 0.3s ease;
}

.chat-conversation-panel, .chat-new-panel {
    right: 420px;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-panel-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.chat-panel-header h5 {
    margin: 0;
    font-size: 16px;
    flex: 1;
}

.chat-panel-header .btn-tool {
    color: white;
}

.chat-panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    max-height: 450px;
}

.chat-conversation-body {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    max-height: 350px;
    background-color: #f8f9fa;
}

.chat-panel-footer {
    padding: 10px;
    border-top: 1px solid #e9ecef;
}

.conversation-item {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    cursor: pointer;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    gap: 10px;
}

.conversation-item:hover {
    background-color: #f8f9fa;
}

.conversation-item img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.conversation-name {
    font-weight: 600;
    margin-bottom: 4px;
    display: flex;
    justify-content: space-between;
}

.conversation-preview {
    font-size: 13px;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-time {
    font-size: 11px;
    color: #adb5bd;
}

.unread-badge {
    background-color: #dc3545;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
}

.user-item {
    padding: 10px;
    border-bottom: 1px solid #e9ecef;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-item:hover {
    background-color: #f8f9fa;
}

.user-item img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-bubble-message {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.chat-bubble-message.own {
    align-items: flex-end;
}

.chat-bubble-message-text {
    max-width: 80%;
    padding: 8px 12px;
    border-radius: 10px;
    word-wrap: break-word;
}

.chat-bubble-message.own .chat-bubble-message-text {
    background-color: #007bff;
    color: white;
}

.chat-bubble-message:not(.own) .chat-bubble-message-text {
    background-color: #e9ecef;
    color: #212529;
}

.chat-bubble-message-time {
    font-size: 10px;
    color: #6c757d;
    margin-top: 2px;
}

#quick-file-preview {
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 5px;
    display: none;
    font-size: 12px;
}

#quick-file-preview.active {
    display: block;
}

#quick-file-preview img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 5px;
}

.note-editor.note-airframe, .note-editor.note-frame {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.note-editable {
    min-height: 80px;
    max-height: 120px;
    font-size: 13px;
}

@media (max-width: 768px) {
    .chat-bubble-btn {
        bottom: 20px;
        right: 20px;
    }
    .chat-panel, .chat-conversation-panel, .chat-new-panel {
        right: 20px;
        left: 20px;
        width: calc(100vw - 40px);
    }
}
</style>

<script>
// Esperar a que jQuery y el DOM estén completamente cargados
(function checkAndInit() {
    'use strict';




    if (typeof jQuery === 'undefined') {
        // Si jQuery no está disponible, esperar 100ms y volver a intentar

        setTimeout(checkAndInit, 100);
        return;
    }

    jQuery(document).ready(function($) {





        // Verificar que el botón existe
        if (!$('#chat-bubble-btn').length) {


            return;
        }



        let currentConversationId = null;
        let selectedFile = null;
        let messageInterval = null;
        let allUsers = [];
        let conversationListInterval = null;
        let chatPanelIsOpen = false;
        let previousConversations = {}; // Para comparar mensajes nuevos
        let lastUnreadTotal = 0; // Contador global de no leídos

        const chatBubbleBtn = $('#chat-bubble-btn');
        const chatPanel = $('#chat-panel');
        const chatPanelClose = $('#chat-panel-close');
        const chatPanelBody = $('#chat-panel-body');
        const chatBubbleBadge = $('#chat-bubble-badge');






        if (chatBubbleBtn.length === 0) {

            return;
        }

        const newPanel = $('#chat-new-panel');
        const newConversationBtn = $('#new-conversation-btn');
        const backFromNewBtn = $('#back-from-new-btn');
        const chatNewClose = $('#chat-new-close');
        const userSearchInput = $('#user-search-input');
        const usersList = $('#users-list');

        const conversationPanel = $('#chat-conversation-panel');
        const conversationClose = $('#chat-conversation-close');
        const conversationBody = $('#chat-conversation-body');
        const conversationUserName = $('#conversation-user-name');
        const conversationUserAvatar = $('#conversation-user-avatar');
        const backToListBtn = $('#back-to-list-btn');

        const quickMessageForm = $('#quick-message-form');
        const quickAttachmentInput = $('#quick-attachment-input');
        const quickFilePreview = $('#quick-file-preview');
        const quickMessageInput = $('#quick-message-input');

        // Toggle chat panel

        chatBubbleBtn.on('click', function(e) {

            e.stopPropagation();
            if (chatPanel.hasClass('active')) {

                chatPanel.removeClass('active');
                chatPanelIsOpen = false;
                // Detener actualización automática de lista
                if (conversationListInterval) {
                    clearInterval(conversationListInterval);
                    conversationListInterval = null;
                }
            } else {

                chatPanel.addClass('active');
                chatPanelIsOpen = true;
                loadConversations();
                // Al abrir el chat, actualizar badge inmediatamente
                setTimeout(updateUnreadCount, 500);
                // Iniciar actualización automática de lista cada 5 segundos
                if (conversationListInterval) clearInterval(conversationListInterval);
                conversationListInterval = setInterval(() => {
                    if (chatPanelIsOpen && !conversationPanel.hasClass('active')) {
                        loadConversations();
                    }
                }, 5000);
            }
        });

        // Cerrar paneles
        chatPanelClose.on('click', closeAllPanels);
        conversationClose.on('click', closeConversationPanel);
        chatNewClose.on('click', closeNewPanel);

        // Botón nueva conversación
        newConversationBtn.on('click', function() {
            newPanel.addClass('active');
            loadUsers();
        });

        // Volver
        backFromNewBtn.on('click', closeNewPanel);
        backToListBtn.on('click', closeConversationPanel);

        // Buscar usuarios
        userSearchInput.on('input', function() {
            const search = $(this).val().toLowerCase();
            const filtered = allUsers.filter(u =>
                u.name.toLowerCase().includes(search) ||
                u.email.toLowerCase().includes(search)
            );
            renderUsers(filtered);
        });

        // Adjuntar archivos
        $('.quick-attach-btn').on('click', function() {
            const type = $(this).data('type');
            let accept = '';
            if (type === 'image') accept = 'image/*';
            else if (type === 'audio') accept = 'audio/*';
            else if (type === 'video') accept = 'video/*';
            else accept = '*';
            quickAttachmentInput.attr('accept', accept).click();
        });

        // Preview archivo
        quickAttachmentInput.on('change', function() {
            const file = this.files[0];
            if (file) {
                selectedFile = file;
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        quickFilePreview.addClass('active').html(`
                            <img src="${e.target.result}">
                            <strong>${file.name}</strong>
                            <button type="button" class="btn btn-sm btn-link text-danger" onclick="window.removeQuickFile()">
                                <i class="fas fa-times"></i>
                            </button>
                        `);
                    };
                    reader.readAsDataURL(file);
                } else {
                    let icon = 'fa-file';
                    if (file.type.startsWith('audio/')) icon = 'fa-microphone';
                    else if (file.type.startsWith('video/')) icon = 'fa-video';
                    quickFilePreview.addClass('active').html(`
                        <i class="fas ${icon}"></i>
                        <strong>${file.name}</strong>
                        <button type="button" class="btn btn-sm btn-link text-danger" onclick="window.removeQuickFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    `);
                }
            }
            quickAttachmentInput.val('');
        });

        window.removeQuickFile = function() {
            selectedFile = null;
            quickFilePreview.removeClass('active').html('');
        };

        // Enviar mensaje
        quickMessageForm.on('submit', function(e) {
            e.preventDefault();
            const message = quickMessageInput.val().trim();

            if (!message && !selectedFile) {
                alert('Escribe un mensaje o adjunta un archivo');
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('message', message);
            if (selectedFile) formData.append('attachment', selectedFile);

            $.ajax({
                url: `/messages/${currentConversationId}/send`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        quickMessageInput.val('');
                        window.removeQuickFile();
                        loadConversationMessages(currentConversationId);
                    }
                },
                error: function() {
                    alert('Error al enviar');
                }
            });
        });

        // Funciones auxiliares
        function closeAllPanels() {
            chatPanel.removeClass('active');
            conversationPanel.removeClass('active');
            newPanel.removeClass('active');
            chatPanelIsOpen = false;
            if (messageInterval) clearInterval(messageInterval);
            if (conversationListInterval) {
                clearInterval(conversationListInterval);
                conversationListInterval = null;
            }
        }

        function closeConversationPanel() {
            conversationPanel.removeClass('active');
            if (messageInterval) clearInterval(messageInterval);
            // Recargar lista para actualizar badges
            loadConversations();
            updateUnreadCount();
        }

        function closeNewPanel() {
            newPanel.removeClass('active');
        }

        function loadConversations() {
            $.get('{{ route("messages.json") }}', function(convs) {
                if (!convs.length) {
                    chatPanelBody.html('<div class="text-center py-4"><i class="fas fa-comments"></i><p>Sin conversaciones</p></div>');
                } else {
                    let html = '';
                    convs.forEach(c => {
                        // Validar que last_message existe antes de acceder a sus propiedades
                        const preview = c.last_message?.text ? c.last_message.text.substring(0, 40) : 'Sin mensajes aún';
                        const timeAgo = c.last_message?.time_ago || '';
                        const avatarUrl = c.other_user.avatar || '/images/avatar.png';

                        html += `
                            <div class="conversation-item" data-id="${c.id}" data-name="${c.other_user.name}" data-avatar="${avatarUrl}">
                                <img src="${avatarUrl}" alt="${c.other_user.name}">
                                <div style="flex: 1;">
                                    <div class="conversation-name">
                                        <span>${c.other_user.name}</span>
                                        ${c.unread_count > 0 ? `<span class="unread-badge">${c.unread_count}</span>` : ''}
                                    </div>
                                    ${preview ? `<div class="conversation-preview">${preview}</div>` : ''}
                                    ${timeAgo ? `<div class="conversation-time">${timeAgo}</div>` : ''}
                                </div>
                            </div>
                        `;
                    });
                    chatPanelBody.html(html);
                    $('.conversation-item').on('click', function() {
                        openConversation(
                            $(this).data('id'),
                            $(this).data('name'),
                            $(this).data('avatar')
                        );
                    });
                }
            });
        }

        function loadUsers() {
            $.get('{{ route("messages.users-json") }}', function(users) {
                allUsers = users;
                renderUsers(users);
            });
        }

        function renderUsers(users) {
            if (!users.length) {
                usersList.html('<div class="text-center py-3">No hay usuarios disponibles</div>');
            } else {
                let html = '';
                users.forEach(u => {
                    const avatarUrl = u.avatar || '/images/avatar.png';
                    html += `
                        <div class="user-item" data-id="${u.id}">
                            <img src="${avatarUrl}" alt="${u.name}">
                            <div>
                                <strong>${u.name}</strong><br>
                                <small class="text-muted">${u.email}</small>
                            </div>
                        </div>
                    `;
                });
                usersList.html(html);
                $('.user-item').on('click', function() {
                    createConversation($(this).data('id'));
                });
            }
        }

        function createConversation(userId) {
            $.ajax({
                url: '{{ route("messages.create") }}',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({ user_id: userId }),
                success: function() {
                    closeNewPanel();
                    loadConversations();
                },
                error: function() {
                    closeNewPanel();
                    loadConversations();
                }
            });
        }

        function openConversation(id, name, avatar) {
            currentConversationId = id;
            conversationUserName.text(name);
            const avatarUrl = avatar || '/images/avatar.png';
            conversationUserAvatar.attr('src', avatarUrl);
            conversationPanel.addClass('active');

            // Marcar mensajes como leídos inmediatamente
            markConversationAsRead(id);

            // Cargar mensajes
            loadConversationMessages(id);

            // Actualizar badge y lista inmediatamente
            setTimeout(() => {
                updateUnreadCount();
                loadConversations();
            }, 500);

            if (messageInterval) clearInterval(messageInterval);
            messageInterval = setInterval(() => {
                loadConversationMessages(id);
                // También actualizar badge y lista periódicamente
                updateUnreadCount();
                loadConversations();
            }, 3000);
        }

        function markConversationAsRead(id) {
            $.ajax({
                url: `/messages/${id}/mark-read`,
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function() {

                },
                error: function(error) {

                }
            });
        }

        function loadConversationMessages(id) {
            $.get(`/messages/${id}/messages`, function(msgs) {
                let html = '';
                msgs.forEach(m => {
                    const isOwn = m.from == {{ Auth::id() }};
                    let content = '';
                    if (m.message_type === 'text') {
                        content = m.message;
                    } else if (m.message_type === 'image') {
                        content = `<a href="${m.attachment_url}" target="_blank"><img src="${m.attachment_url}" style="max-width: 200px; border-radius: 5px;"></a>`;
                        if (m.message) content += `<br><small>${m.message}</small>`;
                    } else if (m.message_type === 'audio') {
                        content = `<audio controls style="max-width: 100%;"><source src="${m.attachment_url}"></audio>`;
                        if (m.message) content += `<br><small>${m.message}</small>`;
                    } else if (m.message_type === 'video') {
                        content = `<video controls style="max-width: 200px; border-radius: 5px;"><source src="${m.attachment_url}"></video>`;
                        if (m.message) content += `<br><small>${m.message}</small>`;
                    } else if (m.message_type === 'file') {
                        content = `<a href="${m.attachment_url}" target="_blank"><i class="fas fa-file"></i> ${m.attachment_name}</a>`;
                        if (m.message) content += `<br><small>${m.message}</small>`;
                    }

                    html += `
                        <div class="chat-bubble-message ${isOwn ? 'own' : ''}">
                            <div class="chat-bubble-message-text">${content}</div>
                            <div class="chat-bubble-message-time">${m.time_ago}</div>
                        </div>
                    `;
                });
                conversationBody.html(html);
                conversationBody.scrollTop(conversationBody.prop('scrollHeight'));
            });
        }

        function updateUnreadCount() {

            try {

                $.get('{{ route("messages.json") }}', function(convs) {

                    try {
                        // Actualizar contador de badge
                        const total = convs.reduce((sum, c) => sum + (c.unread_count || 0), 0);
                        if (total > 0) {
                            chatBubbleBadge.text(total).show();
                        } else {
                            chatBubbleBadge.hide();
                        }

                        // Verificar mensajes NUEVOS por ID (sin importar si están leídos o no)
                        if (typeof window.hyplastNotifications !== 'undefined') {


                            convs.forEach(function(conv) {
                                const currentMessageId = conv.last_message ? conv.last_message.id : null;
                                const prevConv = previousConversations[conv.id];
                                const previousMessageId = prevConv ? prevConv.last_message_id : null;



                                // Si hay un ID nuevo, es un mensaje NUEVO
                                if (currentMessageId && currentMessageId !== previousMessageId) {
                                    const senderName = conv.other_user?.name || 'Usuario';
                                    const messageText = conv.last_message?.text || 'Nuevo mensaje';





                                    // SIEMPRE enviar notificación
                                    // El sistema de notificaciones decide si mostrarla o no
                                    const notificationShown = window.hyplastNotifications.notifyNewMessage(
                                        messageText,
                                        senderName
                                    );

                                    // Actualizar ID guardado SOLO si se intentó notificar
                                    // (Aunque no se muestre por estar visible, el mensaje ya fue procesado)
                                    previousConversations[conv.id] = {
                                        last_message_id: currentMessageId
                                    };

                                } else if (currentMessageId === previousMessageId) {

                                } else if (!currentMessageId) {

                                }
                            });


                        }

                        // Actualizar contador global
                        lastUnreadTotal = total;

                    } catch (innerError) {

                    }
                }).fail(function(xhr, status, error) {



                });
            } catch (outerError) {

            }
        }

        // Ejecutar primera verificación




        // Hacer la función global para debugging
        window.updateUnreadCount = updateUnreadCount;
        window.forceCheckMessages = function() {

            updateUnreadCount();
        };

        try {

            updateUnreadCount();

        } catch (error) {


        }

        // Configurar intervalo con manejo de errores
        const intervalId = setInterval(function() {
            try {

                updateUnreadCount();
            } catch (error) {


            }
        }, 15000);



    });
})(); // Fin de checkAndInit - se auto-ejecuta
</script>
@endif
