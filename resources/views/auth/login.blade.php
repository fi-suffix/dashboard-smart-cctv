<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - FaceGuard AI</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-dark-bg text-text-primary font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-accent-blue flex items-center justify-center text-white font-bold text-xl mb-4 shadow-lg shadow-blue-500/20">FG</div>
            <h1 class="text-2xl font-semibold tracking-tight">FaceGuard AI</h1>
            <p class="text-sm text-text-secondary mt-1">Sign in to your dashboard</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-danger/10 border border-danger/20 text-danger text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-success/10 border border-success/20 text-success text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="bg-dark-card border border-border-subtle rounded-2xl p-6 space-y-4 shadow-xl">
            @csrf

            <div>
                <label for="identifier" class="block text-sm font-medium mb-1.5">Email or Username</label>
                <input id="identifier" type="text" name="identifier" value="{{ old('identifier') }}" required autofocus autocomplete="username"
                       class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full rounded-lg border border-border-subtle bg-dark-elevated px-3.5 py-2.5 text-sm text-text-primary focus:border-accent-blue focus:outline-none placeholder:text-text-secondary">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-text-secondary cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-border-subtle bg-dark-elevated text-accent-blue focus:ring-accent-blue">
                    Remember me
                </label>
            </div>

            <button type="submit"
                    class="w-full px-4 py-2.5 rounded-lg bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium transition-colors">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>