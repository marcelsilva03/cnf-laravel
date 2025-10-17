<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitacaoResource\Pages;
use App\Filament\Resources\SolicitacaoResource\RelationManagers;
use App\Models\Solicitacao;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SolicitacaoResource extends Resource
{
    protected static ?string $model = Solicitacao::class;
    protected static ?string $slug = 'solicitacoes';
    protected static ?string $navigationLabel = 'Solicitações';
    protected static ?string $pluralLabel = 'Solicitações';
    protected static ?string $label = 'Solicitação';
    protected static ?string $navigationGroup = 'FINANCEIRO';
    protected static ?string $navigationIcon = 'fas-search';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return !$user->hasRole('moderador');
    }

    public static function form(Form $form): Form
    {
        $opcoesEstadoCivil = config('constants.estadosCivis');
        $opcoesLocalDeObito = config('constants.tipoLocalDeObito');
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do Falecido')
                    ->schema([
                        Forms\Components\Grid::make(4) // Two-column layout
                        ->schema([
                            Forms\Components\TextInput::make('sol_nome_fal')
                                ->label('Nome do Falecido')
                                ->required()
                                ->maxLength(60),
                            Forms\Components\TextInput::make('sol_cpf_fal')
                                ->label('CPF')
                                ->required()
                                ->maxLength(14)
                                ->mask('999.999.999-99')
                                ->placeholder('XXX.XXX.XXX-XX'),
                            Forms\Components\TextInput::make('sol_rg_fal')
                                ->label('RG')
                                ->required()
                                ->maxLength(12),
                            Forms\Components\TextInput::make('sol_titulo_eleitor')
                                ->label('Título de Eleitor')
                                ->required()
                                ->maxLength(11),
                        ]),
                        Forms\Components\Grid::make(2) // Two-column layout
                        ->schema([
                            Forms\Components\TextInput::make('sol_nome_pai_fal')
                                ->label('Nome do Pai')
                                ->maxLength(60)
                                ->required(),
                            Forms\Components\TextInput::make('sol_nome_mae_fal')
                                ->label('Nome da Mãe')
                                ->maxLength(60)
                                ->required(),
                        ]),
                        Forms\Components\Grid::make(3) // Two-column layout
                        ->schema([
                            Forms\Components\DatePicker::make('sol_data_nascimento')
                                ->label('Data do Nascimento')
                                ->required(),
                            Forms\Components\DatePicker::make('sol_data_obito')
                                ->label('Data do Falecimento')
                                ->required(),
                            Forms\Components\Select::make('sol_id_abr')
                                ->label('Abrangencia')
                                ->relationship('abrangencia', 'abr_desc')
                                ->preload()
                                ->required(),
                        ]),
                        Forms\Components\Grid::make(3) // Two-column layout
                        ->schema([
                            Forms\Components\TextInput::make('sol_estado_cidade')
                                ->label('Estado e Cidade (Óbito)')
                                ->maxLength(100)
                                ->required(),
                            Forms\Components\Select::make('sol_local_obito_tipo')
                                ->label('Local do Falecimento')
                                ->options($opcoesLocalDeObito)
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('sol_estado_civil')
                                ->label('Estado Civil')
                                ->options($opcoesEstadoCivil)
                                ->preload()
                                ->required(),
                        ]),
                        Forms\Components\Grid::make(1) // Two-column layout
                        ->schema([
                            Forms\Components\TextInput::make('sol_obs')
                                ->label('Informações Adicionais')
                                ->maxLength(255),
                        ]),
                    ]),
                Forms\Components\Section::make('Dados da Solicitação')
                    ->schema([
                        Forms\Components\Grid::make(2) // Two-column layout
                        ->schema([
                            Forms\Components\TextInput::make('sol_nome_sol')
                                ->label('Responsável pela Solicitação')
                                ->maxLength(60)
                                ->required(),
                            Forms\Components\TextInput::make('sol_email_sol')
                                ->label('Email do Solicitante')
                                ->email()
                                ->required()
                                ->maxLength(60)
                                ->reactive(),
                        ]),
                        Forms\Components\Grid::make(3) // Two-column layout
                        ->schema([
                            Forms\Components\TextInput::make('sol_tel_sol')
                                ->label('Telefone')
                                ->tel()
                                ->maxLength(15),
                            Forms\Components\DatePicker::make('created_at')
                                ->label('Data de Envio')
                                ->nullable(),
                            Forms\Components\Select::make('sol_status')
                                ->label('Status')
                                ->options(Solicitacao::statusList())
                                ->required(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $opcoesStatus = Solicitacao::statusList();
        $filters = [];
        $user = auth()->user();
        $filterByStatus = Tables\Filters\SelectFilter::make('sol_status')
            ->label('Status')
            ->options($opcoesStatus);
        if ($user->hasRole('pesquisador')) {
            $filterByStatus = $filterByStatus->default(Solicitacao::STATUS['PENDENTE']);
        }
        $filters[] = $filterByStatus;
        $filters[] = Tables\Filters\Filter::make('sol_data_obito')
            ->form([
                DatePicker::make('obito_from')
                    ->label('Data de óbito mínima'),
                DatePicker::make('obito_to')
                    ->label('Data de óbito máxima'),
            ])
            ->query(function (Builder $query, $data) {
                return $query
                    ->when(
                        $data['obito_from'],
                        fn(Builder $query, $date) => $query->whereDate('sol_data_obito', '>=', $date),
                    )
                    ->when(
                        $data['obito_to'],
                        fn(Builder $query, $date) => $query->whereDate('sol_data_obito', '<=', $date),
                    );
            });
        $filters[] = Tables\Filters\Filter::make('created_at')
            ->form([
                DatePicker::make('created_from')
                    ->label('Data da solicitação mínima'),
                DatePicker::make('created_to')
                    ->label('Data de solicitação máxima'),
            ])
            ->query(function (Builder $query, $data) {
                return $query
                    ->when(
                        $data['created_from'],
                        fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_to'],
                        fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date),
                    );
            });
        $t = $table
            ->columns([
                Tables\Columns\TextColumn::make('sol_nome_fal')
                    ->label('Nome do Falecido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sol_email_sol')
                    ->label('Email do Solicitante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sol_data_obito')
                    ->label('Data de Óbito')
                    ->formatStateUsing(fn ($state) => date('d/m/Y', strtotime($state)))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sol_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        Solicitacao::STATUS['PENDENTE'] => 'warning',
                        Solicitacao::STATUS['APROVADA'] => 'success',
                        Solicitacao::STATUS['REJEITADA'] => 'danger',
                        default => 'secondary'
                    })
                    ->formatStateUsing(fn ($state) => $opcoesStatus[$state])
                    ->sortable(),
            ])
            ->searchPlaceholder('Pesquisar nome ou email')
            ->filters($filters)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
        if ($user->hasRole('pesquisador') || $user->hasRole('admin')) {
            $t->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        }
        return $t;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'pesquisador', 'solicitante', 'financeiro'];
        $whoCanView = $user->hasRole($roles);
        return $whoCanView;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $defaultQuery = parent::getEloquentQuery();
        if ($user->hasRole(['admin', 'financeiro'])) {
            return $defaultQuery;
        }
        if ($user->hasRole('pesquisador')) {
            return $defaultQuery->where('sol_status', Solicitacao::STATUS['PENDENTE']);
        }
        return $defaultQuery->where('sol_email_sol', $user->email);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitacaos::route('/'),
            'create' => Pages\CreateSolicitacao::route('/create'),
            'edit' => Pages\EditSolicitacao::route('/{record}/edit'),
        ];
    }


}
