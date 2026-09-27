@if ($errors->any())
    <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div>
    <label class="block text-sm font-medium text-gray-700">Nama</label>
    <input type="text" name="name" value="{{ old('name') }}"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="2"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">{{ old('description') }}</textarea>
</div>