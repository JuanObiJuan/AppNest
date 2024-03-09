<div>
    @foreach($fields as $fieldName => $fieldInfo)
        @php
            $fieldType = $fieldInfo['type'];
            $fieldValues = $fieldInfo['values'];
            $uiSettings = $fieldInfo['ui'] ?? [];
        @endphp

        @foreach($fieldValues as $lang => $value)
            @php
                $componentName = '';
                $extraAttributes = [];
                {{ logger($fieldType); }}

                switch ($fieldType) {
                    case 'string':
                        $componentName = 'forms.components.text-input';
                        $extraAttributes['placeholder'] = $uiSettings[$lang]['ui:placeholder'] ?? '';
                        break;
                    case 'boolean':
                        $componentName = 'forms.components.checkbox';
                        $extraAttributes['label'] = $uiSettings[$lang]['ui:label'] ?? '';
                        break;
                }
                $inputName = "{$fieldName}[{$lang}]";
            @endphp

            <x-dynamic-component :component="$componentName"
                                 :attributes="$extraAttributes"
                                 name="{{ $inputName }}"
                                 :label="__('fields.'.$fieldName.'.'.$lang)"
                                 :value="$value" />
        @endforeach
    @endforeach
</div>
