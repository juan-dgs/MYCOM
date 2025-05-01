<?php
// ajax.php?mode=getmapcoords

$db = new Conexion();
$arr = array('codigo' => 0, 'alerta' => 'Error al cargar coordenadas');

try {
    $sql = "SELECT maps FROM core LIMIT 1";
    $result = $db->query($sql);
    
    if($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $maps = $data['maps'] ?? '';
        
        // Extraer coordenadas del enlace de Google Maps
        $coords = array('lat' => 0, 'lng' => 0);
        if(!empty($maps)) {
            $pattern = '/@(-?\d+\.\d+),(-?\d+\.\d+)/';
            if(preg_match($pattern, $maps, $matches)) {
                $coords['lat'] = floatval($matches[1]);
                $coords['lng'] = floatval($matches[2]);
            }
        }
        
        $arr = array(
            'codigo' => 1,
            'coords' => $coords
        );
    }
} catch(Exception $e) {
    $arr = array('codigo' => 0, 'alerta' => $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($arr, JSON_UNESCAPED_UNICODE);
exit();
?>