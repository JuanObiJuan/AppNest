<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Application;
use Creagia\FilamentCodeField\CodeField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;
    protected static ?string $navigationIcon = 'heroicon-o-window';
    protected static ?string $navigationLabel = 'Application';
    protected static ?string $modelLabel = 'Application';
    protected static ?string $navigationGroup = 'Application Management';
    protected static ?int $navigationSort = 1;

    public static function GetDynamicFields($jsonData, $jsonSchema, $jsonUiSchema):array {
        $fields = [];

        // Iterate over schema properties to create form fields
        foreach ($jsonSchema['properties'] as $propertyKey => $propertyValue) {
            $fieldType = $propertyValue['type'];
            $uiSchema = $jsonUiSchema[$propertyKey] ?? null;

            // Based on the type of field, create appropriate Filament form field
            if ($fieldType === 'object' && $uiSchema) {
                foreach ($propertyValue['properties'] as $langKey => $langValue) {
                    $field=null;
                    $uiOptions = $uiSchema['ui:options'][$langKey] ?? [];
                    $label = $uiOptions['label'] ?? ucfirst($langKey);
                    $componentType = $uiSchema['ui:component'];

                    // Create field based on component type
                    switch ($componentType) {
                        case 'Textarea':
                            $field = Forms\Components\Textarea::make("json_data.".$propertyKey.".".$langKey)
                                ->label($label)
                                ->placeholder($uiOptions['placeholder'] ?? '')
                                ->rows($uiOptions['rows'] ?? 2)
                                ->default($jsonData[$propertyKey][$langKey] ?? '');
                            break;

                        case 'Toggle':
                            $field = Forms\Components\Toggle::make("json_data.".$propertyKey.".".$langKey)
                                ->label($label)
                                ->default($jsonData[$propertyKey][$langKey] ?? false);
                            break;

                        default:
                            $field = Forms\Components\TextInput::make("json_data.".$propertyKey.".".$langKey)
                                ->label($label)
                                ->default($jsonData[$propertyKey][$langKey] ?? '');
                            break;
                    }

                    $fields[] = $field;
                }
            }
        }
        $newField = CodeEditor::make("json_schema")
            ->default(json_encode($jsonSchema));

        //$fields[]=$newField;
        return $fields;
    }

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $json_data_array=[];
        if (is_array($record->json_data)){
            $json_data_array=$record->json_data;
        }
        else{
            $json_data_array=json_decode($record->json_data , true);
        }
        $json_schema_array=[];
        if (is_array($record->json_schema)){
            $json_schema_array=$record->json_schema;
        }
        else{
            $json_schema_array=json_decode($record->json_schema , true);
        }
        $json_admin_ui_schema_array=[];
        if (is_array($record->json_admin_ui_schema)){
            $json_admin_ui_schema_array=$record->json_admin_ui_schema;
        }
        else{
            $json_admin_ui_schema_array=json_decode($record->json_admin_ui_schema , true);
        }

        $dynamicFields = self::GetDynamicFields($json_data_array, $json_schema_array, $json_admin_ui_schema_array);

        return $form->schema(
            array_merge([
                Forms\Components\TextInput::make('name')
            ], $dynamicFields)
        );
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('image')->extraAttributes(['style' => 'max-width:100px']),

                Tables\Columns\TextColumn::make('default_language')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'view' => Pages\ViewApplication::route('/{record}'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
