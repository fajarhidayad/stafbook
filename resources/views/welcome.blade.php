<x-layout>

    <div style="min-height: 80vh" class="d-flex flex-column align-items-center justify-content-center">
        <h1>Prod.ly</h1>
        <p>Product management</p>

        @guest
            <div class="d-flex">
                <a class="btn btn-primary me-3" href="{{ route('show.login') }}">Login</a>
                <a class="btn btn-outline-primary" href="{{ route('show.register') }}">Register</a>
            </div>
        @endguest
        @auth
            <div class="d-flex">
                <a class="btn btn-primary me-3" href="{{ route('products.index') }}">Products</a>
                <a class="btn btn-outline-primary" href="{{ route('categories.index') }}">Categories</a>
            </div>
        @endauth
    </div>

</x-layout>
