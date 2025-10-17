<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComunicadosDeObitoResource\Pages;
use App\Filament\Resources\ComunicadosDeObitoResource\RelationManagers;
use App\Models\ComunicadoDeObito;
use App\Models\Falecido;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComunicadosDeObitoResource extends Resource
{
    protected static ?string $model = ComunicadoDeObito::class;
    protected static ?string $navigationLabel = 'Comunicados de Óbito';
    protected static ?string $navigationGroup = 'GESTÃO';
    protected static int $sort = 4;

    protected static ?string $navigationIcon = 'fas-inbox';

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->user()->hasRole('admin');
        $opcoesEstadoCivil = config('constants.opcoes_estado_civil');

        return $form
            ->schema([
                Forms\Components\Section::make('Informações Solicitante')
                ->schema([
                    Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('nome_sol')
                            ->label('Nome')
                            ->readonly(),
                        Forms\Components\TextInput::make('email_sol')
                            ->label('Email')
                            ->readonly(),
                    ])
                ]),
                Forms\Components\Section::make('Informações do(a) Falecido(a)')
                ->schema([
                    Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('nome_fal')
                            ->label('Nome')
                            ->readonly(),
                        Forms\Components\TextInput::make('cpf_fal')
                            ->label('CPF')
                            ->readonly(),
                        Forms\Components\TextInput::make('rg_fal')
                            ->label('RG')
                            ->readonly(),
                        Forms\Components\DatePicker::make('data_nascimento')
                            ->label('Data de Nascimento')
                            ->readonly(),
                        Forms\Components\DatePicker::make('data_obito')
                            ->label('Data de Óbito')
                            ->readonly(),
                        Forms\Components\TextInput::make('termo')
                            ->label('Termo')
                            ->readonly(),
                        Forms\Components\TextInput::make('estado_civil')
                            ->label('Estado Civil')
                            ->readonly(),
                        Forms\Components\Select::make('cartorio_id')
                            ->label('Cartório')
                            ->relationship('cartorio', 'ccc_nome')
                            ->searchable()
                            ->preload()
                            ->disabled(),
                        Forms\Components\TextInput::make('livro')
                            ->label('Livro')
                            ->readonly(),
                        Forms\Components\TextInput::make('nome_pai_fal')
                            ->label('Nome do Pai')
                            ->readonly(),
                        Forms\Components\TextInput::make('local_obito_tipo')
                            ->label('Tipo do Local de Óbito')
                            ->formatStateUsing(fn ($state) => config('constants.tipoLocalDeObito')[$state])
                            ->readonly(),
                        Forms\Components\TextInput::make('cidade_estado_obito')
                            ->label('Cidade/Estado do Óbito')
                            ->readonly(),
                        Forms\Components\Textarea::make('obs')
                            ->label('Observações')
                            ->readonly(),
                        Forms\Components\TextInput::make('folha')
                            ->label('Folha')
                            ->readonly(),
                        Forms\Components\TextInput::make('titulo_eleitor')
                            ->label('Título de Eleitor')
                            ->readonly(),
                        Forms\Components\TextInput::make('nome_mae_fal')
                            ->label('Nome da Mãe')
                            ->readonly(),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $statusComunicado = ComunicadoDeObito::statusList();
        $opcoesLocalDeObito = config('constants.tipoLocalDeObito');
        $isAdmin = auth()->user()->hasRole('admin');
        $acaoVisualizar = Tables\Actions\Action::make('view')
            ->label('Visualizar')
            ->icon('heroicon-s-eye')
            ->color('primary')
            ->url(function ($record) {
                if ($record->status === 1) {
                    $falecido = Falecido::where('fal_nome', $record->nome_fal)->first();
                    return route('filament.dashboard.resources.falecidos.edit', $falecido);
                }
                return route('filament.dashboard.resources.comunicados-de-obitos.edit', $record);
            });

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_fal')
                    ->label('Nome do Falecido')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_obito')
                    ->label('Data do Óbito')
                    ->formatStateUsing(fn ($state) => date('d/m/Y', strtotime($state)))
                    ->sortable(),
                Tables\Columns\TextColumn::make('local_obito_tipo')
                    ->label('Local Óbito')
                    ->formatStateUsing(fn ($state) => $opcoesLocalDeObito[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_sol')
                    ->label('Email Solicitante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        ComunicadoDeObito::STATUS['PENDENTE'] => 'warning',
                        ComunicadoDeObito::STATUS['APROVADO'] => 'success',
                        ComunicadoDeObito::STATUS['REJEITADO'] => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => $statusComunicado[$state])
            ])
            ->searchPlaceholder('Pesquisar nome ou email')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options($statusComunicado)
                ->default(0),
                Tables\Filters\SelectFilter::make('local_obito_tipo')
                ->label('Local de óbito')
                ->options($opcoesLocalDeObito),
                Tables\Filters\Filter::make('date_range')
                    ->label('Data do Óbito')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Data inicial'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Data final')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(isset($data['start_date'], $data['end_date']), function ($query) use ($data) {
                            $query->whereBetween('data_obito', [$data['start_date'], $data['end_date']]);
                        });
                    })
            ])
            ->actions($isAdmin
                ? [$acaoVisualizar, Tables\Actions\DeleteAction::make()]
                : [$acaoVisualizar]
            );
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'pesquisador', 'solicitante', 'moderador'];
        $whoCanView = $user->hasRole($roles);
        return $whoCanView;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user->hasRole(['admin', 'pesquisador'])) {
            return $query;
        } elseif ($user->hasRole('moderador')) {
            return $query->where('status', ComunicadoDeObito::STATUS['PENDENTE']);
        }
        return $query->where('email_sol', $user->email);
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        // Admins, pesquisadores e moderadores podem ver qualquer registro
        if ($user->hasRole(['admin', 'pesquisador', 'moderador'])) {
            return true;
        }
        
        // Solicitantes só podem ver seus próprios registros
        $comunicado = ComunicadoDeObito::where('email_sol', $user->email)->first();
        return !!$comunicado;
    }

    public static function canCreate(): bool
    {
        return false; // Administradores não devem criar comunicados de óbito
    }
 
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComunicadosDeObitos::route('/'),
            'create' => Pages\CreateComunicadosDeObito::route('/create'),
            'edit' => Pages\EditComunicadosDeObito::route('/{record}/edit'),
        ];
    }
}

