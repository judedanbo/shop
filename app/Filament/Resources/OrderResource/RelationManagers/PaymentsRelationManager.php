<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function isReadOnly(): bool
    {
        return false;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema(Payment::getForm($this->getOwnerRecord()->id, $this->getOwnerRecord()->total));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->emptyStateHeading('No payment yet')
            ->emptyStateActions([
                // Action::make('create')
                //     ->label('Pay')
                //     // ->url(route('posts.create'))
                //     // ->icon('heroicon-m-plus')
                //     ->button(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->sortable()
                    ->searchable()
                    ->date(),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('transaction_id'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('GHS'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
