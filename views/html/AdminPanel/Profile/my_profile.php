<?php
// Iniciar la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificación  de sesión
$user_id = null;
if (!empty($_SESSION['USER_ID'])) {
    $user_id = $_SESSION['USER_ID'];
} elseif (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die("<div style='padding:40px;font-family:Arial;text-align:center;color:#dc3545;'>
            <i class='fas fa-exclamation-triangle fa-3x'></i>
            <h2>Error de autenticación</h2>
            <p>No se pudo verificar tu sesión. Por favor:</p>
            <ol style='text-align:left;max-width:400px;margin:20px auto;'>
                <li>Inicia sesión nuevamente</li>
                <li>Verifica que las cookies estén habilitadas</li>
                <li>Contacta al administrador si el problema persiste</li>
            </ol>
            <a href='login.php' style='color:#fff;background:#dc3545;padding:10px 20px;border-radius:5px;text-decoration:none;display:inline-block;margin-top:20px;'>
                Volver a login
            </a>
         </div>");
}

// Incluir archivos necesarios
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<style>
    :root {
        --primary: #3897f0;
        --secondary: #fafafa;
        --text: #262626;
        --text-light: #8e8e8e;
        --white: #ffffff;
        --border: #dbdbdb;
        --radius: 8px;
        --error: #dc3545;
    }
    
    body {
        background-color: var(--secondary);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        margin: 0;
        padding: 0;
    }
    
    .profile-container {
        max-width: 935px;
        margin: 30px auto;
        padding: 0 20px;
    }
    
    .profile-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .profile-header {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
        align-items: center;
    }
    
    .profile-photo-container {
        flex: 0 0 150px;
        margin-right: 30px;
        text-align: center;
    }
    
    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--white);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .profile-info {
        flex: 1;
        min-width: 250px;
    }
    
    .profile-name {
        font-size: 28px;
        font-weight: 300;
        margin: 0 0 5px 0;
        color: var(--text);
    }
    
    .profile-username {
        color: var(--text-light);
        font-size: 16px;
        margin: 0 0 20px 0;
    }
    
    .profile-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .detail-item {
        display: flex;
        align-items: flex-start;
    }
    
    .detail-icon {
        width: 24px;
        height: 24px;
        margin-right: 10px;
        color: var(--primary);
        text-align: center;
        margin-top: 3px;
    }
    
    .detail-label {
        font-size: 14px;
        color: var(--text-light);
        margin: 0;
    }
    
    .detail-value {
        font-size: 16px;
        color: var(--text);
        margin: 3px 0 0 0;
        word-break: break-word;
    }
    
    .profile-actions {
        margin-top: 25px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .profile-btn {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 8px 16px;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
    }
    
    .profile-btn:hover {
        background: #f0f0f0;
        text-decoration: none;
    }
    
    .profile-btn i {
        margin-right: 8px;
    }
    
    .loading {
        text-align: center;
        padding: 50px 0;
        color: var(--text-light);
    }
    
    .error-container {
        text-align: center;
        padding: 40px 20px;
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        max-width: 500px;
        margin: 30px auto;
    }
    
    .error-icon {
        color: var(--error);
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    .error-title {
        color: var(--error);
        margin-bottom: 15px;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-photo-container {
            margin-right: 0;
            margin-bottom: 20px;
        }
        
        .profile-details {
            grid-template-columns: 1fr;
        }
        
        .detail-item {
            justify-content: center;
        }
        
        .profile-actions {
            justify-content: center;
        }
    }
</style>

<div class="profile-container">
    <div id="profile-content">
        <div class="loading">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Cargando tu perfil...</p>
        </div>
    </div>
</div>

<script>
// Función para cargar los datos del perfil
function loadUserProfile() {
    $.ajax({
        url: "ajax.php?mode=getregister",
        type: "POST",
        data: {
            tabla: "users",
            campoId: "id",
            datoId: "<?php echo $user_id; ?>"
        },
        dataType: "json",
        beforeSend: function() {
            $('#profile-content').html(`
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Cargando tu perfil...</p>
                </div>
            `);
        },
        success: function(response) {
            if(response && response[1]) {
                renderProfile(response[1]);
            } else {
                const errorMsg = response && response.ERROR ? response.ERROR : "No se pudieron cargar los datos del perfil";
                showError(errorMsg);
            }
        },
        error: function(xhr, status, error) {
            let errorMsg = "Error al conectar con el servidor";
            if(xhr.responseJSON && xhr.responseJSON.ERROR) {
                errorMsg = xhr.responseJSON.ERROR;
            } else if(error) {
                errorMsg = error;
            }
            showError(errorMsg);
        }
    });
}

// Función para mostrar el perfil
function renderProfile(userData) {
    // Datos por defecto para evitar errores
    userData = userData || {};
    const fullName = `${userData.nombre || ''} ${userData.apellido_p || ''} ${userData.apellido_m || ''}`.trim();
    const username = userData.usuario ? `@${userData.usuario}` : '';
    const email = userData.correo || 'No especificado';
    const userType = userData.c_tipo_usuario || 'No especificado';
    const joinDate = formatDate(userData.f_registro);
    const profilePhoto = userData.dir_foto ? `${userData.dir_foto}` : `views/images/profile/`;

    const profileHTML = `
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-photo-container">
                    <img src="${profilePhoto}" 
                         alt="Foto de perfil" 
                         class="profile-photo"
                         onerror="this.src='views/images/profile/'">
                </div>
                
                <div class="profile-info">
                    <h1 class="profile-name">${escapeHtml(fullName)}</h1>
                    ${username ? `<p class="profile-username">${escapeHtml(username)}</p>` : ''}
                    
                    <div class="profile-details">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="detail-label">Correo electrónico</p>
                                <p class="detail-value">${escapeHtml(email)}</p>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <div>
                                <p class="detail-label">Tipo de usuario</p>
                                <p class="detail-value">${escapeHtml(userType)}</p>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="detail-label">Miembro desde</p>
                                <p class="detail-value">${joinDate}</p>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    `;
    
    $('#profile-content').html(profileHTML);
}

// Función para mostrar errores
function showError(message) {
    $('#profile-content').html(`
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="error-title">Ocurrió un error</h3>
            <p>${escapeHtml(message)}</p>
            <button class="profile-btn" onclick="loadUserProfile()" style="margin-top:20px;">
                <i class="fas fa-sync-alt"></i> Reintentar
            </button>
        </div>
    `);
}

/* Función para editar perfil
function editProfile() {
    const userId = "<?php echo $user_id; ?>";
    const userName = $("#profile-content .profile-name").text().trim();
    if (typeof GetUser === 'function') {
        GetUser(userId, userName);
    } else {
        showError("La función de edición no está disponible");
    }
}

// Función para cambiar contraseña
function changePassword() {
    const userId = "<?php echo $user_id; ?>";
    const userName = $("#profile-content .profile-name").text().trim();
    if (typeof ChangePass === 'function') {
        ChangePass(userId, userName);
    } else {
        showError("La función de cambio de contraseña no está disponible");
    }
}
*/

// Función para escapar HTML
function escapeHtml(unsafe) {
    if (unsafe == null) return '';
    return unsafe.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Función para formatear fecha
function formatDate(dateString) {
    if (!dateString) return 'No disponible';
    try {
        const date = new Date(dateString);
        if (isNaN(date)) return 'No disponible';
        return date.toLocaleDateString('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch (e) {
        return 'No disponible';
    }
}

// Cargar el perfil al iniciar
$(document).ready(function() {
    loadUserProfile();
});
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>