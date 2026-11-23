<?php
namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
class AdminClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('Id', 'desc')->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }
    public function active()
    {
        $clients = Client::active()->orderBy('Id', 'desc')->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }
    public function blocked()
    {
        $clients = Client::blocked()->orderBy('Id', 'desc')->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }
    public function ban($id)
    {
        $client = Client::findOrFail($id);
        $client->IsBlocked = true;
        $client->save();

        return redirect()->route('admin.clients.index')->with('success', "Клиент {$client->Surname} {$client->Name} заблокирован");
    }
    public function unban($id)
    {
        $client = Client::findOrFail($id);
        $client->IsBlocked = false;
        $client->save();

        return redirect()->route('admin.clients.index')->with('success', "Клиент {$client->Surname} {$client->Name} разблокирован");
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $clients = Client::where(function($query) use ($search) {
                $query->where('Surname', 'like', "%{$search}%")
                      ->orWhere('Name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('login', 'like', "%{$search}%")
                      ->orWhere('Phone', 'like', "%{$search}%");
            })
            ->orderBy('Id', 'desc')
            ->paginate(15);
        
        return view('admin.clients.index', compact('clients'));
    }

    public function show($id)
    {
        $client = Client::with('orders')->findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }
}