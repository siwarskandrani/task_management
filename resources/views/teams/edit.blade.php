@extends('layouts.app')

@section('edit_team')
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
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $item)
            <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container p-5">
        <form action="{{ route('teams.update', $team->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    name="name"
                    id="name"
                    value="{{ old('name', $team->name) }}"
                />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    class="form-control"
                    rows="3"
                >{{ old('description', $team->description) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="emails_member">Update Members (emails)</label>
                <select id="emails_member" name="emails_member" multiple class="form-select">
                    @foreach($members as $member)
                        <option value="{{ $member->email }}" {{ in_array($member->email, old('emails_member', $members->pluck('email')->toArray())) ? 'selected' : '' }}>
                            {{ $member->email }}
                        </option>
                    @endforeach
                </select>
                @error('emails_member')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            
            <button type="submit" class="btn btn-success">Update</button>
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
