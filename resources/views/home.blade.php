@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">Blog Post</div>
                    <div class="card-body">
                        <button id="modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Add Blogs</button>
                        <hr>
                        <table class="table display" id="table_id">
                            <thead>
                            <tr style="background: #006aa6;color: #fff;">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data) && !empty($data))
                                @php
                                    $count = 1;
                                @endphp
                                @foreach($data as $blog)
                                    <tr>
                                        <th>{{ $count++ }}</th>
                                        <td>{{ $blog->title??'' }}</td>
                                        <td>{{ $blog->description??'' }}</td>
                                        <td>
                                            @if($blog->status == 1)
                                                <span class="label label-success">Active</span>
                                            @elseif($blog->status == 0)
                                                <span class="label label-success">In-Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" id="edit" data-toggle="modal" data-target='#exampleModalCenter'
                                                    data-id="{{ $blog->id }}">Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" id="delete" data-id="{{ $blog->id }}">Delete</button>
                                            <a href="{{ route('view', $blog->id) }}" class="btn btn-success btn-sm" id="view" data-id="{{ $blog->id }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>

                </div>


            </div>

            {{--        ADD To-do Model--}}
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="blogData">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add Blog Post</h5>
                                <button type="button" id="close" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <input type="hidden" id="id" name="id" value="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Blog Title</label>
                                            <input class="form-control alphabet" type="text" name="title" id="title" placeholder="Please enter Blog Title" required>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Blog Description</label>
                                            <input class="form-control alphabet" type="text" name="description" id="description" placeholder="Please enter Blog Description" required>
                                        </div>
                                    </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="add" class="btn btn-primary">Add Blog</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });

        $('#modal').click(function(event) {
            event.preventDefault();
            $('#add').val("Add Blog");
            $('#exampleModalCenter').modal('show');
            $('#title').val('');
        });

        $('#close').click(function() {
            $('#exampleModalCenter').modal('hide');
        });

        $('#add').on('click', function() {

            var id = $("#id").val();
            var name = $('#title').val();
            var description = $('#description').val();

            if (!name) {
                toastr.error("Please enter the Blog Title!");
                return false;
            }

            if (!description) {
                toastr.error("Please enter the Blog Description!");
                return false;
            }
            console.log(id);
            if (id == '' || id == null || id == undefined) {
                ajaxRequestAsync("{{route('addBlog')}}", {name: name, description: description}, function(data) {

                    if (data.status == 1) {
                        $('#blogData').trigger("reset");
                        $('#exampleModalCenter').modal('hide');
                        window.location.reload(true);
                    }

                }, [], 'POST');

            } else {

                ajaxRequestAsync('blog/' + id + '/update', {id: id, name: name, description: description}, function(data) {

                    if (data.status == 1) {
                        $('#blogData').trigger("reset");
                        $('#exampleModalCenter').modal('hide');
                        window.location.reload(true);
                    }

                }, [], 'POST');
            }

        });

        $('body').on('click','#edit', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            $.get('blog-edit/' + id, function(data) {
                $('#add').html("Edit Blog");
                $('#exampleModalCenter').modal('show');
                $('#id').val(data.result.id);
                $('#title').val(data.result.title);
                $('#description').val(data.result.description);
            })
        });

        {{--$('body').on('click', '#view', function(event) {--}}

        {{--    var id = $(this).attr('data-id');--}}
        {{--    ajaxRequestAsync("{{ route('status') }}", {id: id}, function(data) {--}}

        {{--        if (data.status == 1) {--}}
        {{--            $('#blogData').trigger("reset");--}}
        {{--            window.location.reload(true);--}}
        {{--        }--}}

        {{--    }, [], 'POST');--}}
        {{--});--}}

        $('body').on('click', '#delete', function(event) {

            var id = $(this).attr('data-id');
            ajaxRequestAsync("{{ route('delete') }}", {id: id}, function(data) {

                if (data.status == 1) {
                    $('#blogData').trigger("reset");
                    window.location.reload(true);
                }

            }, [], 'POST');
        });

        function ajaxRequestAsync(link, postData, callBack, params, method) {

            var csrfToken = document.getElementsByName('_token');
            csrfToken = csrfToken[0].value;

            if (method == undefined) {
                method = 'post';
            }

            $.ajax({
                       url: link,
                       data: postData,
                       type: method,
                       cache: false,
                       async: true,
                       dataType: 'json',
                       headers: {
                           'X-CSRF-TOKEN': csrfToken
                       },
                       success: function(result, status, xhr) {

                           if (callBack != undefined && callBack != null) {
                               callBack(result, params);
                           } else {
                               console.log("Call Back Function Is Not Defined.");
                               return false;
                           }
                       },
                       error: function(xhr, status, error) {

                           if (callBack != undefined && callBack != null) {
                               callBack(xhr.responseJSON, params);
                           }

                           return false;
                       }
                   });
        }
    </script>
@endsection
