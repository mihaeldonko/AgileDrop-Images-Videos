@extends('layouts.app')

@section('title', 'Upload Media')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}</h1>
    <div class="container mt-4">
        <h2>Upload Media</h2>
        <form id="upload-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" required maxlength="255">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="media" class="form-label">Media File</label>
                <input type="file" id="media" name="media" class="form-control" accept=".jpg,.jpeg,.png,.mp4,.mkv,.avi" required>
            </div>
            <button type="button" id="upload-button" class="btn btn-primary">Upload</button>
        </form>

        <div id="upload-status" class="mt-4"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadForm = document.getElementById('upload-form');
            const uploadButton = document.getElementById('upload-button');
            const uploadStatus = document.getElementById('upload-status');

            uploadButton.addEventListener('click', function () {
                const formData = new FormData(uploadForm);
                const bearerToken = "{{ Crypt::decryptString(Auth::user()->api_token) }}"; 

                uploadStatus.innerHTML = "Uploading...";

                fetch("{{ url('/api/media') }}", {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${bearerToken}`,
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        uploadStatus.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        uploadForm.reset();
                    } else {
                        uploadStatus.innerHTML = `<div class="alert alert-danger">Error: ${data.error || "Unknown error occurred."}</div>`;
                    }
                })
                .catch(error => {
                    console.error("Upload error:", error);
                    uploadStatus.innerHTML = `<div class="alert alert-danger">An error occurred during upload.</div>`;
                });
            });
        });
    </script>
@endsection
