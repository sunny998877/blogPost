@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">{{ $data->title }}</div>
                    <div class="card-body">
                        @if(isset($data) && !empty($data))
                            {{ $data->description }}
                        @endif
                    </div>
                    <button id="modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Add Comment</button>
                </div>

                @if(isset($comment) && !empty($comment))
                    @php
                        $count = 1;
                    @endphp
                    <div class="card">
                        <div class="card-body" id="table_id">
                            @foreach($comment as $com)
                               {{ $count++ }} {{ $com->comment??'' }}<br>
                            @endforeach
                        </div>
                    </div>

                @endif
            </div>
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="blogData">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add Comment</h5>
                            <button type="button" id="close" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <input type="hidden" id="blog_id" name="id" value="{{$data->id}}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Comment</label>
                                        <input class="form-control alphabet" type="text" name="comment" id="commentTitle" placeholder="Please Add Blog Comment" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="comment" class="btn btn-primary">Comment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        $('#modal').click(function(event) {
            event.preventDefault();
            $('#add').val("Add Blog");
            $('#exampleModalCenter').modal('show');
            $('#title').val('');
        });

        $('#close').click(function() {
            $('#exampleModalCenter').modal('hide');
        });

        $('#comment').on('click', function() {

            var id = $("#blog_id").val();
            var comment = $('#commentTitle').val();

            if (!comment) {
                toastr.error("Please enter the comment!");
                return false;
            }

            ajaxRequestAsync("{{route('addComment')}}", {comment: comment, blog_id : id}, function(data) {

                if (data.status == 1) {
                    $('#blogData').trigger("reset");
                    $('#exampleModalCenter').modal('hide');
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
