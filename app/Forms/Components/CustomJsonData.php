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
        return array_merge(parent::getViewData(), [
            'schema' => $this->json_schema,
            'uiSchema' => $this->json_ui_Schema,
            //convert the json_data into a structure suitable for the form fields
            'formData' => $this->getState() ? json_decode($this->getState(), true) : [],
        ]);
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
