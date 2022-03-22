@foreach($houses as $house)
<div>
    <h1>{{ $house->name }}</h1>
    <p>{{ $house->coatOfArms }}</p>
</div>
@endforeach
