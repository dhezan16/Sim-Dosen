<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="login-page">
        <div class="login-card">
            <div class="logo">SD</div>
            <h1>SIM-DOSEN</h1>
            <p>Sistem Informasi Manajemen Dosen<br>Universitas Muhammadiyah Bengkulu</p>

            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control"
                        required autofocus autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="form-control" required
                        autocomplete="current-password">
                </div>

                <div class="form-group">
                    <label class="checkbox"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>
        </div>
    </div>
</body>

</html>
