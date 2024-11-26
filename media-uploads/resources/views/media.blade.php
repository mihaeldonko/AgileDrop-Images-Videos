@extends('layouts.app')

@section('content')
    <h2>Welcome, to the all media files that have been uploaded here</h2>
    <h5>Total media count: {{count($mediaFiles)}}</h5>
    <hr />
    <div class="container">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($mediaFiles as $media)
                <div class="col">
                    <div class="card h-100">
                        @if (in_array($media['file_type'], ['jpg', 'jpeg', 'png']))
                            <img src="{{ asset('storage/' . $media['file_path']) }}" class="card-img-top" alt="{{ $media['title'] }}">
                        @elseif (in_array($media['file_type'], ['mp4', 'mkv', 'avi']))
                            <video class="card-img-top" controls>
                                <source src="{{ asset('storage/' . $media['file_path']) }}" type="video/{{ $media['file_type'] }}">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="card-img-top text-center bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <p>Unsupported File</p>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $media['title'] }}</h5>
                            <p class="card-text">{{ $media['description'] }}</p>
                            <a href="{{ asset('storage/' . $media['file_path']) }}" class="btn btn-primary">Open file in new tab</a>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Uploaded: {{ \Carbon\Carbon::parse($media['created_at'])->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
