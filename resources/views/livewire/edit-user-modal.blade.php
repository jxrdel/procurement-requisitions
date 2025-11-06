<!-- Modal -->
<div wire:ignore.self class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editUserModalLabel" style="color: black; text-align:center">Edit User
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="editUser" action="">
                <div class="modal-body" style="color: black">

                    <div class="row">

                        <div class="form-floating form-floating-outline">
                            <input required wire:model.blur="name" type="text" class="form-control"
                                autocomplete="off" id="nameInput" placeholder="John Doe"
                                aria-describedby="nameInputHelp" />
                            <label for="nameInput">Name</label>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="form-floating form-floating-outline">
                            <input required wire:model="username" type="text"
                                class="form-control @error('username')is-invalid @enderror" autocomplete="off"
                                id="usernameInput" placeholder="firstname.lastname"
                                aria-describedby="usernameInputHelp" />
                            <label for="usernameInput">Username</label>
                        </div>
                        @error('username')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="form-floating form-floating-outline">
                            <input required wire:model="email" type="email"
                                class="form-control @error('email')is-invalid @enderror" autocomplete="off"
                                id="emailInput" placeholder="Details" aria-describedby="emailInputHelp" />
                            <label for="emailInput">Email</label>
                        </div>
                        @error('email')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="form-floating form-floating-outline">
                            <select required wire:model="department"
                                class="form-select @error('department')is-invalid @enderror"
                                id="exampleFormControlSelect1" aria-label="Default select example">
                                <option value="">Select a Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            <label for="exampleFormControlSelect1">Department</label>
                        </div>
                        @error('department')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="form-floating form-floating-outline">
                            <select required wire:model="role_id"
                                class="form-select @error('role_id')is-invalid @enderror" id="exampleFormControlSelect1"
                                aria-label="Default select example">
                                <option value="">Select a Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label for="exampleFormControlSelect1">Role</label>
                        </div>
                        @error('role_id')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div x-data="{ is_reporting_officer: @entangle('is_reporting_officer') }">
                        <div class="row mt-4">
                            <div class="form-check form-switch form-check-primary mx-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_reporting_officer"
                                    x-model="is_reporting_officer">
                                <label class="form-check-label" for="is_reporting_officer">Reporting Officer</label>
                            </div>
                        </div>

                        <div class="row mt-4" x-show="is_reporting_officer" x-transition>
                            <div class="form-floating form-floating-outline">
                                <select wire:model="reporting_officer_role"
                                    class="form-select @error('reporting_officer_role')is-invalid @enderror"
                                    id="reporting_officer_role" aria-label="Default select example">
                                    <option value="">Select a Reporting Officer Role</option>
                                    <option value="Permanent Secretary">Permanent Secretary</option>
                                    <option value="Deputy Permanent Secretary">Deputy Permanent Secretary</option>
                                    <option value="Chief Medical Officer">Chief Medical Officer</option>
                                </select>
                                <label for="reporting_officer_role">Reporting Officer Role</label>
                            </div>
                            @error('reporting_officer_role')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">

                        <div class="form-check form-switch mb-2 mx-3">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                wire:model="is_active" />
                            <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                        </div>
                        @error('role_id')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>




                </div>
                <div class="modal-footer" style="align-items: center">
                    <div style="margin:auto">
                        <button class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
