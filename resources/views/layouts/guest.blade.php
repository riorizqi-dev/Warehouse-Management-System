<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
        
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Poppins', sans-serif;
                color: #fff;
            }

            body{
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background: linear-gradient(135deg, #131313 0%, #343a40 100%);
            }

            .container{
                position: relative;
                width: 920px;
                height: min(760px, 94vh);
                border: 2px solid #435ebe;
                box-shadow: 0 0 25px rgba(67, 94, 190, 0.3);
                overflow: hidden;
                border-radius: 20px;
                background: rgba(19, 19, 19, 0.9);
                backdrop-filter: blur(10px);
            }

            @media (max-width: 768px) {
                .container {
                    width: 95%;
                    max-width: 500px;
                    height: auto;
                    min-height: 500px;
                }
            }

            .container .form-box{
                position: absolute;
                top: 0;
                width: 52%;
                height: 100%;
                display: flex;
                justify-content: center;
                flex-direction: column;
                z-index: 10;
                overflow-y: auto;
                scrollbar-width: thin;
                scrollbar-color: rgba(96, 165, 250, 0.55) transparent;
                padding-bottom: 14px;
            }

            .container .form-box::-webkit-scrollbar {
                width: 7px;
            }

            .container .form-box::-webkit-scrollbar-thumb {
                background: rgba(96, 165, 250, 0.55);
                border-radius: 999px;
            }

            .form-box.Login{
                left: 0;
                padding: 28px 34px;
            }

            .form-box.Login .animation{
                transform: translateX(0%);
                transition: .7s;
                opacity: 1;
                transition-delay: calc(.1s * var(--S));
            }

            .container.active .form-box.Login .animation{
                transform: translateX(-120%);
                opacity: 0;
                transition-delay: calc(.1s * var(--D));
            }

            .form-box.Register{
                right: 0;
                padding: 26px 38px;
                z-index: 10;
            }

            .form-box form {
                width: 100%;
                max-width: 430px;
                margin-inline: auto;
                padding-bottom: 16px;
            }

            .form-box.Register .animation{
                transform: translateX(120%);
                transition: .7s ease;
                opacity: 0;
                filter: blur(10px);
                transition-delay: calc(.1s * var(--S));
                pointer-events: none;
            }

            .container.active .form-box.Register .animation{
                transform: translateX(0%);
                opacity: 1;
                filter: blur(0px);
                transition-delay: calc(.1s * var(--li));
                pointer-events: auto !important;
            }

            .container.active .form-box.Register input,
            .container.active .form-box.Register button,
            .container.active .form-box.Register .btn,
            .container.active .form-box.Register a {
                pointer-events: auto !important;
                cursor: pointer !important;
                z-index: 999 !important;
            }

            .form-box h2{
                font-size: 32px;
                text-align: center;
                margin-bottom: 20px;
                margin-top: 0;
            }

            .form-box.Register h2{
                margin-bottom: 15px;
                margin-top: 0;
            }

            .form-box.Login h2{
                margin-bottom: 25px;
            }

            .form-box .input-box{
                position: relative;
                width: 100%;
                height: 50px;
                margin-top: 20px;
                margin-bottom: 8px;
            }

            .form-box.Login .input-box{
                margin-top: 24px;
                margin-bottom: 10px;
            }

            .form-box.Register .input-box{
                margin-top: 18px;
                margin-bottom: 8px;
            }

            .input-box input{
                width: 100%;
                height: 100%;
                background: rgba(15, 23, 42, 0.82);
                border: 1px solid rgba(59, 130, 246, 0.4);
                outline: none;
                font-size: 16px;
                color: #e2e8f0;
                font-weight: 500;
                border-radius: 12px;
                padding-right: 52px;
                padding-left: 14px;
                transition: .5s;
                cursor: text;
                position: relative;
                z-index: 5;
            }

            .form-box.Register .input-box input {
                pointer-events: auto !important;
                cursor: text !important;
                z-index: 1000 !important;
            }

            .input-box .password-toggle {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 5px;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
                color: #94a3b8;
                transition: color 0.3s;
            }

            .input-box .password-toggle:hover {
                color: #435ebe;
            }

            .input-box .password-toggle box-icon {
                font-size: 20px;
            }

            .input-box input:focus,
            .input-box input:valid{
                border-color: #22d3ee;
                box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.16);
            }

            .input-box input::placeholder{
                color: rgba(255, 255, 255, 0.4);
            }

            .input-box input:-webkit-autofill,
            .input-box input:-webkit-autofill:hover,
            .input-box input:-webkit-autofill:focus,
            .input-box input:-webkit-autofill:active {
                -webkit-box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.95) inset !important;
                -webkit-text-fill-color: #e2e8f0 !important;
                border: 1px solid rgba(59, 130, 246, 0.45) !important;
                caret-color: #e2e8f0;
                transition: background-color 9999s ease-in-out 0s;
            }

            .input-box label{
                position: absolute;
                top: 50%;
                left: 14px;
                transform: translateY(-50%);
                font-size: 15px;
                color: #93c5fd;
                transition: .5s;
                pointer-events: none;
                white-space: nowrap;
            }

            .input-box input:focus ~ label,
            .input-box input:valid ~ label{
                top: -10px;
                left: 10px;
                font-size: 12px;
                color: #22d3ee;
                background: #111827;
                padding: 1px 7px;
                border-radius: 999px;
            }

            .input-box box-icon{
                position: absolute;
                top: 50%;
                right: 14px;
                font-size: 18px;
                transform: translateY(-50%);
                color: #94a3b8;
                z-index: 6;
            }

            .input-box input:focus ~ box-icon,
            .input-box input:valid ~ box-icon{
                color: #435ebe;
            }

            .input-box.has-password-toggle > box-icon {
                display: none !important;
            }
            
            .input-box.has-password-toggle .password-toggle box-icon {
                display: block !important;
            }

            .btn{
                position: relative;
                width: 100%;
                height: 45px;
                background: linear-gradient(135deg, #1d4ed8, #22d3ee);
                border-radius: 40px;
                cursor: pointer;
                font-size: 18px;
                font-weight: 600;
                border: 1px solid rgba(34, 211, 238, 0.7);
                overflow: hidden;
                z-index: 1;
                color: #f8fafc;
                box-shadow: 0 10px 24px rgba(34, 211, 238, 0.24);
            }

            .form-box.Register .btn {
                pointer-events: auto !important;
                cursor: pointer !important;
                z-index: 1000 !important;
            }

            .btn::before{
                content: "";
                position: absolute;
                height: 300%;
                width: 100%;
                background: linear-gradient(180deg, rgba(255,255,255,0.2), rgba(255,255,255,0));
                top: -100%;
                left: 0;
                z-index: -1;
                transition: .5s;
            }

            .btn:hover:before{
                top: 0;
            }

            .regi-link{
                font-size: 14px;
                text-align: center;
                margin: 15px 0 5px;
            }

            .form-box.Register .regi-link{
                margin: 12px 0 5px;
            }

            .form-box.Login .regi-link{
                margin: 18px 0 8px;
            }

            .regi-link a{
                text-decoration: none;
                color: #435ebe;
                font-weight: 600;
                position: relative;
                z-index: 10;
                cursor: pointer;
            }

            .regi-link a:hover{
                text-decoration: underline;
            }

            .info-content{
                position: absolute;
                top: 0;
                height: 100%;
                width: 48%;
                display: flex;
                justify-content: center;
                flex-direction: column;
            }

            .info-content.Login{
                right: 0;
                text-align: right;
                padding: 0 40px 60px 150px;
                z-index: 1;
            }

            .info-content.Login .animation{
                transform: translateX(0);
                transition: .7s ease;
                transition-delay: calc(.1s * var(--S));
                opacity: 1;
                filter: blur(0px);
            }

            .container.active .info-content.Login .animation{
                transform: translateX(120%);
                opacity: 0;
                filter: blur(10px);
                transition-delay: calc(.1s * var(--D));
            }

            .info-content.Register{
                left: 0;
                text-align: left;
                padding: 0 150px 60px 38px;
                pointer-events: none;
                z-index: 1;
            }

            .info-content.Register .animation{
                transform: translateX(-120%);
                transition: .7s ease;
                opacity: 0;
                filter: blur(10PX);
                transition-delay: calc(.1s * var(--S));
                pointer-events: none;
            }

            .container.active .info-content.Register .animation{
                transform: translateX(0%);
                opacity: 1;
                filter: blur(0);
                transition-delay: calc(.1s * var(--li));
                pointer-events: none;
            }

            .info-content h2{
                text-transform: uppercase;
                font-size: 36px;
                line-height: 1.3;
            }

            .info-content p{
                font-size: 16px;
            }

            .info-panel {
                margin-top: 18px;
                display: grid;
                gap: 9px;
            }

            .info-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 12px;
                border-radius: 10px;
                border: 1px solid rgba(103, 232, 249, 0.28);
                background: rgba(15, 23, 42, 0.32);
                color: #dbeafe;
                font-size: 14px;
                font-weight: 500;
                width: fit-content;
                backdrop-filter: blur(6px);
            }

            .kpi-grid {
                margin-top: 14px;
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                max-width: 280px;
            }

            .kpi-item {
                border: 1px solid rgba(96, 165, 250, 0.28);
                border-radius: 12px;
                padding: 10px 12px;
                background: linear-gradient(135deg, rgba(37, 99, 235, 0.22), rgba(34, 211, 238, 0.12));
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
            }

            .kpi-item strong {
                display: block;
                font-size: 18px;
                line-height: 1.1;
                color: #f8fafc;
            }

            .kpi-item small {
                color: #bfdbfe;
                font-size: 12px;
            }

            .container .curved-shape{
                position: absolute;
                right: 20px;
                top: -5px;
                height: 600px;
                width: 850px;
                background: linear-gradient(45deg, #131313, #435ebe);
                transform: rotate(10deg) skewY(40deg);
                transform-origin: bottom right;
                transition: 1.5s ease;
                transition-delay: 1.6s;
                z-index: 0;
                pointer-events: none;
            }

            .container .curved-shape::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at 30% 28%, rgba(34, 211, 238, 0.2), transparent 35%),
                    repeating-linear-gradient(
                        -45deg,
                        rgba(255, 255, 255, 0.04) 0 2px,
                        transparent 2px 14px
                    );
                opacity: 0.9;
            }

            .container.active .curved-shape{
                transform: rotate(0deg) skewY(0deg);
                right: -80px;
                width: 1020px;
                transition-delay: .5s;
            }

            .container .curved-shape2{
                position: absolute;
                left: 270px;
                top: 100%;
                height: 700px;
                width: 850px;
                background: #131313;
                border-top: 3px solid #435ebe;
                transform: rotate(0deg) skewY(0deg);
                transform-origin: bottom left;
                transition: 1.5s ease;
                transition-delay: .5s;
                z-index: 0;
                pointer-events: none;
            }

            .container .curved-shape2::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at 75% 35%, rgba(59, 130, 246, 0.18), transparent 40%),
                    repeating-linear-gradient(
                        45deg,
                        rgba(34, 211, 238, 0.05) 0 1px,
                        transparent 1px 12px
                    );
                opacity: 0.7;
            }

            .container.active .curved-shape2{
                transform: rotate(-11deg) skewY(-41deg);
                transition-delay: 1.2s;
            }

            .mt-2 {
                margin-top: 8px;
            }

            .text-red-400 {
                color: #f87171;
            }

            .text-sm {
                font-size: 14px;
            }

            .alert-toast {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 320px;
                max-width: 400px;
                z-index: 9999;
                animation: slideInRight 0.3s ease-out;
                transition: opacity 0.3s, transform 0.3s;
            }

            .alert-toast.alert-error {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border: 1px solid #bd2130;
                box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            }

            .alert-content {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 16px 20px;
                border-radius: 12px;
                color: #fff;
            }

            .alert-message {
                flex: 1;
            }

            .alert-message strong {
                display: block;
                font-size: 15px;
                margin-bottom: 4px;
            }

            .alert-message small {
                font-size: 13px;
                opacity: 0.9;
            }

            .alert-close {
                background: transparent;
                border: none;
                color: #fff;
                font-size: 24px;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                transition: background 0.2s;
            }

            .alert-close:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .error-input {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2) !important;
            }

            .error-input:focus {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.3) !important;
            }

            @media (max-width: 768px) {
                .container {
                    width: 95%;
                    max-width: 500px;
                    height: auto;
                    min-height: 650px;
                }

                .container .form-box,
                .info-content {
                    width: 100%;
                }

                .form-box.Login,
                .form-box.Register {
                    padding: 15px 30px;
                }

                .container .form-box {
                    overflow-y: visible;
                }

                .form-box h2 {
                    font-size: 28px;
                    margin-bottom: 15px;
                }

                .info-content.Login,
                .info-content.Register {
                    padding: 20px 30px;
                }

                .info-content h2 {
                    font-size: 28px;
                }

                .info-content p {
                    font-size: 14px;
                }

                .kpi-grid {
                    grid-template-columns: 1fr;
                    max-width: 220px;
                }
            }
        </style>

    </head>
    <body class="font-sans antialiased">
        {{ $slot }}
        <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
        <script>
            const container = document.querySelector('.container');
            const LoginLink = document.querySelector('.SignInLink');
            const RegisterLink = document.querySelector('.SignUpLink');

            if (RegisterLink) {
                RegisterLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    container.classList.add('active');
                });
            }

            if (LoginLink) {
                LoginLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    container.classList.remove('active');
                });
            }
        </script>
        
    </body>
</html>
