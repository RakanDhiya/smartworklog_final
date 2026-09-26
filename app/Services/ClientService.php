<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientService
{
    public function paginate(?string $search = null): LengthAwarePaginator
    {
        return Client::query()
            ->when($search, fn ($query) => $query->where('name', 'ilike', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);

        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete(); // soft delete
    }
}