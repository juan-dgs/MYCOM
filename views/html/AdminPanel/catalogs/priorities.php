<?php
include(HTML . 'AdminPanel/masterPanel/head.php');
include(HTML . 'AdminPanel/masterPanel/navbar.php');
include(HTML . 'AdminPanel/masterPanel/menu.php');
include(HTML . 'AdminPanel/masterPanel/breadcrumb.php');
?>

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
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddPriority">
                    <i class="fas fa-plus"></i> Agregar Prioridad
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
                <h4 class="modal-title"><i class="fas fa-plus-circle"></i> Agregar Nueva Prioridad</h4>
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
                                    placeholder="0.0" min="0" step="0.5" required>
                            </div>

                            <div class="form-group">
                                <label for="hr_max">Horas Máximas:</label>
                                <input type="number" class="form-control" id="hr_max"
                                    placeholder="0.0" min="0" step="0.5" required>
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
                <h4 class="modal-title"><i class="fas fa-edit"></i> Editar Prioridad</h4>
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
<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="iconModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h4 class="modal-title" id="iconModalLabel"><i class="fas fa-icons"></i> Seleccionar Ícono</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Buscar ícono...">
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

<!-- Incluye FontAwesome para mostrar los iconos -->

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

                // Estilos gratuitos en Font Awesome (fas = solid, far = regular, fab = brands)
                const freeStyles = {
                    'solid': 'fas',
                    'regular': 'far',
                    'brands': 'fab'
                };

                Object.entries(icons).forEach(([name, iconData]) => {
                    // Verificamos si tiene estilos gratuitos
                    if (iconData.styles && iconData.styles.some(style => freeStyles[style])) {
                        iconData.styles.forEach(style => {
                            if (freeStyles[style]) { // Solo si es un estilo gratuito
                                freeIcons.push(`${freeStyles[style]} fa-${name}`);
                            }
                        });
                    }
                });

                //console.log("Iconos gratuitos en formato FA:", freeIcons);
                //console.log("Total de iconos gratuitos:", freeIcons.length);

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

    //console.log(faIcons);

    /*[
        'fas fa-address-book', 'fas fa-address-card', 'fas fa-angry', 'fas fa-arrow-alt-circle-down',
        'fas fa-arrow-alt-circle-left', 'fas fa-arrow-alt-circle-right', 'fas fa-arrow-alt-circle-up',
        'fas fa-bell', 'fas fa-bell-slash', 'fas fa-bookmark', 'fas fa-building', 'fas fa-calendar',
        'fas fa-calendar-alt', 'fas fa-calendar-check', 'fas fa-calendar-minus', 'fas fa-calendar-plus',
        'fas fa-calendar-times', 'fas fa-caret-square-down', 'fas fa-caret-square-left',
        'fas fa-caret-square-right', 'fas fa-caret-square-up', 'fas fa-chart-bar', 'fas fa-check-circle',
        'fas fa-check-square', 'fas fa-circle', 'fas fa-clipboard', 'fas fa-clock', 'fas fa-clone',
        'fas fa-comment', 'fas fa-comment-alt', 'fas fa-comments', 'fas fa-compass', 'fas fa-copy',
        'fas fa-credit-card', 'fas fa-dot-circle', 'fas fa-edit', 'fas fa-envelope', 'fas fa-envelope-open',
        'fas fa-eye', 'fas fa-eye-slash', 'fas fa-file', 'fas fa-file-alt', 'fas fa-file-archive',
        'fas fa-file-excel', 'fas fa-file-image', 'fas fa-file-pdf', 'fas fa-file-word', 'fas fa-flag',
        'fas fa-folder', 'fas fa-folder-open', 'fas fa-heart', 'fas fa-home', 'fas fa-hourglass',
        'fas fa-image', 'fas fa-images', 'fas fa-key', 'fas fa-list', 'fas fa-list-alt', 'fas fa-map',
        'fas fa-paperclip', 'fas fa-paste', 'fas fa-phone', 'fas fa-question-circle', 'fas fa-save',
        'fas fa-search', 'fas fa-share-alt', 'fas fa-star', 'fas fa-tag', 'fas fa-tags', 'fas fa-thumbs-up',
        'fas fa-trash-alt', 'fas fa-user', 'fas fa-user-circle', 'fas fa-users', 'fas fa-bolt'
    ];*/

    // Variables globales para manejo de íconos
    let selectedIcon = '';
    let currentIconField = '';
    let currentIconPreview = '';

    $(document).ready(function() {
        // Inicializar eventos
        initIconModal();

        // Cargar prioridades al iniciar
        getPriorities();
    });

    // Función para inicializar el modal de íconos
    function initIconModal() {
        // Al abrir el modal
        $('#iconModal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            currentIconField = button.data('field');
            currentIconPreview = button.data('preview');
            selectedIcon = $('#' + currentIconField).val() || '';
            loadIcons();
            $('#iconSearch').val('').focus();
        });

        // Selección automática al hacer click
        $('#iconModal').on('click', '.icon-item', function() {
            selectedIcon = $(this).data('icon');
            $('#' + currentIconField).val(selectedIcon);
            updateIconPreview(currentIconPreview, selectedIcon);
            $('#iconModal').modal('hide');
        });

        // Buscar íconos
        $('#iconSearch').on('input', function() {
            loadIcons($(this).val());
        });
    }

    // Función para actualizar la vista previa del ícono
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

    // Función para cargar íconos en el modal
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

    // Función para obtener las prioridades
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

                var arrayOrder = []; //[14, 'asc'], [0, 'asc'], [3, 'asc'], [5, 'asc']
                var arrayExport = ['excel']; //'excel'
                datatablebase("tablaUsuarios", false, 400, true, true, arrayOrder, arrayExport);
                //datatablebase(tableid, ffoot, scroll, order, search, arrayOrder, arrayExport)

                // Configurar el evento del checkbox después de cargar la tabla
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


    // Función para agregar nueva prioridad
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

    // Función para cargar datos para edición
    function GetRegisterPriority(id) {
        $.ajax({
            url: "ajax.php?mode=getpriority",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            beforeSend: function() {
                $('#editPriorityForm').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            },
            success: function(response) {
                if (response && response.success && response.html) {
                    $('#editPriorityForm').html(response.html);

                    // Actualizar el ícono seleccionado
                    const icono = $('#edit_icono').val();
                    if (icono) {
                        $('#editIconPreview').html(`
                        <i class="${icono}"></i>
                        <div class="small">${icono.replace('fas fa-', '')}</div>
                    `).removeClass('text-muted');
                    }

                    // Configurar el modal de íconos para edición
                    $('[data-target="#iconModal"]').off('click').on('click', function() {
                        currentIconField = 'edit_icono';
                        currentIconPreview = 'editIconPreview';
                        selectedIcon = $('#edit_icono').val() || '';
                        $('#iconModal').modal('show');
                    });

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

    // Función para guardar cambios
    function savePriority() {
        const formData = {
            id: $('#edit_id').val(),
            codigo: $('#edit_codigo').val().trim().toUpperCase(),
            descripcion: $('#edit_descripcion').val().trim(),
            color_hex: $('#edit_color_hex').val(),
            hr_min: $('#edit_hr_min').val(),
            hr_max: $('#edit_hr_max').val(),
            icono: $('#edit_icono').val()
        };

        // Validaciones
        if (!formData.descripcion) {
            notify("La descripción es obligatoria", 1500, "error");
            $('#edit_descripcion').focus();
            return;
        }
        if (!formData.icono) {
            notify("Por favor seleccione un ícono", 1500, "error");
            return;
        }
        if (parseFloat(formData.hr_min) > parseFloat(formData.hr_max)) {
            notify("Las horas mínimas no pueden ser mayores que las máximas", 1500, "error");
            $('#edit_hr_min').focus();
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

    // Función para confirmar eliminación
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

    // Función para eliminar prioridad
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

    // Función para limpiar el formulario
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