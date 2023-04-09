@extends('layouts.app')

@section('title', __('Add User'))
@section('scripts')
{{--    <script>--}}

{{--        var asdas ='{{ json_encode($users) }}';--}}
{{--        $.each(asdas, function(index, item) {--}}
{{--            console.log(index);--}}
{{--            // do something with `item` (or `this` is also `item` if you like)--}}
{{--        });--}}

{{--    </script>--}}
@endsection
@section('content')



    <h1 class="h3 mb-3">{{ __('Add User') }}</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('users.store') }}">
                        @csrf

                        <x-input type="text" name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter your name') }}"></x-input>
                        <x-input type="email" name="email" label="{{ __('Email') }}" placeholder="{{ __('Enter your email') }}"></x-input>
                        <x-input type="password" name="password" label="{{ __('Password') }}" placeholder="{{ __('Enter your password') }}"></x-input>
                        <x-input type="password" label="{{ __('Confirm Password') }}" name="password_confirmation" placeholder="{{ __('Enter your password again') }}"></x-input>

                        <div class="form-group">
                            <label for="role">{{ __('Role') }}</label>
                            <select id="role" class="form-control select2 @error('role') is-invalid @enderror" name="role" data-toggle="select2">
                               @foreach(\App\Models\User::USER_ROLES as  $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary">{{ __('Add User') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

