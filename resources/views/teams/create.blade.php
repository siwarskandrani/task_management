@extends('layouts.app')

@section('create_team')
<div class="container p-5">
    <br>
    <div class="row">
        <div class="col align-self-start">
            <a class="btn btn-primary" href="{{ route('teams.index') }}">All teams</a>
        </div>
    </div>
    <br>

    {{-- Affichage du message de succès --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container p-5">
        <form action="{{ route('teams.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    name="name"
                    id="name"
                    value="{{ old('name') }}" 
                />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    class="form-control"
                    rows="3"
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="emails_member">Invite Members</label>
                <input
                    type="text"
                    name="emails_member"
                    id="emails_member"
                    value="{{ old('emails_member') }}" 
                />
                @error('emails_member')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    $('#emails_member').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: false,
        create: function(input) {
            // Vérifier si l'email est valide
            if (validateEmail(input)) {
                return { value: input, text: input };
            } else {
                alert('Veuillez entrer une adresse e-mail valide.');
                return false;
            }
        },
    });

    // Fonction pour valider une adresse e-mail
    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>
@endsection
