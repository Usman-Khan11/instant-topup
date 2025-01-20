@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form method="post" action="{{ route('admin.user.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <div class="card">
                <div class="card-header">
                    <h4 class="fw-bold">{{ $page_title }}</h4>
                    <hr />
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Name*</label>
                                <input name="name" type="text" class="form-control"
                                    value="{{ old('name', $user->name) }}" placeholder="Name*" />
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" placeholder="Email" />
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input name="username" type="text" class="form-control"
                                    value="{{ old('username', $user->username) }}" placeholder="Username" />
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" value="{{ old('password') }}"
                                    placeholder="Password" />
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Balance</label>
                                <input name="balance" step="any" type="number" class="form-control"
                                    value="{{ old('balance', $user->balance) }}" placeholder="Balance" />
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Avatar</label>
                                <input name="avatar" type="file" class="form-control" />
                            </div>
                        </div>

                        <div class="col-md-12 col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
