<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartorioResource\Pages;
use App\Models\Cartorio;
use App\Services\LocalidadesService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartorioResource extends Resource
{
    protected static ?string $model = Cartorio::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $recordRouteKeyName = 'ccc_id';

    protected static ?string $navigationLabel = 'Cartórios';

    protected static ?string $modelLabel = 'Cartório';

    protected static ?string $pluralModelLabel = 'Cartórios';

    protected static ?string $navigationGroup = 'GESTÃO';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('ccc_nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\TextInput::make('ccc_nome_fantasia')
                            ->label('Nome Fantasia')
                            ->maxLength(150),
                        Forms\Components\TextInput::make('ccc_cnpj')
                            ->label('CNPJ')
                            ->mask('99.999.999/9999-99')
                            ->maxLength(40),
                        Forms\Components\TextInput::make('ccc_cns')
                            ->label('CNS')
                            ->maxLength(20),
                        Forms\Components\Select::make('ccc_tipo')
                            ->label('Tipo')
                            ->options([
                                1 => 'Registro Civil',
                                2 => 'Tabelionato',
                                3 => 'Registro de Imóveis',
                                4 => 'Outros',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Endereço')
                    ->schema([
                        Forms\Components\TextInput::make('ccc_endereco')
                            ->label('Endereço')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('ccc_bairro')
                            ->label('Bairro')
                            ->maxLength(1000),
                        Forms\Components\Select::make('ccc_uf')
                            ->label('UF')
                            ->options(function () {
                                static $options = null;
                                if ($options === null) {
                                    $localidadesService = app(LocalidadesService::class);
                                    $ufs = $localidadesService->obterSiglasDosEstados();
                                    $options = array_combine($ufs, $ufs);
                                }
                                return $options;
                            })
                            ->searchable()
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('ccc_cidade')
                            ->label('Cidade')
                            ->options(function (callable $get) {
                                $uf = $get('ccc_uf');
                                if (!$uf) {
                                    return [];
                                }
                                $localidadesService = app(LocalidadesService::class);
                                $cidades = $localidadesService->obterNomeDasCidades($uf);
                                return array_combine($cidades, $cidades);
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('ccc_cep')
                            ->label('CEP')
                            ->mask('99999-999')
                            ->maxLength(30),
                        Forms\Components\TextInput::make('ccc_comarca')
                            ->label('Comarca')
                            ->maxLength(100),
                    ])->columns(2),

                Forms\Components\Section::make('Contato')
                    ->schema([
                        Forms\Components\TextInput::make('ccc_telefone')
                            ->label('Telefone')
                            ->tel()
                            ->maxLength(70),
                        Forms\Components\TextInput::make('ccc_fax')
                            ->label('Fax')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('ccc_email')
                            ->label('E-mail')
                            ->email()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ccc_site')
                            ->label('Website')
                            ->url()
                            ->maxLength(1000),
                    ])->columns(2),

                Forms\Components\Section::make('Responsáveis')
                    ->schema([
                        Forms\Components\TextInput::make('ccc_nome_titular')
                            ->label('Nome do Titular')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ccc_nome_substituto')
                            ->label('Nome do Substituto')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ccc_nome_juiz')
                            ->label('Nome do Juiz')
                            ->maxLength(100),
                    ])->columns(2),

                Forms\Components\Section::make('Informações Adicionais')
                    ->schema([
                        Forms\Components\TextInput::make('ccc_area_abrangencia')
                            ->label('Área de Abrangência')
                            ->maxLength(200),
                        Forms\Components\TextInput::make('ccc_atribuicoes')
                            ->label('Atribuições')
                            ->maxLength(300),
                        Forms\Components\TextInput::make('ccc_horario_funcionamento')
                            ->label('Horário de Funcionamento')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ccc_entrancia')
                            ->label('Entrância')
                            ->maxLength(50),
                        Forms\Components\Textarea::make('ccc_obs')
                            ->label('Observações')
                            ->maxLength(1500)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ccc_id')
                    ->label('ID')
                    ->sortable()
                    ->width('80px'),
                Tables\Columns\TextColumn::make('ccc_nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 40) {
                            return $state;
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('ccc_cidade')
                    ->label('Cidade')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('ccc_uf')
                    ->label('UF')
                    ->searchable()
                    ->sortable()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('ccc_telefone')
                    ->label('Telefone')
                    ->searchable()
                    ->limit(15),
                Tables\Columns\TextColumn::make('ccc_email')
                    ->label('E-mail')
                    ->searchable()
                    ->limit(25)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 25) {
                            return $state;
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ccc_uf')
                    ->label('UF')
                    ->options(function () {
                        $localidadesService = app(LocalidadesService::class);
                        $ufs = $localidadesService->obterSiglasDosEstados();
                        return array_combine($ufs, $ufs);
                    }),
                Tables\Filters\SelectFilter::make('ccc_tipo')
                    ->label('Tipo')
                    ->options([
                        1 => 'Registro Civil',
                        2 => 'Tabelionato',
                        3 => 'Registro de Imóveis',
                        4 => 'Outros',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->tooltip('Visualizar'),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('ccc_nome');
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
            'index' => Pages\ListCartorios::route('/'),
            'create' => Pages\CreateCartorio::route('/create'),
            'view' => Pages\ViewCartorio::route('/{record}'),
            'edit' => Pages\EditCartorio::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin', 'moderador', 'pesquisador']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin']);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin', 'moderador']);
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user->hasRole(['admin']);
    }
}