@extends('layouts.app')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}</h1>
    <hr />
    <div class="container">
        <h2>API Settings</h2>
        <div class="form-group">
            <label for="api-key">Current API Key</label>
            <div class="input-group">
                <input 
                    type="text" 
                    name="api-key" 
                    id="api-key" 
                    class="form-control blurred" 
                    value="{{ $apiToken }}" 
                    disabled>
                <button 
                    id="toggle-blur" 
                    class="btn btn-secondary" 
                    type="button">Show</button>
            </div>
        </div>
        <button id="regenerate-api-key" class="btn btn-primary mt-3">Regenerate the Key</button>
    </div>

    <style>
        .blurred {
            filter: blur(8px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiKeyInput = document.getElementById('api-key');
            const toggleBlurButton = document.getElementById('toggle-blur');
            const regenerateButton = document.getElementById('regenerate-api-key');

            regenerateButton.addEventListener('click', function () {
                fetch('{{ route('regenerate.api.key') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.api_key) {
                        apiKeyInput.value = data.api_key;
                        apiKeyInput.classList.add('blurred');
                        alert('API Key regenerated successfully!');
                    } else {
                        alert('Failed to regenerate API key.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while regenerating the API key.');
                });
            });


            toggleBlurButton.addEventListener('click', function () {
                if (apiKeyInput.classList.contains('blurred')) {
                    apiKeyInput.classList.remove('blurred');
                    toggleBlurButton.textContent = 'Hide';
                } else {
                    apiKeyInput.classList.add('blurred');
                    toggleBlurButton.textContent = 'Show';
                }
            });
        });
    </script>
@endsection
