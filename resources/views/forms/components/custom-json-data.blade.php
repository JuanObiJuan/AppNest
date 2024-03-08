<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        Hello

    </div>

    {{$getJsonSchema()}}
    <br><br>
    {{$getJsonUiSchema()}}

    <script type="text/javascript">
        var laravelVariable = @json($state);
        console.log('Laravel Variable:', laravelVariable);
    </script>
</x-dynamic-component>
