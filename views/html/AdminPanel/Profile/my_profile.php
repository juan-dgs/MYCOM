<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1877f2;
            --secondary-color: #f0f2f5;
            --text-color: #050505;
            --text-secondary: #65676b;
            --white: #ffffff;
            --border-radius: 8px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }
        
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: var(--white);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .cover-photo {
            height: 350px;
            background-color: #e9ebee;
            background-image: url('https://via.placeholder.com/1200x350');
            background-size: cover;
            background-position: center;
            position: relative;
            border-bottom-left-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
        }
        
        .profile-photo-container {
            position: absolute;
            bottom: -75px;
            left: 20px;
        }
        
        .profile-photo {
            width: 168px;
            height: 168px;
            border-radius: 50%;
            border: 4px solid var(--white);
            background-color: #ddd;
            object-fit: cover;
        }
        
        .profile-nav {
            height: 60px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .profile-name {
            margin-left: 200px;
            padding-top: 15px;
            font-size: 28px;
            font-weight: bold;
        }
        
        .profile-content {
            display: flex;
            padding: 20px;
        }
        
        .left-column {
            flex: 0 0 350px;
            margin-right: 20px;
        }
        
        .right-column {
            flex: 1;
        }
        
        .info-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-card h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            font-size: 20px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .info-icon {
            width: 36px;
            height: 36px;
            background-color: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .info-text {
            flex: 1;
        }
        
        .info-text h4 {
            margin: 0;
            font-size: 16px;
            color: var(--text-secondary);
        }
        
        .info-text p {
            margin: 5px 0 0;
            font-size: 17px;
        }
        
        .edit-button {
            background-color: var(--secondary-color);
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-weight: 600;
            cursor: pointer;
            float: right;
        }
        
        .edit-button:hover {
            background-color: #e4e6e9;
        }
        
        .nav-tabs {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            height: 100%;
        }
        
        .nav-tabs li {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 15px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }
        
        .nav-tabs li:hover {
            background-color: #f0f0f0;
        }
        
        .nav-tabs li.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .action-buttons {
            display: flex;
            align-items: center;
        }
        
        .action-button {
            background-color: #e7f3ff;
            color: var(--primary-color);
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            margin-left: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .action-button i {
            margin-right: 5px;
        }
        
        .action-button:hover {
            background-color: #dbe7f2;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Foto de portada -->
        <div class="cover-photo">
            <div class="profile-photo-container">
                <img src="https://via.placeholder.com/168" alt="Foto de perfil" class="profile-photo">
            </div>
        </div>
        
        <!-- Barra de navegación -->
        <div class="profile-nav">
            <ul class="nav-tabs">
                <li class="active">Publicaciones</li>
                <li>Información</li>
                <li>Fotos</li>
                <li>Amigos</li>
                <li>Más</li>
            </ul>
            
            <div class="action-buttons">
                <button class="action-button">
                    <i class="fas fa-plus"></i> Agregar a historia
                </button>
                <button class="action-button">
                    <i class="fas fa-pencil-alt"></i> Editar perfil
                </button>
            </div>
        </div>
        
        <!-- Contenido del perfil -->
        <div class="profile-content">
            <!-- Columna izquierda -->
            <div class="left-column">
                <!-- Tarjeta de información -->
                <div class="info-card">
                    <h3>Información</h3>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-text">
                            <h4>Nombre de usuario</h4>
                            <p>@juanperez</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="info-text">
                            <h4>Trabajo</h4>
                            <p>Desarrollador Web en Empresa XYZ</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="info-text">
                            <h4>Vive en</h4>
                            <p>Ciudad de México, México</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-text">
                            <h4>De</h4>
                            <p>Guadalajara, Jalisco</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="info-text">
                            <h4>Estado civil</h4>
                            <p>Soltero/a</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-text">
                            <h4>Se unió en</h4>
                            <p>Enero 2020</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tarjeta de contacto -->
                <div class="info-card">
                    <h3>Información de contacto
                        <button class="edit-button">Editar</button>
                    </h3>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-text">
                            <h4>Teléfono</h4>
                            <p>+52 55 1234 5678</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-text">
                            <h4>Correo electrónico</h4>
                            <p>juan.perez@example.com</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="info-text">
                            <h4>Sitio web</h4>
                            <p>www.juanperez.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columna derecha (publicaciones) -->
            <div class="right-column">
                <div class="info-card">
                    <h3>Publicaciones</h3>
                    <p style="text-align: center; padding: 50px 0; color: var(--text-secondary);">
                        Aquí aparecerán tus publicaciones
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>