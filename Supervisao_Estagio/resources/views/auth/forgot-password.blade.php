<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Recuperar Senha - Sistema de Estágios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(145deg, #0B2B5E 0%, #1B3B6F 30%, #2A1B4E 100%);
            background-attachment: fixed;
            position: relative;
            padding: 20px;
        }

        /* Elementos decorativos de fundo */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 60%);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 160px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="0.4" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x bottom;
            background-size: cover;
            pointer-events: none;
            opacity: 0.5;
        }

        .container {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(0px);
            border-radius: 2rem;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255,255,255,0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            z-index: 2;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 55px -12px rgba(0, 0, 0, 0.45);
        }

        /* Cabeçalho com ícone */
        .header-icon {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .icon-circle {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 12px 20px -10px rgba(245, 158, 11, 0.4);
        }

        .icon-circle i {
            font-size: 2.5rem;
            color: white;
        }

        .container h1 {
            text-align: center;
            color: #1a2c3e;
            margin-bottom: 0.5rem;
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #1F2B4E, #2D3A5E);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .descricao {
            text-align: center;
            color: #6c7a91;
            font-size: 0.85rem;
            margin-bottom: 1.8rem;
            font-weight: 400;
            line-height: 1.4;
            padding: 0 0.5rem;
        }

        /* Alertas modernos */
        .alert-success {
            background-color: #f0fff4;
            border-left: 4px solid #38a169;
            color: #276749;
            padding: 0.9rem 1.2rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .alert-success::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 1.1rem;
        }

        .alert-danger {
            background-color: #fff5f5;
            border-left: 4px solid #e53e3e;
            color: #c53030;
            padding: 0.9rem 1.2rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .alert-danger::before {
            content: "\f06a";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 1.1rem;
        }

        /* Formulário refinado */
        .form-group {
            margin-bottom: 1.8rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3e5f;
            font-size: 0.85rem;
            letter-spacing: -0.2px;
        }

        label i {
            margin-right: 6px;
            color: #667eea;
        }

        .input-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            color: #a0aec0;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        input[type="email"] {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
            outline: none;
            color: #1a2c3e;
        }

        input[type="email"]:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        input[type="email"]:focus + i,
        .input-icon:focus-within i {
            color: #f59e0b;
        }

        /* Botão principal */
        .btn {
            display: inline-block;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            padding: 0.9rem 1.5rem;
            font-size: 1rem;
            border-radius: 1.2rem;
            transition: all 0.2s ease;
            border: none;
            background: linear-gradient(105deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 8px 18px -6px rgba(245, 158, 11, 0.4);
            letter-spacing: 0.3px;
            width: 100%;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(105deg, #f39c12, #e67e22);
            box-shadow: 0 12px 22px -8px rgba(245, 158, 11, 0.5);
        }

        .btn:active {
            transform: translateY(1px);
        }

        /* Links */
        .links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #edf2f7;
        }

        .links a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #5a67d8;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .links a i {
            font-size: 0.8rem;
            transition: transform 0.2s;
        }

        .links a:hover {
            color: #2b3bb3;
            text-decoration: underline;
        }

        .links a:hover i {
            transform: translateX(-3px);
        }

        /* Rodapé decorativo */
        .footer-note {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.7rem;
            color: #8e9aaf;
            letter-spacing: 0.3px;
        }

        /* Responsividade */
        @media (max-width: 520px) {
            .container {
                padding: 1.8rem 1.5rem;
                max-width: 95%;
            }
            .container h1 {
                font-size: 1.6rem;
            }
            .icon-circle {
                width: 55px;
                height: 55px;
            }
            .icon-circle i {
                font-size: 2rem;
            }
            .descricao {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-icon">
            <div class="icon-circle">
                <i class="fas fa-key"></i>
            </div>
            <h1>Recuperar Senha</h1>
            <div class="descricao">
                <i class="fas fa-envelope-open-text" style="margin-right: 6px; color: #f59e0b;"></i> 
                Enviaremos um link para redefinir sua senha
            </div>
        </div>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label><i class="far fa-envelope"></i> E-mail cadastrado</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-paper-plane"></i> Enviar link de recuperação
            </button>

            <div class="links">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i> Voltar para o login
                </a>
            </div>

            <div class="footer-note">
                <i class="fas fa-lock"></i> Segurança em primeiro lugar • Sistema de Estágios
            </div>
        </form>
    </div>
</body>

</html>