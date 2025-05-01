<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

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
                        <div class="col-md-6">
                            <!-- Sección de Domicilio -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Domicilio</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="domicilio">Dirección Completa</label>
                                        <textarea id="domicilio" class="form-control" name="domicilio" placeholder="Ingrese la dirección completa" required></textarea>
                                        <div class="help-block text-danger" id="domicilio-error" style="display:none;">Este campo es requerido</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="maps_link">Enlace de Google Maps</label>
                                        <input type="url" id="maps_link" class="form-control" name="maps_link" placeholder="https://goo.gl/maps/...">
                                        <div class="help-block text-danger" id="maps-error" style="display:none;">Ingrese un URL válido</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección de Contactos -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Contactos</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="contacto1">Contacto Principal</label>
                                        <input type="text" id="contacto1" class="form-control" name="contacto1" placeholder="Nombre, teléfono, email" required>
                                        <div class="help-block text-danger" id="contacto1-error" style="display:none;">Este campo es requerido</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contacto2">Contacto Secundario</label>
                                        <input type="text" id="contacto2" class="form-control" name="contacto2" placeholder="Información adicional de contacto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna Derecha -->
                        <div class="col-md-6">
                            <!-- Sección de Horario -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Horario de Atención</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="horario">Horarios</label>
                                        <textarea id="horario" class="form-control" name="horario" placeholder="Ej: Lunes a Viernes: 9:00 - 18:00" required></textarea>
                                        <div class="help-block text-danger" id="horario-error" style="display:none;">Este campo es requerido</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección de Redes Sociales -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Redes Sociales</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="social-container">
                                        <!-- Facebook -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
                                                <input type="url" class="form-control social-input" name="social[facebook]" placeholder="URL de Facebook">
                                            </div>
                                        </div>
                                        
                                        <!-- Instagram -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fab fa-instagram"></i></span>
                                                <input type="url" class="form-control social-input" name="social[instagram]" placeholder="URL de Instagram">
                                            </div>
                                        </div>
                                        
                                        <!-- WhatsApp -->
                                        <div class="form-group social-item">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fab fa-whatsapp"></i></span>
                                                <input type="tel" class="form-control social-input" name="social[whatsapp]" placeholder="Número de WhatsApp">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" id="addSocial" class="btn btn-default">
                                        <i class="fas fa-plus"></i> Agregar Otra Red Social
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-right">
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
$(document).ready(function() {
    // Cargar datos existentes
    cargarDatosContacto();
    
    // Validación de formulario
    $('#contactForm').validate({
        rules: {
            domicilio: "required",
            contacto1: "required",
            horario: "required",
            maps_link: {
                required: false,
                url: true
            }
        },
        messages: {
            domicilio: "Este campo es requerido",
            contacto1: "Este campo es requerido",
            horario: "Este campo es requerido",
            maps_link: "Ingrese un URL válido"
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find('.help-block'));
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        }
    });
    
    // Agregar nueva red social
    $('#addSocial').click(function() {
        var newSocial = `
            <div class="form-group social-item">
                <div class="row">
                    <div class="col-xs-3">
                        <select class="form-control social-type">
                            <option value="link">Seleccione red</option>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="twitter">Twitter</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                        </select>
                    </div>
                    <div class="col-xs-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-link"></i></span>
                            <input type="text" class="form-control social-input" name="social[custom][]" placeholder="URL o información">
                        </div>
                    </div>
                    <div class="col-xs-1">
                        <button type="button" class="btn btn-danger btn-xs remove-social">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#social-container').append(newSocial);
    });
    
    // Eliminar red social
    $(document).on('click', '.remove-social', function() {
        $(this).closest('.social-item').remove();
    });
    
    // Cambiar ícono según tipo de red social
    $(document).on('change', '.social-type', function() {
        var icon = $(this).closest('.row').find('.input-group-addon i');
        var type = $(this).val();
        
        var icons = {
            'facebook': 'fab fa-facebook-f',
            'instagram': 'fab fa-instagram',
            'whatsapp': 'fab fa-whatsapp',
            'twitter': 'fab fa-twitter',
            'linkedin': 'fab fa-linkedin-in',
            'youtube': 'fab fa-youtube',
            'tiktok': 'fab fa-tiktok',
            'link': 'fas fa-link'
        };
        
        icon.attr('class', icons[type] || 'fas fa-link');
    });
    
    // Enviar formulario
    $('#contactForm').submit(function(e) {
        e.preventDefault();
        
        if(!$(this).valid()) return;
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "ajax.php?mode=guardarcontacto",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if(response.codigo == 1) {
                    notify("Datos de contacto actualizados correctamente", 1500, "success", "top-end");
                    updateLastSaved();
                } else {
                    notify(response.alerta, 1500, "error", "top-end");
                }
            },
            error: function(xhr, status, error) {
                notify("Error al guardar los datos: " + error, 1500, "error", "top-end");
                console.error("Error al guardar datos:", status, error);
            }
        });
    });
});

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
                $('#horario').val(response.data.horario || '');
                
                // Llenar redes sociales
                if(response.data.redes_sociales) {
                    $.each(response.data.redes_sociales, function(red, valor) {
                        if(red == 'facebook' || red == 'instagram' || red == 'whatsapp') {
                            $('input[name="social['+red+']"]').val(valor || '');
                        } else {
                            // Para redes sociales personalizadas
                            // Aquí puedes implementar la lógica si necesitas
                        }
                    });
                }
                
                updateLastSaved();
            } else {
                notify(response.alerta, 1500, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            notify("Error al cargar los datos de contacto. Por favor recarga la página.", 1500, "error", "top-end");
            console.error("Error al cargar datos:", status, error);
        }
    });
}

function updateLastSaved() {
    var now = new Date();
    var timeString = now.toLocaleTimeString();
    $('#lastSaved').html('Última actualización: ' + timeString);
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>