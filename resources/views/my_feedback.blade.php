<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Feedback') }}
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{route('feedback')}}" class="btn btn-primary"
                   style="background: #1976D2">{{ __('Add Feedback') }}</a>

            </h2>
        </div>
    </x-slot>

    <style>


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
            @forelse($feeback as $row)
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



                        </div>
                    </div>
                    <div class="mt-3">
                        {{ $feeback->links() }}
                    </div>
                    <!-- Add more posts as needed -->

                </div>
            @empty
            @endforelse
        </div>
    </div>

</x-app-layout>
