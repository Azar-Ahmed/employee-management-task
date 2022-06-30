@extends('include.layout')
@section('page_title', 'Dashboard')

@section('container')
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-5">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <h3 class="mb-0 fw-bold">Add Employee</h3>
                    </div>
                    <div class="p-2 bd-highlight">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary">View Employee</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="post" class="emp_form" enctype="multipart/form-data"
                            action="{{ route('employee.save') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="first_name" value="{{ $first_name }}"
                                            class="form-control" placeholder="First Name">
                                        <label for="floatingInput">First Name</label>
                                        <span class="text-danger">
                                            @error('first_name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="last_name" value="{{ $last_name }}"
                                            class="form-control last_name" placeholder="Last Name">
                                        <label for="floatingInput">Last Name</label>
                                        <span class="text-danger">
                                            @error('last_name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" value="{{ $email }}"
                                            class="form-control" placeholder="Email address">
                                        <label for="floatingInput">Email address</label>
                                        <span class="text-danger">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="phone" value="{{ $phone }}"
                                            class="form-control" placeholder="Mobile Number">
                                        <label for="floatingInput">Mobile Number</label>
                                        <span class="text-danger">
                                            @error('phone')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" name="gender" value="{{ $gender }}">
                                            <option value="" selected>Select Gender</option>
                                            @if ($gender == 'Male')
                                                <option value="Male" selected>Male</option>
                                                <option value="Female">Female</option>
                                            @elseif($gender == 'Female')
                                                <option value="Male">Male</option>
                                                <option value="Female" selected>Female</option>
                                            @else
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            @endif
                                        </select>
                                        <label for="floatingSelect">Gender</label>
                                        <span class="text-danger">
                                            @error('gender')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if ($image != '')
                                        <input type="file" class="form-control" name="image"
                                            value="{{ $image }}" placeholder="Upload image">
                                        <img src="{{ asset('assets/images/employee/' . $image) }}" alt="employee image"
                                            style="width: 150px" class="m-2">
                                    @else
                                        <input type="file" class="form-control" name="image" placeholder="Upload image"
                                            required>
                                    @endif
                                    <span class="text-danger">
                                        @error('image')
                                            {{ $image }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            @if ($id > 0)
                                <button class="btn btn-success submit my-4">Update Employee</button>
                            @else
                                <button class="btn btn-primary submit my-4">Add Employee</button>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".submit", function() {
            $.validator.addMethod(
                "lettersonly",
                function(value, element) {
                    return this.optional(element) || /^[a-z\s]+$/i.test(value);
                },
                "Only alphabetical characters"
            );
            $(`.emp_form`).validate({
                rules: {
                    first_name: {
                        required: true,
                        lettersonly: true,
                    },
                    last_name: {
                        required: true,
                        lettersonly: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                    },
                    gender: {
                        required: true,
                    },
                },
                messages: {
                    first_name: {
                        required: "Please Enter First Name",
                        lettersonly: "Only Letters Allowed",
                    },
                    last_name: {
                        required: "Please Enter Last Name",
                        lettersonly: "Only Letters Allowed",
                    },
                    email: {
                        required: "Please Enter Email Address",
                        email: 'Please Enter Valid Email Address',
                    },
                    phone: {
                        required: 'Please Enter Mobile Number',
                        number: 'Please Enter Only Digits',
                        minlength: "Please Enter 10 Digits Mobile No",
                        maxlength: "Please Enter Valid Mobile",
                    },
                    gender: {
                        required: "Please Select Gender",
                    },
                },
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    error.insertAfter(element);
                },
            });
        });
        });
    </script>
@endsection
