<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FalecidosResource\Pages;
use App\Models\Falecido;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FalecidosResource extends Resource
{
    const opcoesSexo = [
        1 => 'Masculino',
        2 => 'Feminino',
    ];
    const opcoesStatus = [
        1 =>'Ativo', 0 => 'Inativo'
    ];

    protected static ?string $model = Falecido::class;
    protected $primaryKey = 'fal_id';

    protected static ?string $navigationLabel = 'Falecidos';
    protected static ?string $navigationIcon = 'fas-book-bible';
    protected static ?string $navigationGroup = 'GESTÃO';

    public static function form(Form $form): Form
    {
        $opcoesEstadoCivil = config('constants.estadosCivis', []);
        $opcoesLocalDeObito = config('constants.tipoLocalDeObito', []);
        $isAdmin = auth()->user()?->hasRole('admin') ?? false;
        $opcoesSexo = self::opcoesSexo;
        return $form
            ->schema([
                Forms\Components\TextInput::make('fal_nome')
                    ->label('Nome')
                    ->readOnly(!$isAdmin),
                Forms\Components\Checkbox::make('fal_status')
                    ->label('Ativo')
                    ->formatStateUsing(fn ($state) => $state === 1),
                Forms\Components\DatePicker::make('fal_data_nascimento')
                    ->label('Data de Nascimento')
                    ->readOnly(!$isAdmin),
                Forms\Components\TextInput::make('fal_nome_mae')
                    ->label('Nome do Mãe')
                    ->readOnly(!$isAdmin),
                Forms\Components\TextInput::make('fal_nome_pai')
                    ->label('Nome do Pai')
                    ->readOnly(!$isAdmin),
                Forms\Components\Select::make('fal_estado_civil')
                    ->label('Estado Civil')
                    ->options($opcoesEstadoCivil)
                    ->disabled(!$isAdmin),
                Forms\Components\TextInput::make('fal_cpf')
                    ->label('CPF')
                    ->readOnly(!$isAdmin),
                Forms\Components\TextInput::make('fal_rg')
                    ->label('RG')
                    ->readOnly(!$isAdmin),
                Forms\Components\TextInput::make('fal_titulo_eleitor')
                    ->label('Título de Eleitor')
                    ->readOnly(!$isAdmin),
                Forms\Components\DatePicker::make('fal_data_falecimento')
                    ->label('Data de Falecimento')
                    ->readOnly(!$isAdmin),
                Forms\Components\Select::make('fal_tipo_local_falecimento')
                    ->label('Local de Falecimento')
                    ->options($opcoesLocalDeObito)
                    ->disabled(!$isAdmin),
                Forms\Components\Select::make('fal_sexo')
                    ->label('Sexo')
                    ->options($opcoesSexo)
                    ->disabled(!$isAdmin),
                Forms\Components\Textarea::make('fal_biografia')
                    ->label('Biografia')
                    ->readOnly(!$isAdmin),
             ]);
    }

    public static function table(Table $table): Table
    {
        $localidades = config('constants.localidades', []);
        $siglas = array_keys($localidades);
        $ufs = !empty($siglas) ? array_combine($siglas, $siglas) : [];

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fal_nome')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fal_data_falecimento')
                    ->label('Data de Falecimento')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => implode('/', array_reverse(explode('-', $state)))),
                Tables\Columns\TextColumn::make('fal_uf')
                    ->label('UF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fal_cidade')
                    ->label('Cidade')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fal_sexo')
                    ->label('Sexo')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Masculino',
                        2 => 'Feminino',
                        default => 'Desconhecido',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fal_uf')
                    ->label('UF')
                    ->options($ufs)
                    ->placeholder('Todas'),
                Tables\Filters\SelectFilter::make('fal_sexo')
                    ->label('Sexo')
                    ->options(self::opcoesSexo),
                Tables\Filters\Filter::make('date_range')
                    ->label('Data de Falecimento')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                        ->label('Data inicial'),
                        Forms\Components\DatePicker::make('end_date')
                        ->label('Data final')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            isset($data['start_date'], $data['end_date']),
                            function ($query) use ($data) {
                                $query->whereBetween('fal_data_falecimento', [$data['start_date'], $data['end_date']]);
                            });
                    }),
                Tables\Filters\Filter::make('status')
                    ->label('Status')
                    ->form([
                        Forms\Components\Checkbox::make('apenas_ativos')
                        ->label('Mostrar apenas ativos')
                        ->default(true)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['apenas_ativos']) {
                            return $query->where('fal_status', '=', 1);
                        }
                        return $query;
                    })
            ])
            ->recordClasses(function (Falecido $falecido) {
                return $falecido->fal_status === 0
                    ? 'bg-red-100'
                    : '';
            })
            ->emptyStateIcon('heroicon-m-face-frown')
            ->emptyStateHeading('Nenhum Falecido encontrado.')
            ->emptyStateDescription('Nenhum Falecido encontrado utilizando os filtros atualmente definidos.')
            ->emptyStateActions([
                    Tables\Actions\Action::make('clear_filters')
                        ->label('Limpar filtros')
                        ->url(fn (): string => static::getUrl('index'))
                        ->icon('heroicon-m-x-mark')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'moderador']) ?? false),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn (Falecido $falecido) => $falecido->fal_status === 0 ? 'Reativar' : 'Remover')
                    ->icon(fn (Falecido $falecido) => $falecido->fal_status === 0 ? 'heroicon-s-check' : 'heroicon-s-x-mark')
                    ->color(fn (Falecido $falecido) => $falecido->fal_status === 0 ? 'success' :'danger')
                    ->action(function (Falecido $falecido) {
                        $val = $falecido->fal_status === 0 ? 1 : 0;
                        $falecido->update(['fal_status' => $val]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn (Falecido $falecido) => $falecido->fal_status === 0 ? 'Confirmar Reativação' : 'Confirmar Remoção')
                    ->modalDescription(fn (Falecido $falecido) => $falecido->fal_status === 0 ? 'Ao reativar este registro ele será acessível ao público. Deseja prosseguir?' : 'Tem certeza de que deseja remover este registro?')
                    ->modalSubmitActionLabel(fn (Falecido $falecido) => $falecido->fal_status === 0 ? 'Reativar' : 'Remover')
                    ->visible(fn () => auth()->user()?->hasRole(['admin', 'moderador']) ?? false)
            ])
            ->bulkActions([]);
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        // Apenas admin e moderador podem editar
        return $user?->hasRole(['admin', 'moderador']) ?? false;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'pesquisador', 'moderador'];
        return $user?->hasRole($roles) ?? false;
    }
    
    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'pesquisador', 'moderador'];
        return $user?->hasRole($roles) ?? false;
    }
    public static function canDeleteAny(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('fal_status', 'ASC');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFalecidos::route('/'),
//            'create' => Pages\CreateFalecidos::route('/create'),
            'edit' => Pages\EditFalecidos::route('/{record}/edit'),
        ];
    }
}
