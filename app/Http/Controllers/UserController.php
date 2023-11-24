<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Conversation;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchType = $request->input('search_type');
        $searchTerm = $request->input('search_term');

        // total de mensagens não lidas 
        $qtdMsg = auth()->user()->totalUnreadMessagesCount();

        $query = User::query();

        if ($searchType && $searchTerm) {
            switch ($searchType) {
                case 'user':
                        $query->where('name', 'like', "%$searchTerm%");
                    break;
                case 'role':
                        $query->where('role', 'like', "%$searchTerm%");
                    break;
            }
        }

        $users = $query->paginate(10)->appends(['search_type' => $searchType, 'search_term' => $searchTerm]);

        return view('admin.users.index', compact('users', 'searchTerm', 'qtdMsg'));
    }

    public function show($email, Request $request)
    {
        $user = User::where('email', $email)->firstOrFail();

        $coursesCreated = null;
        if ($user->role == "creator") {
            $coursesCreated = $user->courses;
        }

        $relations = ['favorites.course', 'subscriptions.course', 'reports.course', 'courseRatings.course', 'completions.course'];

        foreach ($relations as $relation) {
            $user->load([$relation => function ($query) {
                $query->withTrashed();
            }]);
        }

        $searchTerm = null;

        $searchTerm = $request->input('search_term');
        $role = $request->input('role');

        if ($searchTerm) {
            switch ($role) {
                case 'inscrito':
                        $user->subscriptions = $user->subscriptions->filter(function ($subscription) use ($searchTerm) {
                            return stripos($subscription->course->title, $searchTerm) !== false;
                        });
                    break;
                case 'criados':
                        $coursesCreated = $coursesCreated->filter(function ($created) use ($searchTerm) {
                            return stripos($created->title, $searchTerm) !== false;
                        });
                    break;
                case 'favoritado':
                        $user->favorites = $user->favorites->filter(function ($favorite) use ($searchTerm) {
                            return stripos($favorite->course->title, $searchTerm) !== false;
                        });
                    break;
                case 'concluido':
                        $user->completions = $user->completions->filter(function ($complet) use ($searchTerm) {
                            return stripos($complet->course->title, $searchTerm) !== false;
                        });
                    break;
                case 'denuncias':
                        $user->reports = $user->reports->filter(function ($report) use ($searchTerm) {
                            return stripos($report->course->title, $searchTerm) !== false;
                        });
                    break;
                case 'avaliacoes':
                        $user->courseRatings = $user->courseRatings->filter(function ($rating) use ($searchTerm) {
                            return stripos($rating->course->title, $searchTerm) !== false;
                        });
                    break;
            }
        }    

        return view('admin.users.show', compact('user', 'coursesCreated', 'role' , 'searchTerm'));
    }

    public function edit($email)
    {
        $user = User::where('email', $email)->firstOrFail();

        if (Gate::denies('edit-profile', $user->email)) {
            $message = 'Desculpe, parece que você está tentando editar um perfil que não é seu. Por favor, verifique se você selecionou o perfil correto.';
            
            if (auth()->user()->isCreator() && session('user_role') != 'viewer') {
                return redirect()->route('courses.creator')->with('error', $message);
            }else{
                return redirect()->route('courses.viewer')->with('error', $message);
            }
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Verificação se há intenção de alterar a senha
        if ($request->has('password')) {
            if (!Hash::check($request->input('password'), $user->password)) {
                return redirect()->route('user.edit', $user->email)->withErrors(['password' => 'Senha incorreta.'])->withInput();
            }
            else {
                return redirect()->back()->with('changePassword', 'Digite sua nova senha:');
            }
        }

        // Verifique se a senha deve ser atualizada
        if ($request->has('newPassword')) {
            $validator = $request->validate([
                'newPassword' => 'required|min:6|confirmed',
            ]);

            $user->password = Hash::make($validator['newPassword']);

            $user->save();

            return redirect()->route('user.edit', $user->email)->with('success', 'Senha alterada com sucesso!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'uploadfoto' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->has('role')) {
            $user->role = 'creator';
        }

        if ($request->hasFile('uploadfoto')) {
            // vendo se a imagem não é a deafult
            if ($user->image !== 'images/defaultUser.jpg') {
                // Exclua a imagem antiga do usuário
                $path = str_replace('storage/', '', $user->image);
                Storage::disk('public')->delete($path);
            }

            $imageFile = $request->file('uploadfoto');
            $imageName = md5(uniqid()) . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = 'storage/images/' . $imageName;
            $imageFile->storeAs('public/images', $imageName);

            $user->image = $imagePath;
        }

        $user->save();

        return redirect()->route('user.edit', $user->email)->with('success', 'Dados atualizados com sucesso!');
    }

    public function support()
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin->id;

        $authId = auth()->id();

        // Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($adminId, $authId) {
            $query->where('sender_id', $adminId)
                ->where('receiver_id', $authId);
            })
            ->orWhere(function ($query) use ($adminId, $authId) {
                $query->where('sender_id', $authId)
                    ->where('receiver_id', $adminId);
            })->first();
        
        if ($existingConversation) {
            // Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }

        // Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $adminId,
            'receiver_id' => $authId,
        ]);

        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }

    public function startChat($userId)
    {
        $authenticatedUserId = auth()->id();

        // Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $authenticatedUserId)
                    ->where('receiver_id', $userId);
                })
                ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', $authenticatedUserId);
                })->first();
            
        if ($existingConversation) {
            // Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }
    
        // Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);
 
        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }
}
