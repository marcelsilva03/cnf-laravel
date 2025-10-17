<?php

namespace App\Filament\Resources;

use App\Enums\Parentesco;
use App\Filament\Resources\HomenagemResource\Pages;
use App\Forms\Components\ResourceImage;
use App\Models\Falecido;
use App\Models\Homenagem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class HomenagemResource extends Resource
{
    protected static ?string $model = Homenagem::class;

    protected static ?string $navigationIcon = 'fas-award';
    protected static ?string $navigationLabel = 'Homenagens';
    protected static ?string $slug = 'homenagens';
    protected static ?string $navigationGroup = 'GESTÃO';

    public static function form(Form $form): Form
    {
        $opcoesParentesco = Parentesco::toArray();
        return $form
            ->schema([
                Forms\Components\TextInput::make('hom_nome_autor')
                    ->label('Autor da Homenagem')
                    ->readOnly(),
                Forms\Components\TextInput::make('hom_cpf_autor')
                    ->label('CPF do Autor')
                    ->readOnly(),
                Forms\Components\TextInput::make('hom_whatsapp')
                    ->label('WhatsApp')
                    ->readOnly(),
                Forms\Components\TextInput::make('hom_email')
                    ->label('Email')
                    ->email()
                    ->readOnly(),
                Forms\Components\TextInput::make('hom_id_falecido')
                    ->label('Falecido')
                    ->formatStateUsing(function ($state) {
                        $falecido = Falecido::where('fal_id', $state)->first();
                        return $falecido->fal_nome;
                    })
                    ->disabled(),
                Forms\Components\Select::make('hom_parentesco')
                    ->label('Parentesco')
                    ->options($opcoesParentesco)
                    ->disabled(),
                ResourceImage::make('hom_url_foto')
                    ->label('Foto do(a) Falecido(a)')
                    ->viewData([
                        'field' => 'hom_url_foto',
                        'label' => 'Foto do(a) Falecido(a)',
                        'url' => fn ($record) => $record->hom_url_foto ? Storage::url($record->hom_url_foto) : null,
                    ]),
                ResourceImage::make('hom_url_fundo')
                    ->label('Foto de fundo')
                    ->viewData([
                        'field' => 'hom_url_fundo',
                        'label' => 'Foto de fundo',
                    ]),
                Forms\Components\DatePicker::make('created_at')
                    ->label('Criada em')
                    ->disabled()
                    ->displayFormat('d/m/Y H:i'),
                Forms\Components\TextInput::make('hom_codigo')
                    ->label('Código')
                    ->required()
                    ->readOnly(),
                Forms\Components\TextInput::make('hom_mensagem')
                    ->label('Mensagem')
                    ->required()
                    ->maxLength(500)
                    ->readOnly(function ($record) {
                        return in_array(
                            $record->hom_status, [
                                Homenagem::STATUS['PUBLICADO'],
                                Homenagem::STATUS['REMOVIDO']
                            ]
                        );
                    })
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $statusOptions = Homenagem::statusList();
        $user = auth()->user();
        $filtroPorStatus = Tables\Filters\SelectFilter::make('hom_status')
            ->label('Status')
            ->options($statusOptions);
        if ($user->hasRole('admin') || $user->hasRole('moderador')) {
            $filtroPorStatus = $filtroPorStatus->default(Homenagem::STATUS['PENDENTE']);
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hom_nome_autor')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('hom_email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hom_parentesco')
                    ->label('Parentesco')
                    ->formatStateUsing(fn ($state) => Parentesco::toArray()[$state]),
                Tables\Columns\TextColumn::make('hom_id_falecido')
                    ->label('Falecido(a)')
                    ->formatStateUsing(function ($state) {
                        $falecido = Falecido::where('fal_id', $state)->first();
                        return $falecido->fal_nome;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('hom_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        Homenagem::STATUS['PENDENTE'] => 'warning',
                        Homenagem::STATUS['PUBLICADO'] => 'success',
                        Homenagem::STATUS['REMOVIDO'] => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => $statusOptions[$state]),
            ])
            ->searchPlaceholder('Email ou falecido(a)')
            ->filters([
                $filtroPorStatus
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomenagems::route('/'),
            'create' => Pages\CreateHomenagem::route('/create'),
            'edit' => Pages\EditHomenagem::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        $roles = ['admin', 'moderador', 'solicitante'];
        return $user->hasRole($roles);
    }

    public static function canCreate(): bool
    {
        return false; // Administradores não devem criar homenagens
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(function ($query) {
            $user = auth()->user();
            if ($user->hasRole('admin') || $user->hasRole('moderador')) {
                return $query;
            }
            return $query->where('hom_email', $user->email);
        });
    }

}
