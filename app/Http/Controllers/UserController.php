<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = "Users";

        if ($request->ajax()) {
            $query = User::Query();
            $query = $query->orderBy('id', 'desc')->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }

        return view('admin.user.index', $data);
    }

    public function create()
    {
        $data['page_title'] = "Add New User";
        return view('admin.user.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit User";
        $data['user'] = User::where("id", $id)->first();
        return view('admin.user.edit', $data);
    }

    public function view($id)
    {
        $data['page_title'] = "View User";
        $data['user'] = User::where("id", $id)->first();
        return view('admin.user.view', $data);
    }

    public function delete($id)
    {
        User::where("id", $id)->delete();
        return back()->withSuccess('User deleted successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|string|max:150|unique:users',
            'password' => 'required|string|min:6|max:25',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = 'assets/uploads/users_avatar/';
            $path = $file->move($directory, $filename);
            $user->avatar = $path;
        }

        if ($user->save()) {
            return redirect()->route('admin.user')->withSuccess('User added successfully.');
        }

        return back()->withError('Something went wrong.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request->id)],
            'username' => ['required', 'string', 'max:150', Rule::unique('users')->ignore($request->id)],
            'password' => 'nullable|string|min:6|max:25',
            'balance' => 'required|numeric|between:0,999999.99',
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->balance = $request->balance;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = 'assets/uploads/users_avatar/';
            $path = $file->move($directory, $filename);
            $user->avatar = $path;
        }

        if ($user->save()) {
            return redirect()->route('admin.user')->withSuccess('User added successfully.');
        }

        return back()->withError('Something went wrong.');
    }
}
