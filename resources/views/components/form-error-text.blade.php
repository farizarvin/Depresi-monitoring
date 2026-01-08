@props([
    'field'=>''
])
<div>
    @if($errors->has($field))
        <small class="text-danger">{{ $errors->first($field) }}</small> 
    @endif
</div>