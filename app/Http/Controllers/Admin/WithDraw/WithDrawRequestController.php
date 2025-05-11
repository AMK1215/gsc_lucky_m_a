<?php

namespace App\Http\Controllers\Admin\WithDraw;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\TransactionName;
use App\Models\WithDrawRequest;
use App\Services\WalletService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WithDrawRequestController extends Controller
{
    public function index(Request $request)
    {

        $startDate = $request->start_date
        ? Carbon::parse($request->start_date)->startOfDay()->toDateTimeString()
        : Carbon::today()->startOfDay()->toDateTimeString();

        $endDate = $request->end_date
        ? Carbon::parse($request->end_date)->endOfDay()->toDateTimeString()
        : Carbon::today()->endOfDay()->toDateTimeString();

        $query = WithDrawRequest::with(['user'])
                                        ->where('agent_id', Auth::id());

                                        if ($startDate && $endDate) {
                                            $query->whereBetween('created_at', [$startDate, $endDate]);
                                        } elseif ($startDate && !$endDate) {
                                            $query->where('created_at', '>=', $startDate);
                                        } elseif (!$startDate && $endDate) {
                                            $query->where('created_at', '<=', $endDate);
                                        }

       $withdraws =  $query->orderBy('id', 'desc')
                            ->paginate(15)
                            ->appends($request->only(['start_date', 'end_date']));



        return view('admin.withdraw_request.index', compact('withdraws'));
    }

    public function statusChangeIndex(Request $request, WithDrawRequest $withdraw)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
            'amount' => 'required|numeric|min:0',
            'player' => 'required|exists:users,id',
        ]);

        try {
            $agent = Auth::user();
            $player = User::find($request->player);

            if ($request->status == 1 && $player->balanceFloat < $request->amount) {
                return redirect()->route('admin.agent.withdraw')->with('error', 'Player Balance insufficient!');
            }

            $withdraw->update([
                'status' => $request->status,
            ]);

            if ($request->status == 1) {
                app(WalletService::class)->transfer($player, $agent, $request->amount, TransactionName::DebitTransfer);
            }

            return redirect()->route('admin.agent.withdraw')->with('success', 'Withdraw status updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function statusChangeReject(Request $request, WithDrawRequest $withdraw)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        try {
            $withdraw->update([
                'status' => $request->status,
            ]);

            return redirect()->route('admin.agent.withdraw')->with('success', 'Withdraw status updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
