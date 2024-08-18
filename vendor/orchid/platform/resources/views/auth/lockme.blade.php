<div class="mb-3 d-flex align-items-center">
    <div class="thumb-sm avatar me-3">
        <img src="{{ $lockUser->presenter()->image() }}" class="b bg-light" alt="{{ $lockUser->presenter()->title() }}">
    </div>
    <div class="d-flex flex-column overflow-hidden small">
        <span class="text-ellipsis">{{ $lockUser->presenter()->title() }}</span>
        <span class="text-muted d-block text-ellipsis">{{ $lockUser->presenter()->subTitle() }}</span>
    </div>
    <input type="hidden" name="email" required value="{{ $lockUser->email }}">
</div>

@error('email')
    <span class="d-block invalid-feedback text-danger">
        {{ $errors->first('email') }}
    </span>
@enderror

<div class="mb-3">
    <input type="hidden" name="remember" value="true">

    {!!  \Orchid\Screen\Fields\Password::make('password')
            ->required()
            ->autocomplete('current-password')
            ->tabindex(1)
            ->autofocus()
            ->placeholder(__('Enter your password'))
    !!}
</div>

<div class="row align-items-center" style="flex-direction: column-reverse !important;display: flex;">
    <div class="col-md-6 col-xs-12"  style="width:100%">
        <a href="{{ route('platform.login.lock') }}" class="small">
            {{__('Sign in with another user.')}}
        </a>
    </div>
    <div class="col-md-6 col-xs-12" style="width:100%">
        <button style="width:100%" id="button-login" type="submit" class="btn btn-md btn-grad btn-grad-alternet btn-round" tabindex="3">
            <x-orchid-icon path="bs.box-arrow-in-right" class="small me-2"/>
            {{__('Login')}}
        </button>
    </div>
</div>
