<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('email')
                    ->label('Customer Email')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('address')
                    ->label('Shipping Address')
                    ->limit(50), // Limit text length for better UI
    
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->sortable()
                    ->money('USD'),
    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'danger' => 'failed',
                        'success' => 'completed',
                        'warning' => 'pending',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)) // Capitalize the status
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s'),
    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s'),
            ])
            ->filters([
                // Optional filter to select orders by status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
