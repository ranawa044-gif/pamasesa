<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — PAMASESA</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --app-primary: #4f46e5;
            --app-primary-dark: #4338ca;
            --app-text: #0f172a;
            --app-muted: #64748b;
            --font-heading: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-body: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            font-family: var(--font-body);
            color: var(--app-text);
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 10% 10%, rgba(79, 70, 229, 0.25) 0px, transparent 50%),
                radial-gradient(at 90% 90%, rgba(13, 148, 136, 0.20) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(30, 41, 59, 0.8) 0px, transparent 100%);
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            padding: 2.5rem 2rem;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon-box {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1.15rem;
            background: linear-gradient(135deg, #4f46e5 0%, #0d9488 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5);
            margin-bottom: 1rem;
        }

        .brand-title {
            font-family: var(--font-heading);
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .brand-subtitle {
            font-size: 0.85rem;
            color: var(--app-muted);
            font-weight: 500;
        }

        .form-label {
            font-family: var(--font-heading);
            font-weight: 650;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .input-group-text {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
            padding-left: 1rem;
            padding-right: 0.85rem;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        .form-control {
            border-color: #cbd5e1;
            padding: 0.75rem 1rem;
            font-size: 0.925rem;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--app-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }

        .btn-login {
            font-family: var(--font-heading);
            font-weight: 700;
            background: var(--app-primary);
            border-color: var(--app-primary);
            color: #ffffff;
            padding: 0.8rem;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.4);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-login:hover {
            background: var(--app-primary-dark);
            border-color: var(--app-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -4px rgba(79, 70, 229, 0.5);
        }

        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
            border-radius: 0.75rem;
            font-size: 0.875rem;
        }

        .login-card a.text-muted:hover {
            color: var(--app-primary) !important;
        }
    </style>
</head>
<body>
<div id="particles-js"></div>
<div class="login-wrapper">
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-icon-box">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1 class="brand-title">PAMASESA</h1>
            <p class="brand-subtitle">Manajemen Seminar & Sidang Akhir D3 SI</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-5"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="post" action="{{ route('login.attempt') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Pengguna</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@pamasesa.local" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-slate-700 small" for="remember">
                        Ingat sesi saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
            </button>
        </form>

        <div class="mt-4 text-center">
            <small class="text-muted" style="font-size: 0.75rem; line-height: 1.5; display: inline-block;">
                &copy; {{ date('Y') }} Chepy Perdana.<br>
                Supported by <a href="https://luminara.web.id" target="_blank" class="text-decoration-none text-muted fw-bold">Luminara.web.id</a>
            </small>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    particlesJS('particles-js', {
        particles: {
            number: {
                value: 65,
                density: {
                    enable: true,
                    value_area: 800
                }
            },
            color: {
                value: ['#818cf8', '#4f46e5', '#06b6d4']
            },
            shape: {
                type: 'circle'
            },
            opacity: {
                value: 0.6,
                random: false
            },
            size: {
                value: 3,
                random: true
            },
            line_linked: {
                enable: true,
                distance: 150,
                color: '#6366f1',
                opacity: 0.35,
                width: 1.2
            },
            move: {
                enable: true,
                speed: 1.8,
                direction: 'none',
                random: false,
                straight: false,
                out_mode: 'out',
                bounce: false
            }
        },
        interactivity: {
            detect_on: 'window',
            events: {
                onhover: {
                    enable: true,
                    mode: 'grab'
                },
                onclick: {
                    enable: true,
                    mode: 'push'
                },
                resize: true
            },
            modes: {
                grab: {
                    distance: 180,
                    line_linked: {
                        opacity: 0.85
                    }
                },
                push: {
                    particles_nb: 3
                }
            }
        },
        retina_detect: true
    });
</script>
</body>
</html>

