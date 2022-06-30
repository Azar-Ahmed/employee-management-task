@extends('include.layout')
@section('page_title', 'Dashboard')
@push('custom_styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">   
@endpush

@section('container')
    <div class="container">
        <div class="col-md-12 my-5">
            <div class="d-flex">
                <div class="p-2 flex-grow-1">
                    <h3>Employee's</h3>
                </div>
                <div class="p-2">
                    <?php $slug = 'add'; ?>
                    <a href="{{ url('employee-form/' . $slug) }}" class="btn btn-outline-primary">Add Employee</a>
                </div>
            </div>
            <div class="my-3">
                @if (isset($data[0]))
                    <table class="table table-borderless display" id="table_id">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>User Name</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($data as $item)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                    <td>
                                        @if ($item->image != '')
                                            <img src="{{ asset('assets/images/employee/' . $item->image) }}"
                                                alt="image" style="width: 100px">
                                        @else
                                            <img src="{{ asset('assets/images/employee/default_img.png') }}"
                                                alt="image" style="width: 100px">
                                        @endif
                                    </td>
                                    <td> 
                                        {{-- Status --}}
                                        @if ($item->status == 1)
                                            <a href="{{ url('employee-status/deactive/' . $item->email) }}"
                                                class="btn btn-outline-success">Active</a>
                                        @elseif ($item->status == 0)
                                            <a href="{{ url('employee-status/active/' . $item->email) }}"
                                                class="btn btn-outline-danger">Deactive</a>
                                        @endif
                                        {{-- View --}}
                                        <button type="button" class="btn btn-outline-info view_emp"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            value="{{ $item->id }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        {{-- Edit --}}
                                        <a href="{{ url('employee-form/' . $item->email) }}"
                                            class="btn btn-outline-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                        {{-- Delete --}}
                                        <button type="button" value="{{ $item->id }}"
                                            class="delete_emp btn btn-outline-danger"><i
                                                class="fa-solid fa-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                <div class="text-center">
                    <a href="{{ url('employee-form/' . $slug) }}">
                        <img src="{{ asset('assets/images/add-emp.svg') }}"  data-bs-toggle="tooltip" title="Add Employee" alt="image" style="width: 40%">
                    </a>
                </div>
                @endif
            </div>

        </div>
    </div>
    <!-- Employee Detail Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Employee Details</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="employeeDetails"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    @if (session('success_msg') > 0)
        <script>
            swal({
                title: `{{ session('success_msg') }}`,
                icon: "success",
            });
        </script>
    @elseif (session('error_msg') > 0)
        <script>
            swal({
                title: `{{ session('error_msg') }}`,
                icon: "warning",
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
            $(document).on('click', '.delete_emp', function(e) {
                e.preventDefault();
                var id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                swal({
                        title: "Are you sure?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: `{{ url('employee-delete/${id}') }}`,
                                dataType: "JSON",
                                success: function(response) {
                                    swal(`${response.message}`, {
                                            icon: "success",
                                        })
                                        .then((value) => {
                                            location.reload();
                                        });
                                }
                            });
                        } else {
                            swal("Employee Not Deleted!");
                        }
                    });
            });

             // View Product Details 
             $(document).on('click', '.view_emp', function(e) {
                e.preventDefault();
                var emp_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "get",
                    url: `{{ url('employee-view/${emp_id}') }}`,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response.data);
                        if(response.data.status > 0){
                            var status = `<i class="fa-solid fa-circle-check fa-xs text-success"></i>`;
                        }else{
                            var status = `<i class="fa-solid fa-circle-xmark fa-xs text-danger"></i>`;
                        }

                        $('.employeeDetails').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ asset('assets/images/employee/${response.data.image}') }}" alt="" style="width:300px">
                            </div>

                            <div class="col-md-6">
                                <h3>${response.data.first_name} ${response.data.last_name} <small>${status}</small> </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted m-0">Email <p class="fw-semibold m-0">${response.data.email}</p></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted m-0">Phone No <p class="fw-semibold m-0">${response.data.phone}</p></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted m-0">Gender <p class="fw-semibold m-0">${response.data.gender}</p></p>
                                    </div>
                                </div>
                            </div>
                         </div>
                        `);
                    }
                });

            });

        });
    </script>
@endsection
