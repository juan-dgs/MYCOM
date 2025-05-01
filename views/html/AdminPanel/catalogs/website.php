<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<style>
    /* Estilos generales */
    .panel-body {
        padding: 15px;
    }
    
    /* Estilos para el mapa */
    #map-container {
        height: 300px;
        margin-top: 10px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    
    #map {
        height: 100%;
        width: 100%;
    }
    
    .map-controls {
        margin-top: 8px;
        display: flex;
        gap: 8px;
    }
    
    /* Estilos para formularios */
    .form-section {
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 12px;
    }
    
    /* Estilos para redes sociales */
    .social-item {
        margin-bottom: 12px;
    }
    
    .btn-add-social {
        margin-top: 8px;
    }
    
    /* Ajustes de layout */
    .left-column {
        padding-right: 10px;
    }
    
    .right-column {
        padding-left: 10px;
    }
    
    @media (max-width: 992px) {
        .left-column, 
        .right-column {
            padding: 0;
        }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Configuración de Contacto y Redes Sociales</h3>
                <div class="panel-options">
                    <span id="lastSaved" class="text-muted small" style="margin-right: 10px;"></span>
                </div>
            </div>
            <div class="panel-body">
                <form id="contactForm">
                    <div class="row">
                        <!-- Columna Izquierda -->
                        <div class="col-md-6 left-column">
                            <!-- Sección de Domicilio -->
                            <div class="panel panel-default form-section">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Domicilio</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="domicilio">Dirección Completa</label>
                                        <textarea id="domicilio" class="form-control" name="domicilio" placeholder="Ingrese la dirección completa" rows="2"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="maps_link">Enlace de Google Maps</label>
                                        <input type="text" id="maps_link" class="form-control" name="maps_link" placeholder="https://goo.gl/maps/...">
                                    </div>
                                    
                                    <div id="map-container">
                                        <div id="map"></div>
                                        <div class="map-controls">
                                            <button type="button" id="findAddress" class="btn btn-sm btn-default">
                                                <i class="fas fa-search"></i> Buscar dirección
                                            </button>
                                            <button type="button" id="useCurrentLocation" class="btn btn-sm btn-default">
                                                <i class="fas fa-location-arrow"></i> Usar mi ubicación
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección de Contactos -->
                            <div class="panel panel-default form-section">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Contactos</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="contacto1">Contacto Principal</label>
                                        <input type="text" id="contacto1" class="form-control" name="contacto1" placeholder="Nombre, teléfono, email">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contacto2">Contacto Secundario</label>
                                        <input type="text" id="contacto2" class="form-control" name="contacto2" placeholder="Información adicional de contacto">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="text" id="email" class="form-control" name="email" placeholder="correo@ejemplo.com">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna Derecha -->
                        <div class="col-md-6 right-column">
                            <!-- Sección de Horario -->
                            <div class="panel panel-default form-section">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Horario de Atención</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="atencion">Horario de atención</label>
                                        <textarea id="atencion" class="form-control" name="atencion" placeholder="Ejemplo: Lunes a Viernes de 9:00 a 18:00" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección de Redes Sociales -->
                            <div class="panel panel-default form-section">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Redes Sociales</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="social-container">
                                        <!-- Facebook -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fab fa-facebook-f"></i>
                                                </span>
                                                <input type="text" class="form-control" name="social[facebook]" placeholder="URL de Facebook">
                                            </div>
                                        </div>
                                        
                                        <!-- Instagram -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fab fa-instagram"></i>
                                                </span>
                                                <input type="text" class="form-control" name="social[instagram]" placeholder="URL de Instagram">
                                            </div>
                                        </div>
                                        
                                        <!-- WhatsApp -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fab fa-whatsapp"></i>
                                                </span>
                                                <input type="text" class="form-control" name="social[whatsapp]" placeholder="Número de WhatsApp">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" id="addSocial" class="btn btn-default btn-add-social">
                                        <i class="fas fa-plus"></i> Agregar Otra Red Social
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-right" style="margin-top: 15px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Mapa y geolocalización
let map;
let marker;

$(document).ready(function() {
    // Inicializar el mapa
    initMap();
    
    // Cargar datos existentes
    cargarDatosContacto();
    
    // Configurar evento click en el mapa
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateAddressFromMarker(e.latlng);
    });
    
    // Buscar dirección en el mapa
    $('#findAddress').click(function() {
        const address = $('#domicilio').val().trim();
        geocodeAddress(address);
    });
    
    // Usar ubicación actual
    $('#useCurrentLocation').click(function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const latlng = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    updateMapLocation(latlng);
                    updateAddressFromMarker(latlng);
                },
                error => {
                    console.error("Error al obtener la ubicación:", error);
                }
            );
        }
    });
    
    // Agregar nueva red social
    $('#addSocial').click(function() {
        const newId = 'custom_' + Date.now();
        const newSocial = `
            <div class="form-group social-item">
                <div class="input-group">
                    <select class="form-control social-type-select" name="social[${newId}][type]">
                        <option value="">Seleccione red social</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="twitter">Twitter</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="youtube">YouTube</option>
                        <option value="tiktok">TikTok</option>
                    </select>
                    <span class="input-group-addon">
                        <i class="fas fa-link"></i>
                    </span>
                    <input type="text" class="form-control" name="social[${newId}][url]" placeholder="URL o información">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger remove-social">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                </div>
            </div>
        `;
        
        $('#social-container').append(newSocial);
        $(`select[name="social[${newId}][type]"]`).focus();
    });
    
    // Eliminar red social
    $(document).on('click', '.remove-social', function() {
        $(this).closest('.social-item').remove();
    });
    
    // Cambiar ícono según tipo de red social
    $(document).on('change', '.social-type-select', function() {
        const type = $(this).val();
        const icon = $(this).closest('.input-group').find('.input-group-addon i');
        const iconClass = type === 'facebook' ? 'fab fa-facebook-f' :
                          type === 'instagram' ? 'fab fa-instagram' :
                          type === 'whatsapp' ? 'fab fa-whatsapp' :
                          type === 'twitter' ? 'fab fa-twitter' :
                          type === 'linkedin' ? 'fab fa-linkedin-in' :
                          type === 'youtube' ? 'fab fa-youtube' :
                          type === 'tiktok' ? 'fab fa-tiktok' : 'fas fa-link';
        icon.attr('class', iconClass);
    });
    
    // Enviar formulario
    $('#contactForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serializeArray();
        
        $('#contactForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        $.ajax({
            url: "ajax.php?mode=guardarcontacto",
            type: "POST",
            data: $.param(formData),
            dataType: "json",
            success: function(response) {
                if(response.codigo == 1) {
                    notify("Datos actualizados correctamente", 1500, "success", "top-end");
                    updateLastSaved();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            },
            complete: function() {
                $('#contactForm button[type="submit"]').html('<i class="fas fa-save"></i> Guardar Cambios');
            }
        });
    });
});

function initMap() {
    map = L.map('map').setView([19.4326, -99.1332], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    marker = L.marker(map.getCenter(), {draggable: true}).addTo(map);
    
    marker.on('dragend', function() {
        updateAddressFromMarker(marker.getLatLng());
    });
}

function updateMapLocation(latlng) {
    map.setView(latlng, 15);
    marker.setLatLng(latlng);
}

function updateAddressFromMarker(latlng) {
    $('#maps_link').val(`https://www.google.com/maps?q=${latlng.lat},${latlng.lng}`);
    
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const address = data.address;
                let fullAddress = '';
                
                if (address.road) fullAddress += address.road;
                if (address.house_number) fullAddress += ` ${address.house_number},`;
                if (address.neighbourhood) fullAddress += ` ${address.neighbourhood},`;
                if (address.suburb) fullAddress += ` ${address.suburb},`;
                if (address.city) fullAddress += ` ${address.city},`;
                if (address.state) fullAddress += ` ${address.state},`;
                if (address.country) fullAddress += ` ${address.country}`;
                
                fullAddress = fullAddress.replace(/,+/g, ',').replace(/,\s*$/, '');
                $('#domicilio').val(fullAddress);
            }
        })
        .catch(error => {
            console.error("Error en geocodificación inversa:", error);
        });
}

function geocodeAddress(address) {
    if (!address) return;
    
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const latlng = {
                    lat: parseFloat(data[0].lat),
                    lng: parseFloat(data[0].lon)
                };
                updateMapLocation(latlng);
                updateAddressFromMarker(latlng);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
}

// Función para cargar datos
function cargarDatosContacto() {
    $.ajax({
        url: "ajax.php?mode=getcontacto",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if(response.codigo === 1 && response.data) {
                $('#domicilio').val(response.data.domicilio || '');
                $('#maps_link').val(response.data.maps_link || '');
                $('#contacto1').val(response.data.contacto1 || '');
                $('#contacto2').val(response.data.contacto2 || '');
                $('#email').val(response.data.email || '');
                $('#atencion').val(response.data.atencion || '');
                
                // Redes sociales
                if(response.data.social) {
                    $('input[name="social[facebook]"]').val(response.data.social.facebook || '');
                    $('input[name="social[instagram]"]').val(response.data.social.instagram || '');
                    $('input[name="social[whatsapp]"]').val(response.data.social.whatsapp || '');
                }
                
                // Mapa
                if(response.data.maps_link) {
                    const coords = extractCoordsFromUrl(response.data.maps_link);
                    if(coords) updateMapLocation(coords);
                }
                
                updateLastSaved();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar datos:", error);
        }
    });
}

// Función para cargar datos
function cargarDatosContacto() {
    $.ajax({
        url: "ajax.php?mode=getcontacto",
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta del servidor:", response); // Para depuración
            
            if(response && response.codigo === 1 && response.data) {
                // Campos básicos
                $('#domicilio').val(response.data.domicilio || '');
                $('#maps_link').val(response.data.maps_link || '');
                $('#contacto1').val(response.data.contacto1 || '');
                $('#contacto2').val(response.data.contacto2 || '');
                $('#email').val(response.data.email || '');
                $('#atencion').val(response.data.atencion || '');
                
                // Redes sociales (con compatibilidad hacia atrás)
                const socialData = response.data.social || {
                    facebook: response.data['social[facebook]'] || '',
                    instagram: response.data['social[instagram]'] || '',
                    whatsapp: response.data['social[whatsapp]'] || ''
                };
                
                $('input[name="social[facebook]"]').val(socialData.facebook || '');
                $('input[name="social[instagram]"]').val(socialData.instagram || '');
                $('input[name="social[whatsapp]"]').val(socialData.whatsapp || '');
                
                // Mapa
                if(response.data.maps_link) {
                    const coords = extractCoordsFromUrl(response.data.maps_link);
                    if(coords) updateMapLocation(coords);
                }
                
                updateLastSaved();
            } else {
                console.error("Respuesta inesperada del servidor:", response);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud:", {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            
            // Mostrar error al usuario
            notify("Error al cargar datos. Por favor recarga la página.", 3000, "error");
        }
    });
}

// Función para guardar datos
$('#contactForm').submit(function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

    // Preparar datos
    const formData = {
        domicilio: $('#domicilio').val().trim(),
        contacto1: $('#contacto1').val().trim(),
        contacto2: $('#contacto2').val().trim(),
        email: $('#email').val().trim(),
        atencion: $('#atencion').val().trim(),
        maps_link: $('#maps_link').val().trim(),
        social: {
            facebook: $('input[name="social[facebook]"]').val().trim(),
            instagram: $('input[name="social[instagram]"]').val().trim(),
            whatsapp: $('input[name="social[whatsapp]"]').val().trim()
        }
    };

    // Enviar datos
    $.ajax({
        url: "ajax.php?mode=guardarcontacto",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(formData),
        dataType: "json",
        success: function(response) {
            console.log("Respuesta de guardado:", response); // Para depuración
            
            if(response && response.codigo === 1) {
                notify("Datos guardados correctamente", 2000, "success");
                updateLastSaved();
            } else {
                const errorMsg = response?.alerta || "Error desconocido al guardar";
                notify(errorMsg, 3000, "error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud:", {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            
            let errorMsg = "Error de conexión con el servidor";
            
            // Intentar extraer mensaje de error si viene en JSON
            try {
                const serverResponse = JSON.parse(xhr.responseText);
                if(serverResponse && serverResponse.alerta) {
                    errorMsg = serverResponse.alerta;
                }
            } catch(e) {
                console.error("No se pudo parsear la respuesta del servidor:", e);
            }
            
            notify(errorMsg, 3000, "error");
        },
        complete: function() {
            submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Cambios');
        }
    });
});


function extractCoordsFromUrl(url) {
    if (!url) return null;
    const match = url.match(/q=([-+]?\d*\.\d+|\d+),([-+]?\d*\.\d+|\d+)/);
    if (match && match.length >= 3) {
        return {
            lat: parseFloat(match[1]),
            lng: parseFloat(match[2])
        };
    }
    return null;
}

function updateLastSaved() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    $('#lastSaved').html('Última actualización: ' + timeString);
}

function notify(message, duration, type, position) {
    const Toast = Swal.mixin({
        toast: true,
        position: position || 'top-end',
        showConfirmButton: false,
        timer: duration || 3000,
        timerProgressBar: true
    });
    
    Toast.fire({
        icon: type || 'success',
        title: message
    });
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>