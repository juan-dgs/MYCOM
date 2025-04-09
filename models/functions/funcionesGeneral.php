<?php

function calcularDiferenciaFechas($fechaInicio, $fechaFin) {
        $datetime1 = new DateTime($fechaInicio);
        $datetime2 = new DateTime($fechaFin);
        
        $diferencia = $datetime1->diff($datetime2);
        $horasTotales = ($diferencia->days * 24) + $diferencia->h + ($diferencia->i / 60);

        $diferencia = ($datetime2)->diff($datetime1);
    
        $partes = [];
        $unidades = [
            'y' => 'año', 
            'm' => 'mes', 
            'd' => 'día', 
            'h' => 'hr', 
            'i' => 'min'
        ];
        
        foreach ($unidades as $prop => $nombre) {
            if ($diferencia->$prop > 0) {
                $partes[] = $diferencia->$prop . ' ' . $nombre . ($diferencia->$prop > 1 ? 's' : '');
            }
        }

        
        return [
            'texo' =>  $partes ? implode(' ', $partes) : '0 minutos',
            'horas' => floor($horasTotales)
        ];
    }

    function borrarArchivoSeguro($ruta) {
        if (!file_exists($ruta)) {
            return false;
        }
        
        if (is_dir($ruta)) {
           // error_log("Intento de borrar directorio como archivo: $ruta");
            return false;
        }
        
        return unlink($ruta);
    }
    



?>