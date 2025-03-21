<x-layout title="Login">
    <section style="height: 80vh" class="d-flex justify-content-center align-items-center">
        <form action="{{ route('login') }}" method="POST" class="container mb-5" style="max-width: 450px">
            <h2>Login</h2>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    required>
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </section>
    @if ($errors->has('credentials'))
        <div class="alert alert-danger container">
            <p>{{ $errors->first('credentials') }}</p>
        </div>
    @endif

</x-layout>
