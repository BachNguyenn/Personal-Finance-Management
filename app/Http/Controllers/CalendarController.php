<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
   /**
    * Display the calendar view.
    */
   public function index()
   {
      return view('calendar.index');
   }

   /**
    * Get events for FullCalendar.
    */
   public function getEvents(Request $request)
   {
      $userId = Auth::id();
      $start = $request->get('start');
      $end = $request->get('end');

      $transactions = Transaction::where('user_id', $userId)
         ->whereBetween('transaction_date', [$start, $end])
         ->with('category')
         ->get();

      $events = $transactions->map(function ($transaction) {
         $isIncome = $transaction->type === 'income';
         $color = $isIncome ? '#28a745' : '#dc3545';

         // If category has color, use it (maybe slightly adjusted)
         if ($transaction->category && $transaction->category->color) {
            // We keep simple red/green for income/expense distinction usually better for calendar
            // But let's use category color border or stick to income/expense colors for clarity
         }

         return [
            'id' => $transaction->id,
            'title' => $this->formatMoney($transaction->amount) . ' - ' . $transaction->description,
            'start' => $transaction->transaction_date->format('Y-m-d'),
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => [
               'amount' => $transaction->amount,
               'type' => $transaction->type,
               'category' => $transaction->category ? $transaction->category->name : 'N/A',
               'description' => $transaction->description
            ]
         ];
      });

      return response()->json($events);
   }

   private function formatMoney($amount)
   {
      if ($amount >= 1000000) {
         return round($amount / 1000000, 1) . 'M';
      } elseif ($amount >= 1000) {
         return round($amount / 1000, 0) . 'k';
      }
      return $amount;
   }
}
