<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Application;
use Creagia\FilamentCodeField\CodeField;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;
use function PHPUnit\Framework\isFalse;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;
    protected static ?string $navigationIcon = 'heroicon-o-window';
    protected static ?string $navigationLabel = 'Application';
    protected static ?string $modelLabel = 'Application';
    protected static ?string $navigationGroup = 'Application Management';
    protected static ?int $navigationSort = 1;

    public static function GetDynamicSection($jsonData, $jsonSchema, $jsonUiSchema): array
    {
        $mainSections = [];
        $fields = [];

        // Iterate over json_schema properties to create form fields
        foreach ($jsonSchema['properties'] as $propertyKey => $propertyValue) {
            $fieldType = $propertyValue['type'];
            $uiSchema = $jsonUiSchema[$propertyKey] ?? null;

            isset($uiSchema['ui:readOnly'])?
                $uiReadOnly=$uiSchema['ui:readOnly']==true:
                $uiReadOnly=false;

            // Based on the type of field from json schema,
            // create appropriate Filament form group of fields
            if ($fieldType === 'object') {
                //This is a header fore each key
                $placeHolder = Forms\Components\Placeholder::make($propertyKey)
                    ->label("KEY ".$propertyKey)
                ->columnSpanFull();
                if(isset($uiSchema['ui:helper'])&&$uiSchema['ui:helper']!="")$placeHolder->helperText($uiSchema['ui:helper']);

                $fields[]=$placeHolder;

                foreach ($propertyValue['properties'] as $langKey => $langValue) {
                    $field = null;
                    $uiSchemaProperties = $uiSchema['properties'][$langKey] ?? [];
                    $label = $propertyKey." (".$langKey.")";
                    $uiComponentType = $uiSchema['ui:component']??null;

                    // Create field based on component type
                    switch ($uiComponentType) {
                        case 'Textarea':
                            $field = Forms\Components\Textarea::make("json_data." . $propertyKey . "." . $langKey)
                                ->label($propertyKey." (".$langKey.")")
                                //->placeholder($uiOptions['placeholder'] ?? '')
                                ->helperText($uiSchemaProperties['helper'] ?? '')
                                ->autosize()
                                ->default($jsonData[$propertyKey][$langKey] ?? '');
                            break;

                        case 'Toggle':
                            $field = Forms\Components\Toggle::make("json_data." . $propertyKey . "." . $langKey)
                                ->label($label)
                                ->helperText($uiSchemaProperties['helper'] ?? '')
                                ->default($jsonData[$propertyKey][$langKey] ?? false)
                                ->columns(1);
                            break;

                        case 'TextInput':
                            $field = Forms\Components\TextInput::make("json_data." . $propertyKey . "." . $langKey)
                                ->label($label)
                                ->helperText($uiSchemaProperties['helper'] ?? '')
                                ->placeholder($uiSchemaProperties['placeholder'] ?? '')
                                ->default($jsonData[$propertyKey][$langKey] ?? '');

                            break;

                        default:
                            $field = Forms\Components\TextInput::make("json_data." . $propertyKey . "." . $langKey)
                                ->label($label)
                                ->placeholder($uiSchemaProperties['placeholder'] ?? '')
                                ->helperText($uiSchemaProperties['helper'] ?? '')
                                ->default($jsonData[$propertyKey][$langKey] ?? '');
                            break;
                    }
                    $field->disabled($uiReadOnly);
                    $fields[] = $field;
                }
            }
        }
        $mainSections[] = Section::make('JSON DATA')
            ->description('Edit values from json_data field')
            ->schema($fields)
            ->collapsible();

        return $mainSections;
    }

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();

        is_array($record->json_data)?
            $json_data_array = $record->json_schema:
            $json_data_array = json_decode($record->json_schema, true);

        is_array($record->json_schema)?
            $json_schema_array = $record->json_schema:
            $json_schema_array = json_decode($record->json_schema, true);

        is_array($record->json_admin_ui_schema)?
            $json_admin_ui_schema_array = $record->json_admin_ui_schema:
            $json_admin_ui_schema_array = json_decode($record->json_admin_ui_schema, true);

        $dynamicSection =  self::GetDynamicSection($json_data_array, $json_schema_array, $json_admin_ui_schema_array);

        return $form->schema(
            array_merge(
                //NATIVE FIELDS FROM THE MODEL GOES HERE
                [
                    Forms\Components\TextInput::make('name')
                        ->columns(1),
                    Forms\Components\TextInput::make('default_language')
                        ->maxLength(2)
                        ->columns(1),

                    CodeField::make('languages')
                        ->withLineNumbers()
                        ->dehydrateStateUsing(fn($state) => json_decode($state))
                        ->formatStateUsing(fn($state) => empty($state) ? '{}' : json_encode($state, JSON_PRETTY_PRINT))
                        ->columnSpanFull(),
                ],
                //JSON DATA FIELDS GOES HERE
                $dynamicSection,
                //CODE EDITORS TO EDIT SCHEMAS GOES HERE
                [
                    Section::make('Json Schema')
                        ->description('Change the schema data')
                        ->schema([
                            CodeField::make('json_schema')
                                ->withLineNumbers()
                                ->dehydrateStateUsing(fn($state) => json_decode($state))
                                ->formatStateUsing(fn($state) => empty($state) ? '{}' : json_encode($state, JSON_PRETTY_PRINT))
                                ->columnSpanFull(),
                        ])
                        ->collapsed(),
                    Section::make('Json Admin UI Schema')
                        ->description('Change the ui for admin users')
                        ->schema([
                            CodeField::make('json_admin_ui_schema')
                                ->withLineNumbers()
                                ->dehydrateStateUsing(fn($state) => json_decode($state))
                                ->formatStateUsing(fn($state) => empty($state) ? '{}' : json_encode($state, JSON_PRETTY_PRINT))
                                ->columnSpanFull(),
                        ])
                        ->collapsed(),
                    Section::make('Json Manager UI Schema')
                        ->description('Change the ui for manager users')
                        ->schema([
                            CodeField::make('json_manager_ui_schema')
                                ->withLineNumbers()
                                ->dehydrateStateUsing(fn($state) => json_decode($state))
                                ->formatStateUsing(fn($state) => empty($state) ? '{}' : json_encode($state, JSON_PRETTY_PRINT))
                                ->columnSpanFull(),
                        ])
                        ->collapsed(),
                ]
            )
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
