<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Number;
use Livewire\Attributes\On;
use Filament\Infolists\Components\Actions as ComponentsActions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return "Order {$this->record->code} - " . Number::currency($this->record->total);
    }

    #[On('refreshViewOrder')]
    public function refresh(): void
    {
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        ComponentsActions::make([
                            Action::make('status-change')->label('Cancel order')
                                ->color('danger')
                                ->link()
                                ->icon('heroicon-o-x-circle')
                                ->visible(fn($record) => ($record->status == OrderStatusEnum::SUCCESSFUL))
                                ->requiresConfirmation()
                                ->modalIcon('heroicon-o-x-circle')
                                ->form([
                                    Toggle::make('refund')->label(fn(Order $record) => 'Refund ' . Number::currency($record->total))->onColor('danger')->required(),
                                ])
                                ->action(function (array $data, Order $record) {
                                    if ($data['refund']) {
                                        $record->status = OrderStatusEnum::REFUNDED;
                                    } else {
                                        $record->status = OrderStatusEnum::CANCELLED;
                                    }
                                    $record->refund_at = now();
                                    $record->save();
                                    Notification::make()
                                        ->title("The order {$record->code} " . Number::currency($record->total) . " has been cancelled.")
                                        ->success()
                                        ->send();
                                }),
                            Action::make('update-status')->label('Update Status')
                                ->color('primary')
                                ->icon('heroicon-o-arrow-uturn-up')
                                ->link()
                                ->form([
                                    Select::make('status')
                                        ->options([
                                            OrderStatusEnum::SUCCESSFUL->value => 'Successful',
                                            OrderStatusEnum::PENDING->value => 'Pending',
                                            OrderStatusEnum::DELIVERED->value => 'Delivered',
                                            OrderStatusEnum::SHIPPED->value => 'Shipped',
                                        ])
                                        ->required()
                                        ->label('Select status'),
                                ])
                                ->action(function (array $data, Order $record) {
                                    $record->status = $data['status'];
                                    $record->save();

                                    // Send email notification
                                    try {
                                        app(\App\Http\Controllers\Checkout\PaymentCheckoutController::class)
                                            ->send_email($record, $data['status']);
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title("Email failed to send for order {$record->code}")
                                            ->danger()
                                            ->send();
                                    }

                                    // Send SMS notification
                                    try {
                                        $phone = $record->data?->user?->phone; // Ensure phone number exists
                                        if ($phone) {
                                            app(\App\Http\Controllers\Auth\OtpController::class)
                                                ->sendOrderStatusSMS($phone, $record->code, $data['status']);
                                        }
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title("SMS failed to send for order {$record->code}")
                                            ->danger()
                                            ->send();
                                    }

                                    Notification::make()
                                        ->title("The order {$record->code} status has been updated.")
                                        ->success()
                                        ->send();
                                }),
                        ])->columns(4)->columnStart(2)->alignment(Alignment::End),
                        Section::make([
                            TextEntry::make('data.user.name')->label('Client'),
                            TextEntry::make('data.user.phone')->label('Phone'),
                            // TextEntry::make('data.user.email')->label('Email'),
                            TextEntry::make('status')->label('Status')->badge(),
                            ViewEntry::make('order_products')->columnSpanFull()->view('filament.infolists.sales-view')
                        ])->columnSpan(2)->columns(3),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make([
                                    TextEntry::make('created_at')->label('Date')->dateTime(),
                                    TextEntry::make('refund_at')->visible(fn($state) => $state)->label('Cancelled at')->dateTime(),
                                ])->columnSpan(1),
                                Section::make('Payment')
                                    // ->visible(fn($record) => $record->payment)
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('payment.method')->badge()->label('Payment method'),
                                        TextEntry::make('payment.reference')->label('Reference'),
                                        TextEntry::make('payment.data')->columnSpanFull()->label('Observation')->placeholder('- No observation'),
                                    ]),
                            ])
                    ]),
            ]);
    }
}
