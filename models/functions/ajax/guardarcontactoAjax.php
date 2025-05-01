<?php
header('Content-Type: application/json');
error_reporting(0); // Desactivar errores para evitar output no JSON

try {
    $db = new Conexion();
    $mode = $_GET['mode'] ?? '';
    $response = ['codigo' => 0, 'alerta' => 'Acción no válida'];

    switch ($mode) {
        case 'getcontacto':
            $sql = "SELECT domicilio, tel1 as contacto1, tel2 as contacto2, email, atencion, 
                   rs_fb as 'social_facebook', rs_insta as 'social_instagram', rs_wp as 'social_whatsapp', 
                   maps as maps_link FROM core LIMIT 1";
            
            $result = $db->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $data = $result->fetch_assoc();
                
                // Estructurar los datos como los espera el frontend
                $response = [
                    'codigo' => 1,
                    'data' => [
                        'domicilio' => $data['domicilio'] ?? '',
                        'contacto1' => $data['contacto1'] ?? '',
                        'contacto2' => $data['contacto2'] ?? '',
                        'email' => $data['email'] ?? '',
                        'atencion' => $data['atencion'] ?? '',
                        'maps_link' => $data['maps_link'] ?? '',
                        'social' => [
                            'facebook' => $data['social_facebook'] ?? '',
                            'instagram' => $data['social_instagram'] ?? '',
                            'whatsapp' => $data['social_whatsapp'] ?? ''
                        ]
                    ]
                ];
            } else {
                $response = ['codigo' => 1, 'data' => []];
            }
            break;

        case 'guardarcontacto':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Formato de datos inválido');
            }

            $updates = [
                "domicilio = '" . $db->real_escape_string($data['domicilio'] ?? '') . "'",
                "tel1 = '" . $db->real_escape_string($data['contacto1'] ?? '') . "'",
                "tel2 = '" . $db->real_escape_string($data['contacto2'] ?? '') . "'",
                "email = '" . $db->real_escape_string($data['email'] ?? '') . "'",
                "atencion = '" . $db->real_escape_string($data['atencion'] ?? '') . "'",
                "rs_fb = '" . $db->real_escape_string($data['social']['facebook'] ?? '') . "'",
                "rs_insta = '" . $db->real_escape_string($data['social']['instagram'] ?? '') . "'",
                "rs_wp = '" . $db->real_escape_string($data['social']['whatsapp'] ?? '') . "'",
                "maps = '" . $db->real_escape_string($data['maps_link'] ?? '') . "'",
                "fh_actualizacion = NOW()"
            ];

            $query = "UPDATE core SET " . implode(", ", $updates);
            
            if ($db->query($query)) {
                $response = ['codigo' => 1, 'alerta' => 'Datos actualizados correctamente'];
            } else {
                throw new Exception("Error en la base de datos: " . $db->error);
            }
            break;
    }
} catch(Exception $e) {
    $response = ['codigo' => 0, 'alerta' => $e->getMessage()];
}

echo json_encode($response);
exit();
?>