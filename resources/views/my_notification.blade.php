<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{route('feedback')}}" class="btn btn-primary"
                   style="background: #1976D2">{{ __('Add Feedback') }}</a>

            </h2>
        </div>
    </x-slot>

    <style>
        /* Custom styles for the notification card */
        .notification-card {
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

        .post-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .post-date {
            color: #777;
        }
    </style>
    <div class="container mt-5">
        <div class="row mx-auto">

            @forelse($notification as $row)
            <div class="col-lg-7 mx-auto">
                <!-- Example Notification Card 1 -->
                <div class="notification-card">
                    <a href="{{route("view_feedback",[$row->feedbacks->id])}}">

                    <div class="post-header">
                        <img src="https://via.placeholder.com/40" alt="User Avatar" class="avatar">
                        <div>
                            <h6 class="mb-0 d-line">{{$row->users->name}} </h6><span class="d-line">mentioned you in a comment</span>
                            <p class="text-muted mb-0">
                                @php
                                    $dateString = $row->created_at;
                                    $carbonDate = \Carbon\Carbon::parse($dateString);
                                    $formattedDateTime = $carbonDate->format('F j, Y h:i A');
                                @endphp
                                {{ $formattedDateTime }}
                            </p>
                        </div>
                    </div>
                    <h2 class="post-title">{{$row->feedbacks->title}}</h2>

                    </a>
                </div>

                <!-- End Example Notification Card 2 -->

                <!-- Add more notification cards as needed -->

            </div>
            @empty
                <div class="col-lg-6 mx-auto">
                    <!-- Example Notification Card 1 -->
                    <div class="notification-card">

                        <h2 class="post-title">Not Found</h2>

                    </div>
            @endforelse
        </div>
    </div>

</x-app-layout>
