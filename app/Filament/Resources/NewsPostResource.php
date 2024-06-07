<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsPostResource\Pages;
use App\Filament\Resources\NewsPostResource\RelationManagers;
use App\Models\NewsPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Forms\Components\NewsPostContent;

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('author_id')->default(Auth::user()->id),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('thumbnail') 
                ->image()   
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                    return Crypt::encryptString(Auth::user()->id . date('Y-m-d H:i:s')) . '.' . $file->getClientOriginalExtension();
                })
                ->directory('news_post_thumbnails'),

                NewsPostContent::make('content')
                ->columnSpanFull(),
                
                Forms\Components\FileUpload::make('pictures')
                ->image()
                ->multiple()
                ->directory('news_post_pictures'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('thumbnail')
                ->default(function ($record) {
                    return $record->getThumbnailPathAttribute();
                })
                ->square(),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListNewsPosts::route('/'),
            'create' => Pages\CreateNewsPost::route('/create'),
            'view' => Pages\ViewNewsPost::route('/{record}'),
            'edit' => Pages\EditNewsPost::route('/{record}/edit'),
        ];
    }    
}
