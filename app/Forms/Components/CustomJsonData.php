<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class CustomJsonData extends Field
{
    protected string $view = 'forms.components.custom-json-data';

    protected array $json_schema = [];
    protected array $json_ui_schema = [];

    public function jsonSchema(array $json_schema): static
    {
        $this->json_schema = $json_schema;
        return $this;
    }
    public function jsonUiSchema(array $json_ui_schema): static
    {
        $this->json_ui_schema = $json_ui_schema;
        return $this;
    }

    public function getJsonSchema():string
    {
        return json_encode($this->json_schema);
    }
    public function getJsonUiSchema():string
    {
        return json_encode($this->json_ui_schema);
    }

    // Override the method that provides the data for the view
    protected function getViewData(): array
    {
        $json_data = $this->getState();

        $json_data_array = json_decode($json_data, true);
        $json_schema_array = $this->json_schema;
        $json_ui_schema_array = $this->json_ui_schema;

        //TODO previous validation

        $transformedData = [];

        // Loop through the json_schema_array
        foreach ($json_schema_array['properties'] as $key => $value) {
            if (isset($json_data_array[$key])) {
                $fieldData = [
                    'type' => $value['type'], // Get the type of field (object, string, boolean, etc.)
                    'values' => $json_data_array[$key], // Get the actual values from the json_data
                ];

                // If there's a UI schema for this field, add it to the fieldData array.
                if (isset($json_ui_schema_array[$key])) {
                    foreach ($json_ui_schema_array[$key] as $lang => $uiProps) {
                        // Here, we're assuming that UI schema might contain placeholders, labels, etc., which we add to our array.
                        // This is simplistic; depending on complexity, you might need a more robust merging strategy.
                        if (!isset($fieldData['ui'])) {
                            $fieldData['ui'] = [];
                        }
                        $fieldData['ui'][$lang] = $uiProps;
                    }
                }
                // Add the fieldData array
                $transformedData[$key] = $fieldData;
            }
        }

        return [
            'fields' => $transformedData,
        ];
    }
    public function render(): \Illuminate\Contracts\View\View
    {

        $viewData = $this->getViewData(); // Assuming this is your existing method to prepare fields

        return view('forms.components.custom-json-data', $viewData);

    }
    protected function save(): void
    {
        $value = $this->getState();
        ///ddd("yeah");
        /*
        // Perform validation or other custom logic here
        // Example validation (make sure to replace with actual schema validation)
        $validator = Validator::make($value ? json_decode($value, true) : [], [
            // Define validation rules based on your json_schema
        ]);

        if ($validator->fails()) {
            // Handle validation failure
        }
        */
        // Proceed with saving or manipulating the value as needed

    }
}
