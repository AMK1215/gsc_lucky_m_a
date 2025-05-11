<?php

namespace App\Http\Controllers\Admin\Deposit;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\TransactionName;
use App\Models\DepositRequest;
use App\Models\WithDrawRequest;
use App\Services\WalletService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepositRequestController extends Controller
{
    public function index(Request $request)
    {

        $startDate = $request->start_date
        ? Carbon::parse($request->start_date)->startOfDay()->toDateTimeString()
        : Carbon::today()->startOfDay()->toDateTimeString();

    $endDate = $request->end_date
        ? Carbon::parse($request->end_date)->endOfDay()->toDateTimeString()
        : Carbon::today()->endOfDay()->toDateTimeString();

        $query = DepositRequest::with(['user', 'paymentType', 'agent'])
                                ->where('agent_id', Auth::id());


        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate && !$endDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif (!$startDate && $endDate) {
            $query->where('created_at', '<=', $endDate);
        }


        $deposits = $query->orderBy('id', 'desc')
                          ->paginate(15)
                          ->appends($request->only(['start_date', 'end_date']));

        return view('admin.deposit_request.index', compact('deposits'));
    }

    public function statusChangeIndex(Request $request, DepositRequest $deposit)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
            'amount' => 'required|numeric|min:0',
            'player' => 'required|exists:users,id',
        ]);

        try {
            $agent = Auth::user();
            $player = User::find($request->player);

            // Check if the status is being approved and balance is sufficient
            if ($request->status == 1 && $agent->balanceFloat < $request->amount) {
                return redirect()->back()->with('error', 'You do not have enough balance to transfer!');
            }
            // Update the deposit status
            $deposit->update([
                'status' => $request->status,
            ]);

            // Transfer the amount if the status is approved
            if ($request->status == 1) {
                app(WalletService::class)->transfer($agent, $player, $request->amount, TransactionName::DebitTransfer);
            }

            return redirect()->route('admin.agent.deposit')->with('success', 'Deposit status updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function statusChangeReject(Request $request, DepositRequest $deposit)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        try {
            // Update the deposit status
            $deposit->update([
                'status' => $request->status,
            ]);

            return redirect()->route('admin.agent.deposit')->with('success', 'Deposit status updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
