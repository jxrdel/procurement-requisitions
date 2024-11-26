<div class="card p-7">
    <!-- Logo -->
    <div class="app-brand justify-content-center mt-5">
        <a href="/" class="app-brand-link gap-3">
            <span class="app-brand-logo demo">
                <i class="ri-contract-fill fs-2"></i>
                <span class="app-brand-text demo text-heading fw-semibold fs-4">Procurement Requisition
                    Application</span>
        </a>
    </div>
    <!-- /Logo -->

    <div class="card-body mt-1">
        <form wire:submit.prevent="login">
            <div class="form-floating form-floating-outline mb-5 mt-2">
                <input required wire:model="username" type="text"
                    class="form-control @error('username')is-invalid @enderror" id="email" autocomplete="off"
                    placeholder="firstname.lastname" autofocus />
                <label for="email">Username</label>

                @error('username')
                    <div class="text-danger text-center"> {{ $message }} </div>
                @enderror

            </div>
            <div class="mb-5">
                <div class="form-password-toggle">
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input required wire:model="password" type="password" id="password"
                                class="form-control @error('password')is-invalid @enderror" name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password" />
                            <label for="password">Password</label>
                        </div>
                        <span wire:ignore class="input-group-text cursor-pointer"><i
                                class="ri-eye-off-line ri-20px"></i></span>
                    </div>
                    @error('password')
                        <div class="text-danger text-center"> {{ $message }} </div>
                    @enderror
                </div>
            </div>

            <div class="mb-5">
                <button class="btn btn-primary d-grid w-100 d-flex justify-content-center align-items-center"
                    type="submit">
                    Login
                    <div wire:loading class="spinner-border spinner-border-sm text-white ms-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>

            </div>
        </form>

    </div>
</div>
