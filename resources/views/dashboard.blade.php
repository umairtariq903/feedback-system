<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Feedback') }}
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{route('feedback')}}" class="btn btn-primary"
                   style="background: #1976D2">{{ __('Add Feedback') }}</a>
            </h2>
        </div>
    </x-slot>

    <style>
        /* Add this to your existing styles or create a new style section */
        .mention-suggestion {
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .mention-suggestion:hover {
            background-color: #f0f0f0;
        }

        #mention-list {
            position: absolute;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
            z-index: 1000;
        }

        .post {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .post-content {
            margin-bottom: 15px;
        }

        .like-btn {
            color: #1976D2;
        }

        .comment-section {
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>

    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif
        <div class="row">
            @forelse($feedback as $row)
                <div class="col-lg-8 mx-auto">
                    <div class="post">
                        <!-- Post Header -->
                        <div class="post-header">
                            <img
                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTFKeWgu-Ubv4eEJnRunfWK09E-V9yyIgsXrvEQvaGdgw&s"
                                alt="User Avatar" class="avatar">
                            <div>
                                <h6 class="mb-0">{{$row->users->name}}</h6>
                                <p class="text-muted mb-0">
                                    @php
                                        $dateString =$row->created_at;
                                        $carbonDate = \Carbon\Carbon::parse($dateString);
                                        $formattedDate = $carbonDate->format('F j, Y');
                                    @endphp
                                    {{ $formattedDate }}
                                </p>
                            </div>
                        </div>
                        <h2 class="mb-3">{{$row->title}}</h2>
                        <!-- Post Content -->
                        <div class="post-content">
                            {{$row->description}}
                        </div>
                        <!-- Like Button -->
                        <p id="like-counter" class="text-muted mb-2">{{$row->vote}}
                            @if(Auth::check() && !empty($row->votes) && $row->votes->firstWhere('user', Auth::user()->id))
                                <a href="{{route('unvote',["id"=>$row->id])}}" class="btn btn-link like-btn">Unvote</a>
                            @else
                                <a href="{{route('vote',["id"=>$row->id])}}" class="btn btn-link like-btn">Vote</a>
                            @endif
                        </p>
                        <!-- Comment Section -->
                        <div class="comment-section">
                            <!-- Comments -->
                            <!-- Add more comments as needed -->
                            <!-- Comment Form -->
                            <form class="mt-2" method="post" action="{{route('comment_add',["id"=>$row->id])}}">
                                <div class="my-2">
                                    <x-input-error :messages="$errors->get('comment')" class="mt-2"/>
                                </div>
                                <div class="input-group">
                                    @csrf
                                    <input id="selected-mentions_id-{{$row->id}}" type="hidden" name="mention"
                                           class="form-control  col-lg-1 " readonly>
                                    <input id="selected-mention-{{$row->id}}" type="text" name=""
                                           class="form-control  col-lg-1 " readonly>
                                    <input data-id="{{$row->id}}" id="comment-{{$row->id}}" type="text" name="comment"
                                           class="form-control comment_request" placeholder="Write a comment...">

                                    <button type="submit" class="btn btn-primary" style="background: #1976D2">Comment
                                    </button>
                                </div>
                                <div class="mention-suggestion" data-id="{{$row->id}}"
                                     id="mention-list-{{$row->id}}"></div>
                            </form>
                        </div>
                    </div>
                    <!-- Add more posts as needed -->
                </div>

            @empty
            @endforelse
            <div class="mt-3 col-lg-8 mx-auto">
                {{ $feedback->links() }}
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function () {
            var selectedMention = "";

            $('.comment_request').on('input', function () {
                var postId = $(this).data('id');
                var commentInput = $('#comment-' + postId);
                var selectedMentionInput = $('#selected-mention-' + postId);

                var mentionList = $('#mention-list-' + postId);

                var commentText = commentInput.val();

                if (commentText.includes('@')) {
                    $.ajax({
                        url: "{{ route('getMentions') }}",
                        method: 'GET',
                        data: {term: commentText},
                        dataType: 'json',
                        success: function (users) {
                            mentionList.empty();
                            users.forEach(function (user) {
                                mentionList.append('<div class="assign_user my-1" data-id="' + user.id + '" data-mention="' + user.name + '">' + user.name + '</div>');
                            });
                            mentionList.show();
                        },
                        error: function (error) {
                            console.error(error);
                        }
                    });
                } else {
                    mentionList.hide();
                }
            });

            // Handle mention selection
            $(document).on('click', '.assign_user', function () {
                var postId = $(this).closest('.comment-section').find('.comment_request').data('id');

                var commentInput = $('#comment-' + postId);
                var selectedMentionInput = $('#selected-mention-' + postId);
                var selectedMentionId = $('#selected-mentions_id-' + postId);
                var mentionList = $('#mention-list-' + postId);

                // Clear the input and hide the mention list
                commentInput.val('');
                mentionList.hide();

                // Append the selected mention to the cleared input
                selectedMention = $(this).data('mention');
                let selectedId = $(this).data('id');


                // Update the selected mention input
                selectedMentionId.val(selectedId);
                selectedMentionInput.val(selectedMention);
            });
        });
    </script>

</x-app-layout>
