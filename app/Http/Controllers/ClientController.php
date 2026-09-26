<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Client::class);

        $clients = $this->clientService->paginate($request->string('search')->toString() ?: null);

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        $this->authorize('create', Client::class);

        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $this->clientService->create($request->validated());

        return redirect()->route('clients.index')->with('success', 'Client berhasil dibuat.');
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->clientService->update($client, $request->validated());

        return redirect()->route('clients.index')->with('success', 'Client berhasil diperbarui.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('delete', $client);

        $this->clientService->delete($client);

        return redirect()->route('clients.index')->with('success', 'Client berhasil dihapus.');
    }
}