<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<!-- Incluir Leaflet CSS y JS para el mapa -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<style>
    /* Estilos generales */
    .panel-body {
        padding: 20px;
    }
    
    /* Estilos para el horario - Modificado para acordeón */
    .schedule-container {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .schedule-day {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    
    .day-header {
        padding: 12px 15px;
        background-color: #f5f5f5;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .day-header:hover {
        background-color: #e9e9e9;
    }
    
    .day-name {
        font-weight: bold;
        margin: 0;
    }
    
    .day-content {
        padding: 15px;
        display: none;
        background-color: #fff;
    }
    
    .day-content.active {
        display: block;
    }
    
    .time-controls {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .time-input-group {
        display: flex;
        flex-direction: column;
    }
    
    .time-input-group label {
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #2196F3;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    /* Estilos para el mapa */
    #map-container {
        height: 350px;
        margin-top: 15px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    
    #map {
        height: 100%;
        width: 100%;
    }
    
    .map-controls {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    
    /* Estilos para formularios */
    .form-section {
        margin-bottom: 25px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    /* Estilos para redes sociales */
    .social-item {
        margin-bottom: 15px;
    }
    
    .btn-add-social {
        margin-top: 10px;
    }
    
    /* Ajustes de layout */
    .left-column {
        padding-right: 15px;
    }
    
    .right-column {
        padding-left: 15px;
    }
    
    @media (max-width: 992px) {
        .left-column, 
        .right-column {
            padding: 0;
        }
    }
    
    /* Estilos para validación */
    .is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
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
                                        <textarea id="domicilio" class="form-control" name="domicilio" placeholder="Ingrese la dirección completa" rows="3" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="maps_link">Enlace de Google Maps</label>
                                        <input type="url" id="maps_link" class="form-control" name="maps_link" placeholder="https://goo.gl/maps/...">
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
                                        <input type="text" id="contacto1" class="form-control" name="contacto1" placeholder="Nombre, teléfono, email" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contacto2">Contacto Secundario</label>
                                        <input type="text" id="contacto2" class="form-control" name="contacto2" placeholder="Información adicional de contacto">
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
                                    <div class="schedule-container">
                                        <!-- Lunes -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Lunes</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_lunes" data-dia="lunes" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_lunes">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_lunes">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_lunes" data-dia="lunes" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_lunes">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_lunes" data-dia="lunes" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Martes -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Martes</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_martes" data-dia="martes" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_martes">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_martes">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_martes" data-dia="martes" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_martes">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_martes" data-dia="martes" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Miércoles -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Miércoles</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_miercoles" data-dia="miercoles" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_miercoles">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_miercoles">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_miercoles" data-dia="miercoles" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_miercoles">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_miercoles" data-dia="miercoles" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Jueves -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Jueves</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_jueves" data-dia="jueves" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_jueves">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_jueves">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_jueves" data-dia="jueves" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_jueves">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_jueves" data-dia="jueves" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Viernes -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Viernes</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_viernes" data-dia="viernes" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_viernes">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_viernes">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_viernes" data-dia="viernes" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_viernes">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_viernes" data-dia="viernes" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Sábado -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Sábado</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_sabado" data-dia="sabado">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_sabado">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_sabado">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_sabado" data-dia="sabado" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_sabado">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_sabado" data-dia="sabado" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Domingo -->
                                        <div class="schedule-day">
                                            <div class="day-header">
                                                <h4 class="day-name">Domingo</h4>
                                                <label class="switch">
                                                    <input type="checkbox" class="schedule-checkbox" id="laborable_domingo" data-dia="domingo">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="day-content" id="content_domingo">
                                                <div class="time-controls">
                                                    <div class="time-input-group">
                                                        <label for="entrada_domingo">Hora de entrada</label>
                                                        <input type="time" class="form-control time-input entrada" id="entrada_domingo" data-dia="domingo" value="08:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label for="salida_domingo">Hora de salida</label>
                                                        <input type="time" class="form-control time-input salida" id="salida_domingo" data-dia="domingo" value="18:00">
                                                        <div class="invalid-feedback">La hora de salida debe ser posterior a la de entrada</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                                <input type="url" class="form-control" name="social[facebook]" placeholder="URL de Facebook">
                                            </div>
                                        </div>
                                        
                                        <!-- Instagram -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fab fa-instagram"></i>
                                                </span>
                                                <input type="url" class="form-control" name="social[instagram]" placeholder="URL de Instagram">
                                            </div>
                                        </div>
                                        
                                        <!-- WhatsApp -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fab fa-whatsapp"></i>
                                                </span>
                                                <input type="tel" class="form-control" name="social[whatsapp]" placeholder="Número de WhatsApp">
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
                    
                    <div class="form-group text-right" style="margin-top: 20px;">
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
let activeDay = null;

$(document).ready(function() {
    // Inicializar el mapa
    initMap();
    
    // Cargar datos existentes
    cargarDatosContacto();
    
    // Manejar el comportamiento de acordeón para los días
    $('.day-header').click(function() {
        const dayContent = $(this).next('.day-content');
        const dia = $(this).find('.schedule-checkbox').data('dia');
        
        // Si ya está activo, cerrarlo
        if (dayContent.hasClass('active')) {
            dayContent.removeClass('active');
            activeDay = null;
            return;
        }
        
        // Cerrar el día activo si hay uno
        if (activeDay) {
            $(`#content_${activeDay}`).removeClass('active');
        }
        
        // Abrir el día seleccionado
        dayContent.addClass('active');
        activeDay = dia;
    });
    
    // Mostrar/ocultar campos de horario según checkbox
    $('.schedule-checkbox').change(function() {
        const dia = $(this).data('dia');
        const isChecked = $(this).is(':checked');
        const dayContainer = $(this).closest('.schedule-day');
        
        // Habilitar/deshabilitar controles
        dayContainer.toggleClass('disabled', !isChecked);
        dayContainer.find('.entrada, .salida').prop('disabled', !isChecked);
        
        // Validar el horario
        validateScheduleTime(dia);
    });
    
    // Validar horas cuando cambian
    $('.entrada, .salida').on('change input', function() {
        const dia = $(this).closest('.day-content').attr('id').replace('content_', '');
        validateScheduleTime(dia);
    });
    
    // Configurar evento click en el mapa
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateAddressFromMarker(e.latlng);
    });
    
    // Buscar dirección en el mapa
    $('#findAddress').click(function() {
        const address = $('#domicilio').val().trim();
        if (!address) {
            notify("Ingrese una dirección para buscar", 1500, "error", "top-end");
            return;
        }
        
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
                    notify("Error al obtener la ubicación: " + error.message, 1500, "error", "top-end");
                }
            );
        } else {
            notify("Geolocalización no soportada por tu navegador", 1500, "error", "top-end");
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
    
    // Validar y enviar formulario
    $('#contactForm').submit(function(e) {
        e.preventDefault();
        
        // Validar campos requeridos
        let isValid = true;
        const requiredFields = ['domicilio', 'contacto1'];
        
        requiredFields.forEach(field => {
            const value = $(`#${field}`).val().trim();
            if (!value) {
                isValid = false;
                notify(`El campo ${field} es requerido`, 1500, "error", "top-end");
                $(`#${field}`).focus();
                return false;
            }
        });
        
        // Validar URL de maps si está presente
        const mapsUrl = $('#maps_link').val().trim();
        if (mapsUrl && !isValidUrl(mapsUrl)) {
            isValid = false;
            notify('Ingrese un URL válido para Google Maps', 1500, "error", "top-end");
            $('#maps_link').focus();
            return false;
        }
        
        // Validar horario (al menos un día debe estar marcado como laborable)
        const diasLaborables = $('.schedule-checkbox:checked').length;
        if (diasLaborables === 0) {
            isValid = false;
            notify('Debe seleccionar al menos un día laborable', 1500, "error", "top-end");
            return false;
        }
        
        // Validar horas en días laborables
        $('.schedule-checkbox:checked').each(function() {
            const dia = $(this).data('dia');
            const entrada = $(`#entrada_${dia}`).val();
            const salida = $(`#salida_${dia}`).val();
            
            if (!entrada || !salida) {
                isValid = false;
                notify(`Debe completar las horas para ${dia}`, 1500, "error", "top-end");
                return false;
            }
            
            if (entrada >= salida) {
                isValid = false;
                notify(`La hora de entrada debe ser anterior a la de salida (${dia})`, 1500, "error", "top-end");
                return false;
            }
        });
        
        if (!isValid) return;
        
        // Preparar datos para enviar
        const formData = $(this).serializeArray();
        
        // Agregar horario al formData
        const horario = {};
        $('.schedule-checkbox').each(function() {
            const dia = $(this).data('dia');
            const laborable = $(this).is(':checked');
            const entrada = $(`#entrada_${dia}`).val();
            const salida = $(`#salida_${dia}`).val();
            
            horario[dia] = {
                laborable: laborable,
                entrada: entrada,
                salida: salida,
                entrada_24: entrada,
                salida_24: salida
            };
        });
        
        formData.push({name: 'horario', value: JSON.stringify(horario)});
        
        // Mostrar estado de carga
        $('#contactForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        // Enviar datos al servidor
        $.ajax({
            url: "ajax.php?mode=guardarcontacto",
            type: "POST",
            data: $.param(formData),
            dataType: "json",
            success: function(response) {
                if(response.codigo == 1) {
                    notify("Datos actualizados correctamente", 1500, "success", "top-end");
                    updateLastSaved();
                } else {
                    notify(response.alerta || "Error al guardar los datos", 1500, "error", "top-end");
                }
            },
            error: function(xhr, status, error) {
                notify("Error al conectar con el servidor", 1500, "error", "top-end");
                console.error("Error:", error);
            },
            complete: function() {
                $('#contactForm button[type="submit"]').html('<i class="fas fa-save"></i> Guardar Cambios');
            }
        });
    });
});

function initMap() {
    // Inicializar mapa con vista por defecto
    map = L.map('map').setView([19.4326, -99.1332], 15);
    
    // Añadir capa de tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Crear marcador
    marker = L.marker(map.getCenter(), {draggable: true}).addTo(map);
    
    // Evento al mover el marcador
    marker.on('dragend', function() {
        updateAddressFromMarker(marker.getLatLng());
    });
}

function updateMapLocation(latlng) {
    map.setView(latlng, 15);
    marker.setLatLng(latlng);
}

function updateAddressFromMarker(latlng) {
    // Actualizar enlace de Google Maps
    $('#maps_link').val(`https://www.google.com/maps?q=${latlng.lat},${latlng.lng}`);
    
    // Hacer geocodificación inversa para obtener la dirección completa
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const address = data.address;
                let fullAddress = '';
                
                // Construir dirección completa con número
                if (address.road) fullAddress += address.road;
                if (address.house_number) fullAddress += ` ${address.house_number},`;
                if (address.neighbourhood) fullAddress += ` ${address.neighbourhood},`;
                if (address.suburb) fullAddress += ` ${address.suburb},`;
                if (address.city) fullAddress += ` ${address.city},`;
                if (address.state) fullAddress += ` ${address.state},`;
                if (address.country) fullAddress += ` ${address.country}`;
                
                // Limpiar comas extras
                fullAddress = fullAddress.replace(/,+/g, ',').replace(/,\s*$/, '');
                
                $('#domicilio').val(fullAddress);
            }
        })
        .catch(error => {
            console.error("Error en geocodificación inversa:", error);
        });
}

function geocodeAddress(address) {
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
            } else {
                notify("No se encontró la dirección", 1500, "error", "top-end");
            }
        })
        .catch(error => {
            notify("Error al buscar la dirección", 1500, "error", "top-end");
            console.error("Error:", error);
        });
}

function validateScheduleTime(dia) {
    if (!$(`#laborable_${dia}`).is(':checked')) {
        $(`#salida_${dia}`).removeClass('is-invalid');
        return true;
    }
    
    const entrada = $(`#entrada_${dia}`).val();
    const salida = $(`#salida_${dia}`).val();
    
    if (!entrada || !salida) {
        $(`#salida_${dia}`).addClass('is-invalid');
        return false;
    }
    
    if (entrada >= salida) {
        $(`#salida_${dia}`).addClass('is-invalid');
        return false;
    } else {
        $(`#salida_${dia}`).removeClass('is-invalid');
        return true;
    }
}

function cargarDatosContacto() {
    $.ajax({
        url: "ajax.php?mode=getcontacto",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if(response.codigo == 1) {
                // Llenar campos básicos
                $('#domicilio').val(response.data.domicilio || '');
                $('#maps_link').val(response.data.maps_link || '');
                $('#contacto1').val(response.data.contacto1 || '');
                $('#contacto2').val(response.data.contacto2 || '');
                
                // Llenar horario
                if (response.data.horario) {
                    const horario = JSON.parse(response.data.horario);
                    for (const dia in horario) {
                        if (horario[dia].laborable) {
                            $(`#laborable_${dia}`).prop('checked', true).trigger('change');
                            // Convertir de formato 12h a 24h si es necesario
                            let entrada = horario[dia].entrada_24 || horario[dia].entrada;
                            let salida = horario[dia].salida_24 || horario[dia].salida;
                            
                            // Asegurar formato HH:MM
                            if (entrada && !entrada.includes(':')) {
                                entrada = convertTo24Hour(entrada.split(' ')[0], entrada.split(' ')[1]);
                            }
                            if (salida && !salida.includes(':')) {
                                salida = convertTo24Hour(salida.split(' ')[0], salida.split(' ')[1]);
                            }
                            
                            $(`#entrada_${dia}`).val(entrada || '08:00');
                            $(`#salida_${dia}`).val(salida || '18:00');
                        }
                    }
                }
                
                // Centrar mapa si hay coordenadas
                if (response.data.maps_link) {
                    const coords = extractCoordsFromUrl(response.data.maps_link);
                    if (coords) {
                        updateMapLocation(coords);
                    }
                }
                
                updateLastSaved();
            } else {
                notify(response.alerta || "Error al cargar los datos", 1500, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            notify("Error al cargar los datos de contacto", 1500, "error", "top-end");
            console.error("Error:", error);
        }
    });
}

function convertTo24Hour(time, ampm) {
    if (!time) return '';
    
    let [hours, minutes] = time.split(':');
    hours = parseInt(hours);
    
    if (ampm === 'PM' && hours < 12) {
        hours += 12;
    } else if (ampm === 'AM' && hours === 12) {
        hours = 0;
    }
    
    return `${hours.toString().padStart(2, '0')}:${minutes}`;
}

function extractCoordsFromUrl(url) {
    const match = url.match(/q=([-+]?\d*\.\d+|\d+),([-+]?\d*\.\d+|\d+)/);
    if (match && match.length >= 3) {
        return {
            lat: parseFloat(match[1]),
            lng: parseFloat(match[2])
        };
    }
    return null;
}

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

function updateLastSaved() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    $('#lastSaved').html('Última actualización: ' + timeString);
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>