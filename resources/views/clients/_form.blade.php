@if ($errors->any())
    <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div>
    <label class="block text-sm font-medium text-gray-700">Nama</label>
    <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Tipe</label>
    <select name="client_type" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
        <option value="individual" @selected(old('client_type', $client->client_type ?? '') === 'individual')>Individual</option>
        <option value="company" @selected(old('client_type', $client->client_type ?? '') === 'company')>Company</option>
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Telepon</label>
    <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Alamat</label>
    <textarea name="address" rows="2"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">{{ old('address', $client->address ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Catatan</label>
    <textarea name="notes" rows="2"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">{{ old('notes', $client->notes ?? '') }}</textarea>
</div>