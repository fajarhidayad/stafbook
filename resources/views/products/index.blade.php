<x-layout>
    <h2>Products</h2>

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->has('category'))
        <div class="alert alert-danger">
            <p>{{ $errors->first('category') }}</p>
        </div>
    @endif

    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">+ Tambah
            Produk</button>

        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProductModalLabel">Tambah Produk Baru</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="modal-body" action="{{ route('products.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Deskripsi</th>
                <th scope="col">Kategori</th>
                <th scope="col">Gambar</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>
                        @foreach ($product->categories as $category)
                            <div class="d-flex">
                                <p class="badge text-bg-dark align-items-start">{{ $category->name }}
                                </p>
                                <form
                                    action="{{ route('products.deleteCategory', [
                                        'id' => $product->id,
                                        'categoryId' => $category->id,
                                    ]) }}"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type='submit' class="btn btn-ghost btn-sm"><i class="fa fa-x"></i></button>
                                </form>
                            </div>
                        @endforeach
                        @if (count($product->categories) < 3)
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#{{ $product->id . 'addCategories' }}"><i
                                    class="fa fa-plus"></i></button>
                        @endif
                        <div class="modal fade" id="{{ $product->id . 'addCategories' }}" tabindex="-1"
                            aria-labelledby="{{ $product->id . 'addCateggoriesLabel' }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="{{ $product->id . 'addCateggoriesLabel' }}">
                                            Tambah Kategori Produk</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form class="modal-body" action="{{ route('products.addCategory', $product->id) }}"
                                        method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kategori</label>
                                            <select class="form-select" name="category_id"
                                                aria-label="Default select example">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary me-2"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @foreach ($product->images as $image)
                            <div class="mb-2 d-flex align-items-center">
                                <img src="{{ Storage::url($image->image_url) }}" alt="{{ $product->name }}"
                                    style="max-width: 200px">
                                <form class="modal-body" action="{{ route('products.deleteImage', $product->id) }}"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ms-2"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        @endforeach
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#{{ $product->id . 'upload' }}">
                            <i class="fa fa-upload"></i>
                        </button>
                        <div class="modal fade" id="{{ $product->id . 'upload' }}" tabindex="-1"
                            aria-labelledby="{{ $product->id . 'uploadLabel' }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="{{ $product->id . 'uploadLabel' }}">
                                            Upload Gambar Produk</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form class="modal-body" enctype="multipart/form-data"
                                        action="{{ route('products.uploadImage', $product->id) }}" method="post">
                                        @csrf
                                        @method('POST')
                                        <div class="mb-3">
                                            <label class="form-label">Upload gambar</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary me-2"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('products.delete', $product->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>
