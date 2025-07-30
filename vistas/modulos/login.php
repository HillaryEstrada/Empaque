<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mango  - Iniciar Sesión</title>
    
  
    
    <style>
        /* Importar fuentes de Google */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FF6B35 0%, #F7931E 25%, #FFD23F 50%, #06D6A0 75%, #118AB2 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animación de gradiente */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Elementos decorativos de fondo */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1.5" fill="rgba(255,255,255,0.15)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="1.5" fill="rgba(255,255,255,0.12)"/><circle cx="90" cy="30" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Contenedor principal del login */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            padding: 3rem 2.5rem;
            max-width: 450px;
            width: 100%;
            position: relative;
            z-index: 10;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 35px 60px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.3);
        }

        /* Encabezado del login */
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .login-header::before {
            content: '🥭';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 3rem;
            opacity: 0.1;
            z-index: -1;
        }

        .login-header i {
            font-size: 4rem;
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .login-header h2 {
            color: #2C3E50;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .login-header p {
            color: #7F8C8D;
            font-weight: 400;
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* Estilos de formulario */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2C3E50;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: #FF6B35;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #E8E8E8;
            border-radius: 15px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #BDC3C7;
            font-weight: 300;
        }

        /* Botón de login */
        .btn-login {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #FF6B35 0%, #F7931E 50%, #FFD23F 100%);
            border: none;
            border-radius: 15px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, #E55A2B 0%, #E8831A 50%, #E6C236 100%);
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-login i {
            margin-right: 0.7rem;
            font-size: 1.1rem;
        }

        /* Efectos adicionales */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF6B35, #F7931E, #FFD23F, #06D6A0);
            border-radius: 25px 25px 0 0;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .login-card {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .login-header h2 {
                font-size: 1.8rem;
            }
            
            .login-header i {
                font-size: 3rem;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem 1rem;
            }
            
            .login-header {
                margin-bottom: 2rem;
            }
            
            .form-control {
                padding: 0.9rem 1rem;
            }
            
            .btn-login {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        /* Animaciones adicionales */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Clases de utilidad */
        .text-muted {
            color: #7F8C8D !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .w-100 {
            width: 100% !important;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-seedling"></i>
            <h2>Mango Tropical</h2>
            <p class="text-muted">Sistema de Empaque</p>
        </div>

        <form action="../../index.php" method="POST">
            <!-- Campo oculto para identificar el login -->
            <input type="hidden" name="opcion" value="login">

            <div class="form-group">
                <label for="usuario" class="form-label">
                    <i class="fas fa-user"></i> Usuario
                </label>
                <input type="text" 
                       name="usuario" 
                       id="usuario" 
                       class="form-control" 
                       required 
                       placeholder="Ingresa tu usuario"
                       autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="contrasena" class="form-label">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" 
                       name="contrasena" 
                       id="contrasena" 
                       class="form-control" 
                       required 
                       placeholder="Ingresa tu contraseña"
                       autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
            </button>
        </form>
    </div>

    <script>
        // Efecto de enfoque automático
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('usuario').focus();
        });

        // Validación básica del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario').value.trim();
            const contrasena = document.getElementById('contrasena').value;

            if (usuario === '' || contrasena === '') {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "¡Campos Requeridos!",
                    text: "Por favor completa todos los campos",
                    confirmButtonText: "Entendido",
                    confirmButtonColor: "#ffc107",
                    background: "#ffffff",
                    color: "#333333",
                    iconColor: "#ffc107",
                    width: "500px",
                    padding: "2rem",
                    backdrop: "rgba(0,0,0,0.4)",
                    allowOutsideClick: false,
                    customClass: {
                        popup: "colored-toast"
                    }
                });
            }
        });

        // Simulación de mensaje de logout para demo
        setTimeout(() => {
            Swal.fire({
                icon: 'info',
                title: '¡Bienvenido!',
                text: 'Este es el preview del sistema Mango Pack',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#FF6B35',
                color: 'white'
            });
        }, 1000);
    </script>
</body>
</html>