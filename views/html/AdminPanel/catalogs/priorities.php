<?php
include(HTML . 'AdminPanel/masterPanel/head.php');
include(HTML . 'AdminPanel/masterPanel/navbar.php');
include(HTML . 'AdminPanel/masterPanel/menu.php');
include(HTML . 'AdminPanel/masterPanel/breadcrumb.php');
?>

<script src="views\js\forms.js"></script>


<style>
    .icon-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .icon-item {
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .icon-item:hover {
        background-color: #f8f9fa;
        transform: scale(1.03);
    }

    .icon-item.selected {
        background-color: #007bff;
        color: white;
    }

    .icon-preview {
        font-size: 2rem;
        text-align: center;
        margin: 10px 0;
    }

    .modal-header {
        padding: 12px 20px;
    }

    .btn-icon {
        cursor: pointer;
        font-size: 1.2rem;
        padding: 5px 10px;
    }

    .btn-icon:hover {
        opacity: 0.8;
    }

    .inactive-row,
    .inactive-row td {
        background-color: rgb(124, 124, 124) !important;
    }

    .dropdown-menu {
        min-width: 200px;
        padding: 10px;
    }

    #contentPriorities {
        position: relative;
    }

    .loading-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 1000;
    }
    
    .btn-reactivate {
        color: #28a745;
        margin-left: 10px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary expandable-btn" data-toggle="modal" data-target="#ModalAddPriority">
                    <span class="fas fa-plus" style="margin-right:10px;"></span> Nueva Prioridad
                </button>
                <div id="contentPriorities" class="mt-3">
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>Cargando prioridades...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Prioridad -->
<div id="ModalAddPriority" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="fas fa-plus"></span> Agregar Nueva Prioridad</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addPriorityForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">Código:</label>
                                <input type="text" class="form-control text-uppercase" id="codigo"
                                    placeholder="Ej: ALTA, MEDIA, BAJA" maxlength="20"
                                    oninput="this.value = this.value.toUpperCase()" required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <input type="text" class="form-control" id="descripcion"
                                    placeholder="Descripción de la prioridad" maxlength="50" required>
                            </div>

                            <div class="form-group">
                                <label for="color_hex">Color:</label>
                                <input type="color" class="form-control" id="color_hex" value="#FF0000" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hr_min">Horas Mínimas:</label>
                                <input type="number" class="form-control" id="hr_min"
                                    placeholder="0.0" min="0" step="0.5" required
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46">
                            </div>

                            <div class="form-group">
                                <label for="hr_max">Horas Máximas:</label>
                                <input type="number" class="form-control" id="hr_max"
                                    placeholder="0.0" min="0" step="0.5" required
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46">
                            </div>

                            <div class="form-group">
                                <label>Ícono:</label>
                                <input type="text" id="icono" name="icono" value="" disabled>
                                <div id="selectedIconPreview" class="icon-preview text-muted mt-2">
                                    <i class="fas fa-question-circle"></i>
                                    <div class="small">Ningún ícono seleccionado</div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-block"
                                    data-toggle="modal" data-target="#iconModal"
                                    data-field="icono" data-preview="selectedIconPreview">
                                    <i class="fas fa-icons"></i> Seleccionar Ícono
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="newPriority()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Prioridad -->
<div id="ModalEditPriority" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="fas fa-pencil"></span> Editar Prioridad</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="editPriorityForm">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="savePriority()">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Selección de Íconos -->
<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="iconModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="iconModalLabel"><i class="fas fa-icons"></i> Seleccionar Ícono</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Buscar ícono..." autocomplete="off">
                </div>
                <div class="row icon-container" id="iconContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var faIcons;
    $(document).ready(function(){
        extraIconosFontAsowme()
    });

    function extraIconosFontAsowme() {
        $.get('views/components/fontawesome-free/metadata/icons.yml').done(function(yamlData) {
            try {
                const icons = jsyaml.load(yamlData);
                const freeIcons = [];

                const freeStyles = {
                    'solid': 'fas',
                    'regular': 'far',
                    'brands': 'fab'
                };

                Object.entries(icons).forEach(([name, iconData]) => {
                    if (iconData.styles && iconData.styles.some(style => freeStyles[style])) {
                        iconData.styles.forEach(style => {
                            if (freeStyles[style]) {
                                freeIcons.push(`${freeStyles[style]} fa-${name}`);
                            }
                        });
                    }
                });

                faIcons = freeIcons;
            } catch (e) {
                console.error("Error al procesar YAML:", e);
                faIcons = false;
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error al cargar el YAML:", textStatus, errorThrown);
            faIcons = false;
        });
    }

    let selectedIcon = '';
    let currentIconField = '';
    let currentIconPreview = '';

    $(document).ready(function() {
        initIconModal();
        getPriorities();
    });

    function initIconModal() {
        $('#iconModal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            currentIconField = button.data('field');
            currentIconPreview = button.data('preview');
            selectedIcon = $('#' + currentIconField).val() || '';
            loadIcons();
            $('#iconSearch').val('').focus();
        });

        $('#iconModal').on('click', '.icon-item', function() {
            selectedIcon = $(this).data('icon');
            $('#' + currentIconField).val(selectedIcon);
            updateIconPreview(currentIconPreview, selectedIcon);
            $('#iconModal').modal('hide');
        });

        $('#iconSearch').on('input', function() {
            loadIcons($(this).val());
        });
    }

    function updateIconPreview(previewId, iconClass) {
        const previewElement = $('#' + previewId);
        if (iconClass) {
            previewElement.html(`
            <i class="${iconClass}"></i>
            <div class="small">${iconClass.replace('fas fa-', '')}</div>
        `).removeClass('text-muted');
        } else {
            previewElement.html(`
            <i class="fas fa-question-circle"></i>
            <div class="small">Ningún ícono seleccionado</div>
        `).addClass('text-muted');
        }
    }

    function loadIcons(searchTerm = '') {
        const container = $('#iconContainer');
        container.empty();

        const filteredIcons = searchTerm ?
            faIcons.filter(icon => icon.toLowerCase().includes(searchTerm.toLowerCase())) :
            faIcons;

        filteredIcons.forEach(icon => {
            const isSelected = selectedIcon === icon;
            container.append(`
            <div class="col-md-3 col-sm-4 col-6 mb-3">
                <div class="icon-item text-center p-2 rounded ${isSelected ? 'selected' : ''}" 
                     data-icon="${icon}">
                    <i class="${icon} fa-2x mb-1"></i>
                    <div class="small text-truncate">${icon.replace('fas fa-', '')}</div>
                </div>
            </div>
        `);
        });
    }

    function getPriorities() {
        var mostrarInactivos = $('#mostrarInactivos').is(':checked') ? '1' : '0';
        $.ajax({
            url: "ajax.php?mode=getpriorities",
            type: "POST",
            data: {
                inactivos: mostrarInactivos
            },
            beforeSend: function() {
                $('#contentPriorities').html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando prioridades...</p></div>');
            },
            success: function(datos) {
                $("#contentPriorities").html(datos);
                var arrayOrder = [];
                var arrayExport = ['excel'];
                datatablebase("tablaPrioridades", false, 400, true, true, arrayOrder, arrayExport);

                $('#mostrarInactivos').off('change').on('change', function() {
                    const mostrar = $(this).is(":checked") ? "1" : "0";
                    getPriorities();
                });
            },
            error: function(xhr, status, error) {
                $('#contentPriorities').html('<div class="alert alert-danger">Error al cargar las prioridades</div>');
                console.error('Error al cargar prioridades:', error);
            }
        });
    }

    function newPriority() {
        const formData = {
            codigo: $('#codigo').val().trim().toUpperCase(),
            descripcion: $('#descripcion').val().trim(),
            color_hex: $('#color_hex').val(),
            hr_min: $('#hr_min').val(),
            hr_max: $('#hr_max').val(),
            icono: $('#icono').val()
        };

        // Validaciones
        if (!formData.codigo) {
            notify("El código es obligatorio", 1500, "error");
            $('#codigo').focus();
            return;
        }
        if (!formData.descripcion) {
            notify("La descripción es obligatoria", 1500, "error");
            $('#descripcion').focus();
            return;
        }
        if (!formData.icono) {
            notify("Por favor seleccione un ícono", 1500, "error");
            return;
        }
        
        if (parseFloat(formData.hr_min) < 0) {
            notify("Las horas mínimas no pueden ser negativas", 1500, "error");
            $('#hr_min').focus();
            return;
        }
        
        if (parseFloat(formData.hr_max) < 0) {
            notify("Las horas máximas no pueden ser negativas", 1500, "error");
            $('#hr_max').focus();
            return;
        }
        
        if (parseFloat(formData.hr_min) > parseFloat(formData.hr_max)) {
            notify("Las horas mínimas no pueden ser mayores que las máximas", 1500, "error");
            $('#hr_min').focus();
            return;
        }

        $.ajax({
            url: "ajax.php?mode=newpriority",
            type: "POST",
            data: formData,
            beforeSend: function() {
                $('button').prop('disabled', true);
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);

                    if (result.codigo == 1) {
                        $('#ModalAddPriority').modal('hide');
                        cleanPriorityForm();
                        getPriorities();
                        notify(result.alerta, 1500, "success");
                    } else {
                        notify(result.alerta || "Error al guardar", 1500, "error");
                    }
                } catch (e) {
                    notify("Error al procesar la respuesta", 1500, "error");
                }
            },
            error: function(xhr, status, error) {
                notify("Error al guardar: " + error, 1500, "error");
            },
            complete: function() {
                $('button').prop('disabled', false);
            }
        });
    }

    function GetRegisterPriority(id) {
        $.ajax({
            url: "ajax.php?mode=getpriority",
            type: "POST",
            data: { id: id },
            dataType: 'json',
            beforeSend: function() {
                $('#editPriorityForm').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            },
            success: function(response) {
                if (response && response.success && response.html) {
                    $('#editPriorityForm').html(response.html);
                    $('#ModalEditPriority').modal('show');
                } else {
                    notify(response.error || "Error al cargar datos", 1500, "error");
                }
            },
            error: function(xhr, status, error) {
                notify("Error al cargar datos: " + error, 1500, "error");
            }
        });
    }

    function savePriority() {
        const edit_id = document.getElementById('edit_id');
        const edit_descripcion = document.getElementById('edit_descripcion');
        const edit_color_hex = document.getElementById('edit_color_hex');
        const edit_hr_min = document.getElementById('edit_hr_min');
        const edit_hr_max = document.getElementById('edit_hr_max');
        const edit_icono = document.getElementById('edit_icono');

        if (!edit_id || !edit_descripcion || !edit_color_hex || !edit_hr_min || !edit_hr_max || !edit_icono) {
            notify("Error: No se pudo acceder a los campos del formulario", 1500, "error");
            return;
        }

        const formData = {
            id: edit_id.value,
            codigo: document.getElementById('edit_codigo')?.value?.trim()?.toUpperCase() || '',
            descripcion: edit_descripcion.value.trim(),
            color_hex: edit_color_hex.value,
            hr_min: edit_hr_min.value,
            hr_max: edit_hr_max.value,
            icono: edit_icono.value
        };

        // Validaciones
        if (!formData.descripcion) {
            notify("La descripción es obligatoria", 1500, "error");
            edit_descripcion.focus();
            return;
        }
        if (!formData.icono) {
            notify("Por favor seleccione un ícono", 1500, "error");
            return;
        }
        
        if (parseFloat(formData.hr_min) < 0) {
            notify("Las horas mínimas no pueden ser negativas", 1500, "error");
            edit_hr_min.focus();
            return;
        }
        
        if (parseFloat(formData.hr_max) < 0) {
            notify("Las horas máximas no pueden ser negativas", 1500, "error");
            edit_hr_max.focus();
            return;
        }
        
        if (parseFloat(formData.hr_min) > parseFloat(formData.hr_max)) {
            notify("Las horas mínimas no pueden ser mayores que las máximas", 1500, "error");
            edit_hr_min.focus();
            return;
        }

        $.ajax({
            url: "ajax.php?mode=savepriority",
            type: "POST",
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('button').prop('disabled', true);
            },
            success: function(response) {
                try {
                    if (response && response.codigo == 1) {
                        $('#ModalEditPriority').modal('hide');
                        getPriorities();
                        notify(response.alerta || "Cambios guardados correctamente", 1500, "success");
                    } else {
                        notify(response.alerta || "Error al guardar los cambios", 1500, "error");
                    }
                } catch (e) {
                    notify("Error al procesar la respuesta del servidor", 1500, "error");
                }
            },
            error: function(xhr, status, error) {
                notify("Error de conexión: " + error, 1500, "error");
            },
            complete: function() {
                $('button').prop('disabled', false);
            }
        });
    }

    function confirmDeletePriority(id, codigo, descripcion) {
        if (codigo === "ALTA") {
            notify("No se puede eliminar la prioridad ALTA", 2000, "error");
            return;
        }

        notifyConfirm(
            "¿Está seguro de eliminar esta prioridad?",
            `Se eliminará la prioridad:${descripcion}`,
            "warning",
            `deletePriority('${id}')`
        );
    }

    function deletePriority(id) {
        $.ajax({
            url: "ajax.php?mode=deletepriority",
            type: "POST",
            data: {
                id: id
            },
            beforeSend: function() {
                $('button').prop('disabled', true);
            },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.codigo == 1) {
                        getPriorities();
                        notify(result.alerta, 1500, "success");
                    } else {
                        notify(result.alerta || "Error al eliminar", 1500, "error");
                    }
                } catch (e) {
                    notify("Error al procesar la respuesta", 1500, "error");
                }
            },
            error: function(xhr, status, error) {
                notify("Error al eliminar: " + error, 1500, "error");
            },
            complete: function() {
                $('button').prop('disabled', false);
            }
        });
    }

    function confirmReactivatePriority(id, codigo, descripcion) {
        notifyConfirm(
            "¿Está seguro de reactivar esta prioridad?",
            `Se reactivará la prioridad: ${descripcion}`,
            "warning",
            `reactivatePriority('${id}')`
        );
    }

    function reactivatePriority(id) {
        $.ajax({
            url: "ajax.php?mode=deletepriority",
            type: "POST",
            data: {
                id: id
            },
            beforeSend: function() {
                $('button').prop('disabled', true);
            },
            success: function(response) {
                console.log(response);          
                try {
                    const result = JSON.parse(response);
                    if (result.codigo == 1) {
                        getPriorities();
                        notify(result.alerta, 1500, "success");
                    } else {
                        notify(result.alerta || "Error al reactivar", 1500, "error");
                    }
                } catch (e) {
                    notify("Error al procesar la respuesta", 1500, "error");
                }
            },
            error: function(xhr, status, error) {
                notify("Error al reactivar: " + error, 1500, "error");
            },
            complete: function() {
                $('button').prop('disabled', false);
            }
        });
    }

    function cleanPriorityForm() {
        $('#addPriorityForm')[0].reset();
        $('#icono').val('');
        $('#selectedIconPreview').html(`
        <i class="fas fa-question-circle"></i>
        <div class="small">Ningún ícono seleccionado</div>
    `).addClass('text-muted');
        $('#color_hex').val('#FF0000');
    }
</script>

<?php include(HTML . 'AdminPanel/masterPanel/foot.php'); ?>