<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use App\Models\Category;
use App\Models\LogActivity;

class DashboardController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $itemCount = Item::count();
        $userCount = User::where('role', 'user')->count();
        $loanCount = Loan::count();
        $activities = LogActivity::with('user')
            ->latest()
            ->paginate(5);

        return view('dashboard', compact('categoryCount', 'itemCount', 'userCount', 'loanCount', 'activities'));
    }
}
