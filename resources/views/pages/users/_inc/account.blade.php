@php
    $name = old("name", $user->name ?? '');
@endphp

<div class="tab-pane fade @if($tab == 'account') show active @endif" id="account" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Basic Info</h5>
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PATCH')

                <x-input
                    type="text"
                    label="Name"
                    name="name"
                    placeholder="Enter your name"
                    :value="$user->name"
                ></x-input>

                <x-input
                    type="text"
                    label="Email"
                    name="email"
                    placeholder="Enter your email"
                    :value="$user->email"

                ></x-input>

                <div class="form-group">
                    <label for="role">{{ 'Role' }}</label>
                    <select id="role" class="form-control select2 @error('role') is-invalid @enderror" name="role" data-toggle="select2">

                        @foreach(\App\Models\User::USER_ROLES as  $key => $value)
                            <option value="{{ $key }}" @if(old('role' ,$user->role ) == $key) selected @endif >{{ $value }}</option>
                        @endforeach

                    </select>
                    @error('role')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>



                <button type="submit" class="btn btn-primary">Update account</button>
            </form>
        </div>
    </div>

</div>

<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
