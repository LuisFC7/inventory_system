<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Correo | INVEX</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .content {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img 
                src="{{ env('APP_URL') }}/storage/icons/warehouse-stock-svgrepo-com.svg" 
                alt="Logo INVEX" 
                width="80"
                style="height: auto; margin: 0 auto;"
            >
        </div>
        
        <div class="content">
            <h1 style="color: #111827; font-size: 24px; font-weight: 600; text-align: center; margin-bottom: 20px;">
                TE AMO
            </h1>
            
            <p style="font-size: 16px; margin-bottom: 20px; text-align: center;">
                TE AMO MUCHO <strong>PAU</strong>.
            </p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="#" style="background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Acceder al Sistema
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} INVEX. Todos los derechos reservados.</p>
            <p style="margin-top: 10px;">
                <a href="#" style="color: #2563eb; text-decoration: none;">Políticas de Privacidad</a> | 
                <a href="#" style="color: #2563eb; text-decoration: none;">Contacto</a>
            </p>
        </div>
    </div>
</body>
@include('partials.footer')
</html>