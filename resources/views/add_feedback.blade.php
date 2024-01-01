<x-app-layout>
    <style>

        .custom-form {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Adjust the shadow as needed */
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                @if(session("error"))
                    <div class="alert alert-danger">{{session('error')}}</div>
                @endif
                <!-- Form -->
                <form class="custom-form" method="post" action="{{route('feedback_submit')}}">
                    @csrf
                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                        <input value="{{old('title')}}" type="text" name="title" class="form-control" id="title"
                               placeholder="Enter title">
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                        <textarea name="description" class="form-control" id="description" rows="3"
                                  placeholder="Enter description">{{old('description')}}</textarea>
                    </div>

                    <!-- Category Field -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category:</label>
                        <x-input-error :messages="$errors->get('category')" class="mt-2"/>
                        <select name="category" class="form-control">
                            <option value="" selected="" disabled="">Select Category</option>
                            <option value="Bug Report"> Bug Report</option>
                            <option value="Feature Request"> Feature Request</option>
                            <option value="Improvement">Improvement</option>

                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="background: #1976D2">Submit</button>
                </form>

            </div>
        </div>
    </div>


</x-app-layout>

