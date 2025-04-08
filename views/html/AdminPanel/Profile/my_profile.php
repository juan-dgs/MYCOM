<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #262626;
            --secondary-color: #fafafa;
            --text-color: #262626;
            --text-secondary: #8e8e8e;
            --white: #ffffff;
            --border-color: #dbdbdb;
            --border-radius: 4px;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .profile-container {
            max-width: 600px;
            width: 100%;
            padding: 40px 20px;
        }
        
        .profile-card {
            background-color: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
        }
        
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border-color);
            margin: 0 auto 20px;
            display: block;
        }
        
        .profile-name {
            font-size: 28px;
            font-weight: 300;
            margin: 0 0 10px 0;
        }
        
        .profile-title {
            color: var(--text-secondary);
            font-size: 16px;
            margin: 0 0 20px 0;
        }
        
        .profile-details {
            text-align: left;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .detail-icon {
            width: 36px;
            height: 36px;
            background-color: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
        }
        
        .detail-text {
            flex: 1;
        }
        
        .detail-text h4 {
            margin: 0;
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: normal;
        }
        
        .detail-text p {
            margin: 5px 0 0;
            font-size: 16px;
        }
        
        .profile-bio {
            margin-top: 30px;
            font-size: 16px;
            line-height: 1.5;
            text-align: left;
            padding: 0 10px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <img src="views/images/profile/jd.jpg" alt="Foto de perfil" class="profile-photo">
            
            <h1 class="profile-name">Juan Pérez</h1>
            <p class="profile-title">Desarrollador Web</p>
            
            <div class="profile-details">
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="detail-text">
                        <h4>Nombre de usuario</h4>
                        <p>@juanperez</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="detail-text">
                        <h4>Trabajo</h4>
                        <p>Desarrollador Web en Empresa XYZ</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="detail-text">
                        <h4>Vive en</h4>
                        <p>Ciudad de México, México</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-text">
                        <h4>De</h4>
                        <p>Guadalajara, Jalisco</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="detail-text">
                        <h4>Correo</h4>
                        <p>juan.perez@example.com</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="detail-text">
                        <h4>Sitio web</h4>
                        <p>www.juanperez.com</p>
                    </div>
                </div>
            </div>
            
            <div class="profile-bio">
                <p>Apasionado por la tecnología y el diseño. Especializado en desarrollo front-end con React. En mi tiempo libre disfruto de la fotografía y los viajes.</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>